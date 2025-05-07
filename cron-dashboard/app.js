// app.js - Main server file with scheduling
const express = require('express');
const sqlite3 = require('sqlite3').verbose();
const { exec } = require('child_process');
const path = require('path');
const schedule = require('node-schedule');
const app = express();
const port = 3000;

// Create/connect to SQLite database
const db = new sqlite3.Database('./cron_status.db', (err) => {
  if (err) {
    console.error('Error connecting to database:', err);
  } else {
    console.log('Connected to the SQLite database');
    
    // Create tables if they don't exist
    db.run(`CREATE TABLE IF NOT EXISTS job_runs (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      job_name TEXT,
      start_time DATETIME,
      end_time DATETIME,
      status TEXT,
      output TEXT
    )`);
    
    db.run(`CREATE TABLE IF NOT EXISTS schedules (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      job_name TEXT,
      schedule_pattern TEXT,
      enabled INTEGER DEFAULT 1,
      description TEXT,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )`);
  }
});

// Middleware
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(express.static('public'));

// Function to execute the job
function executeJob(jobName = null) {
  return new Promise((resolve, reject) => {
    const scriptPath = process.env.SCRIPT_PATH || '../rpscrape/scripts/racecards.py';
    const scriptParam = process.env.SCRIPT_PARAM || 'today';
    const name = jobName || path.basename(scriptPath);
    
    // Insert run record with "running" status
    db.run(
      `INSERT INTO job_runs (job_name, start_time, status) VALUES (?, datetime('now'), ?)`,
      [name, 'running'],
      function(err) {
        if (err) {
          reject(err);
          return;
        }
        
        const runId = this.lastID;
        
        // Get the directory of the script
        const scriptDir = path.dirname(scriptPath);
        
        // Execute the Python script from its own directory
        exec(`cd "${scriptDir}" && /Library/Frameworks/Python.framework/Versions/3.12/bin/python3 ${path.basename(scriptPath)} ${scriptParam}`, (error, stdout, stderr) => {
          const status = error ? 'failed' : 'completed';
          const output = stdout + (stderr ? '\nErrors:\n' + stderr : '');
          
          // Update record with results
          db.run(
            `UPDATE job_runs SET end_time = datetime('now'), status = ?, output = ? WHERE id = ?`,
            [status, output, runId],
            (err) => {
              if (err) {
                console.error('Error updating job run:', err);
                reject(err);
              } else {
                resolve({ runId, status });
              }
            }
          );
        });
      }
    );
  });
}

// Routes
app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

// API to get job history
app.get('/api/history', (req, res) => {
  db.all(`SELECT * FROM job_runs ORDER BY start_time DESC LIMIT 50`, [], (err, rows) => {
    if (err) {
      res.status(500).json({ error: err.message });
      return;
    }
    res.json(rows);
  });
});

// API to run job manually
app.post('/api/run', async (req, res) => {
  try {
    const result = await executeJob();
    res.json({ 
      message: 'Job started successfully', 
      runId: result.runId 
    });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// API to get job status
app.get('/api/status/:id', (req, res) => {
  db.get(`SELECT * FROM job_runs WHERE id = ?`, [req.params.id], (err, row) => {
    if (err) {
      res.status(500).json({ error: err.message });
      return;
    }
    res.json(row);
  });
});

// API to get all schedules
app.get('/api/schedules', (req, res) => {
  db.all(`SELECT * FROM schedules ORDER BY created_at DESC`, [], (err, rows) => {
    if (err) {
      res.status(500).json({ error: err.message });
      return;
    }
    res.json(rows);
  });
});

// API to add a new schedule
app.post('/api/schedules', (req, res) => {
  const { schedulePattern, description } = req.body;
  
  if (!schedulePattern) {
    res.status(400).json({ error: 'Schedule pattern is required' });
    return;
  }
  
  const scriptPath = process.env.SCRIPT_PATH || '../rpscrape/scripts/racecards.py';
  const jobName = path.basename(scriptPath);
  
  db.run(
    `INSERT INTO schedules (job_name, schedule_pattern, description) VALUES (?, ?, ?)`,
    [jobName, schedulePattern, description || ''],
    function(err) {
      if (err) {
        res.status(500).json({ error: err.message });
        return;
      }
      
      const scheduleId = this.lastID;
      
      // Create the schedule
      try {
        createScheduleFromPattern(scheduleId, jobName, schedulePattern);
        res.json({ 
          message: 'Schedule created successfully', 
          scheduleId: scheduleId 
        });
      } catch (error) {
        // If scheduling fails, delete the record
        db.run(`DELETE FROM schedules WHERE id = ?`, [scheduleId]);
        res.status(400).json({ error: error.message });
      }
    }
  );
});

// API to toggle schedule (enable/disable)
app.put('/api/schedules/:id/toggle', (req, res) => {
  const scheduleId = req.params.id;
  
  db.get(`SELECT * FROM schedules WHERE id = ?`, [scheduleId], (err, row) => {
    if (err) {
      res.status(500).json({ error: err.message });
      return;
    }
    
    if (!row) {
      res.status(404).json({ error: 'Schedule not found' });
      return;
    }
    
    const newStatus = row.enabled ? 0 : 1;
    
    db.run(
      `UPDATE schedules SET enabled = ? WHERE id = ?`,
      [newStatus, scheduleId],
      function(err) {
        if (err) {
          res.status(500).json({ error: err.message });
          return;
        }
        
        if (newStatus === 0) {
          // Disable the schedule
          if (activeSchedules[scheduleId]) {
            activeSchedules[scheduleId].cancel();
            delete activeSchedules[scheduleId];
          }
        } else {
          // Enable the schedule
          createScheduleFromPattern(scheduleId, row.job_name, row.schedule_pattern);
        }
        
        res.json({ 
          message: `Schedule ${newStatus ? 'enabled' : 'disabled'} successfully` 
        });
      }
    );
  });
});

// API to delete a schedule
app.delete('/api/schedules/:id', (req, res) => {
  const scheduleId = req.params.id;
  
  // Cancel the active schedule if it exists
  if (activeSchedules[scheduleId]) {
    activeSchedules[scheduleId].cancel();
    delete activeSchedules[scheduleId];
  }
  
  db.run(
    `DELETE FROM schedules WHERE id = ?`,
    [scheduleId],
    function(err) {
      if (err) {
        res.status(500).json({ error: err.message });
        return;
      }
      
      if (this.changes === 0) {
        res.status(404).json({ error: 'Schedule not found' });
        return;
      }
      
      res.json({ 
        message: 'Schedule deleted successfully'
      });
    }
  );
});

// Store active schedules
const activeSchedules = {};

// Function to create a schedule from a pattern
function createScheduleFromPattern(id, jobName, pattern) {
  try {
    // Cancel existing schedule if it exists
    if (activeSchedules[id]) {
      activeSchedules[id].cancel();
    }
    
    // Create new schedule
    activeSchedules[id] = schedule.scheduleJob(pattern, async () => {
      console.log(`Running scheduled job ${id} (${jobName}) at ${new Date()}`);
      try {
        await executeJob(jobName);
      } catch (err) {
        console.error(`Error executing scheduled job ${id}:`, err);
      }
    });
    
    if (!activeSchedules[id]) {
      throw new Error('Invalid schedule pattern');
    }
    
    console.log(`Schedule created: ${id} (${pattern})`);
  } catch (error) {
    console.error(`Error creating schedule ${id}:`, error);
    throw error;
  }
}

// Load existing schedules on startup
function loadExistingSchedules() {
  db.all(`SELECT * FROM schedules WHERE enabled = 1`, [], (err, rows) => {
    if (err) {
      console.error('Error loading schedules:', err);
      return;
    }
    
    console.log(`Loading ${rows.length} schedules`);
    
    rows.forEach(row => {
      try {
        createScheduleFromPattern(row.id, row.job_name, row.schedule_pattern);
      } catch (error) {
        console.error(`Error loading schedule ${row.id}:`, error);
      }
    });
  });
}

// Start the server
app.listen(port, () => {
  console.log(`Server running at http://localhost:${port}`);
  loadExistingSchedules();
});