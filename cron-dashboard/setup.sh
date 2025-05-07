#!/bin/bash
# setup.sh - Easy setup script for the dashboard

# Create required directories if they don't exist
mkdir -p public

# Echo status
echo "Creating required files..."

# Write app.js
cat > app.js << 'EOF'
const express = require('express');
const sqlite3 = require('sqlite3').verbose();
const { exec } = require('child_process');
const path = require('path');
const app = express();
const port = 3000;

// Create/connect to SQLite database
const db = new sqlite3.Database('./cron_status.db', (err) => {
  if (err) {
    console.error('Error connecting to database:', err);
  } else {
    console.log('Connected to the SQLite database');
    
    // Create table if it doesn't exist
    db.run(`CREATE TABLE IF NOT EXISTS job_runs (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      job_name TEXT,
      start_time DATETIME,
      end_time DATETIME,
      status TEXT,
      output TEXT
    )`);
  }
});

// Middleware
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(express.static('public'));

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
app.post('/api/run', (req, res) => {
  const scriptPath = process.env.SCRIPT_PATH || 'rpscrape/scripts/racecards.py';
  const scriptParam = process.env.SCRIPT_PARAM || 'today';
  const jobName = path.basename(scriptPath);
  
  // Insert run record with "running" status
  db.run(
    `INSERT INTO job_runs (job_name, start_time, status) VALUES (?, datetime('now'), ?)`,
    [jobName, 'running'],
    function(err) {
      if (err) {
        res.status(500).json({ error: err.message });
        return;
      }
      
      const runId = this.lastID;
      
      // Execute the Python script with parameter
      exec(`python ${scriptPath} ${scriptParam}`, (error, stdout, stderr) => {
        const status = error ? 'failed' : 'completed';
        const output = stdout + (stderr ? '\nErrors:\n' + stderr : '');
        
        // Update record with results
        db.run(
          `UPDATE job_runs SET end_time = datetime('now'), status = ?, output = ? WHERE id = ?`,
          [status, output, runId],
          (err) => {
            if (err) {
              console.error('Error updating job run:', err);
            }
          }
        );
      });
      
      res.json({ 
        message: 'Job started successfully', 
        runId: runId 
      });
    }
  );
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

// Start the server
app.listen(port, () => {
  console.log(`Server running at http://localhost:${port}`);
});
EOF

# Write index.html
cat > public/index.html << 'EOF'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Python Script Status Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Python Script Status Dashboard</h1>
            <div class="actions">
                <button id="run-job" class="btn primary">Run Script Now</button>
                <button id="refresh" class="btn secondary">Refresh Data</button>
            </div>
        </header>

        <div class="status-cards">
            <div class="card">
                <h3>Last Run Status</h3>
                <div id="last-status" class="status-indicator">Unknown</div>
                <div id="last-time">Never</div>
            </div>
            <div class="card">
                <h3>Total Runs</h3>
                <div id="total-runs" class="metric">0</div>
            </div>
            <div class="card">
                <h3>Success Rate</h3>
                <div id="success-rate" class="metric">0%</div>
            </div>
        </div>

        <div class="section">
            <h2>Run History</h2>
            <div class="table-container">
                <table id="history-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Script</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody id="history-data">
                        <tr>
                            <td colspan="6" class="empty-table">No job runs found</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="output-modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Script Output</h2>
                <pre id="job-output"></pre>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
EOF

# Write styles.css
cat > public/styles.css << 'EOF'
:root {
  --primary-color: #4a6cf7;
  --success-color: #28a745;
  --warning-color: #ffc107;
  --danger-color: #dc3545;
  --neutral-color: #6c757d;
  --light-color: #f8f9fa;
  --dark-color: #343a40;
}

* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
  line-height: 1.6;
  color: var(--dark-color);
  background-color: #f5f7fb;
}

.container {
  width: 95%;
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  padding-bottom: 15px;
  border-bottom: 1px solid #eee;
}

h1 {
  color: var(--dark-color);
}

h2 {
  margin-bottom: 15px;
  color: var(--dark-color);
}

h3 {
  font-size: 16px;
  margin-bottom: 10px;
  color: var(--neutral-color);
}

.btn {
  padding: 8px 15px;
  margin-left: 10px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.3s ease;
}

.primary {
  background-color: var(--primary-color);
  color: white;
}

.primary:hover {
  background-color: #3a5bd7;
}

.secondary {
  background-color: var(--light-color);
  color: var(--dark-color);
  border: 1px solid #ddd;
}

.secondary:hover {
  background-color: #e2e6ea;
}

.status-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.card {
  background-color: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.status-indicator {
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 5px;
}

.status-indicator.completed {
  color: var(--success-color);
}

.status-indicator.running {
  color: var(--primary-color);
}

.status-indicator.failed {
  color: var(--danger-color);
}

.metric {
  font-size: 24px;
  font-weight: bold;
}

.section {
  background-color: white;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 30px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.table-container {
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}

th, td {
  padding: 12px 15px;
  text-align: left;
  border-bottom: 1px solid #eee;
}

th {
  background-color: #f9fafb;
  font-weight: 500;
}

tr:hover {
  background-color: #f9fafb;
}

.empty-table {
  text-align: center;
  color: var(--neutral-color);
  padding: 30px 0;
}

.view-output {
  color: var(--primary-color);
  text-decoration: underline;
  cursor: pointer;
}

/* Modal styles */
.modal {
  display: none;
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
  background-color: white;
  margin: 5% auto;
  padding: 20px;
  border-radius: 8px;
  width: 80%;
  max-width: 800px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.close {
  color: var(--neutral-color);
  float: right;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}

.close:hover {
  color: var(--dark-color);
}

pre {
  background-color: #f8f9fa;
  padding: 15px;
  border-radius: 4px;
  border: 1px solid #eee;
  overflow-x: auto;
  white-space: pre-wrap;
  margin-top: 15px;
}

@media (max-width: 768px) {
  header {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .actions {
    margin-top: 15px;
  }
  
  .btn {
    margin-top: 10px;
    margin-left: 0;
    margin-right: 10px;
  }
}
EOF

# Write script.js
cat > public/script.js << 'EOF'
document.addEventListener('DOMContentLoaded', () => {
  // DOM elements
  const runJobBtn = document.getElementById('run-job');
  const refreshBtn = document.getElementById('refresh');
  const lastStatus = document.getElementById('last-status');
  const lastTime = document.getElementById('last-time');
  const totalRuns = document.getElementById('total-runs');
  const successRate = document.getElementById('success-rate');
  const historyData = document.getElementById('history-data');
  const modal = document.getElementById('output-modal');
  const closeModal = document.querySelector('.close');
  const jobOutput = document.getElementById('job-output');
  
  // Fetch job history data
  async function fetchHistory() {
    try {
      const response = await fetch('/api/history');
      const data = await response.json();
      
      if (data.length > 0) {
        updateMetrics(data);
        displayHistory(data);
      }
    } catch (error) {
      console.error('Error fetching job history:', error);
    }
  }
  
  // Update metrics based on history data
  function updateMetrics(data) {
    // Last run status
    const lastRun = data[0];
    if (lastRun) {
      lastStatus.textContent = capitalizeFirstLetter(lastRun.status);
      lastStatus.className = 'status-indicator ' + lastRun.status;
      
      const startTime = new Date(lastRun.start_time);
      lastTime.textContent = formatDate(startTime);
      
      // Total runs
      totalRuns.textContent = data.length;
      
      // Success rate
      const completedRuns = data.filter(run => run.status === 'completed').length;
      const rate = data.length > 0 ? Math.round((completedRuns / data.length) * 100) : 0;
      successRate.textContent = `${rate}%`;
    }
  }
  
  // Display job history in table
  function displayHistory(data) {
    historyData.innerHTML = '';
    
    data.forEach(run => {
      const row = document.createElement('tr');
      
      const startTime = run.start_time ? formatDate(new Date(run.start_time)) : 'N/A';
      const endTime = run.end_time ? formatDate(new Date(run.end_time)) : 'In progress';
      
      row.innerHTML = `
        <td>${run.id}</td>
        <td>${run.job_name}</td>
        <td>${startTime}</td>
        <td>${endTime}</td>
        <td><span class="status-indicator ${run.status}">${capitalizeFirstLetter(run.status)}</span></td>
        <td><span class="view-output" data-id="${run.id}">View Output</span></td>
      `;
      
      historyData.appendChild(row);
    });
    
    // Add event listeners for view output links
    document.querySelectorAll('.view-output').forEach(link => {
      link.addEventListener('click', async () => {
        const id = link.getAttribute('data-id');
        await showJobOutput(id);
      });
    });
  }
  
  // Show job output in modal
  async function showJobOutput(id) {
    try {
      const response = await fetch(`/api/status/${id}`);
      const data = await response.json();
      
      jobOutput.textContent = data.output || 'No output available';
      modal.style.display = 'block';
    } catch (error) {
      console.error('Error fetching job output:', error);
      jobOutput.textContent = 'Error fetching job output';
      modal.style.display = 'block';
    }
  }
  
  // Run job manually
  async function runJob() {
    try {
      runJobBtn.disabled = true;
      runJobBtn.textContent = 'Starting...';
      
      const response = await fetch('/api/run', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        }
      });
      
      const data = await response.json();
      
      if (data.runId) {
        pollJobStatus(data.runId);
      }
    } catch (error) {
      console.error('Error running job:', error);
      alert('Failed to start job');
      runJobBtn.disabled = false;
      runJobBtn.textContent = 'Run Script Now';
    }
  }
  
  // Poll job status until it completes
  async function pollJobStatus(id) {
    try {
      const response = await fetch(`/api/status/${id}`);
      const data = await response.json();
      
      if (data.status === 'running') {
        // Check again in 1 second
        setTimeout(() => pollJobStatus(id), 1000);
      } else {
        runJobBtn.disabled = false;
        runJobBtn.textContent = 'Run Script Now';
        fetchHistory(); // Refresh data
      }
    } catch (error) {
      console.error('Error polling job status:', error);
      runJobBtn.disabled = false;
      runJobBtn.textContent = 'Run Script Now';
    }
  }
  
  // Helper function to format dates
  function formatDate(date) {
    return new Intl.DateTimeFormat('default', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit'
    }).format(date);
  }
  
  // Helper function to capitalize first letter
  function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }
  
  // Event listeners
  runJobBtn.addEventListener('click', runJob);
  refreshBtn.addEventListener('click', fetchHistory);
  
  closeModal.addEventListener('click', () => {
    modal.style.display = 'none';
  });
  
  window.addEventListener('click', (event) => {
    if (event.target === modal) {
      modal.style.display = 'none';
    }
  });
  
  // Initial data fetch
  fetchHistory();
});
EOF

# Make setup script executable
chmod +x setup.sh

# Create package.json
cat > package.json << 'EOF'
{
  "name": "script-dashboard",
  "version": "1.0.0",
  "description": "Dashboard for manually running and monitoring Python scripts",
  "main": "app.js",
  "scripts": {
    "start": "node app.js"
  },
  "dependencies": {
    "express": "^4.18.2",
    "sqlite3": "^5.1.6"
  }
}
EOF

# Install dependencies
echo "Installing dependencies..."
npm install

echo "Setup complete! You can now start the dashboard with:"
echo "node app.js"
echo ""
echo "Access the dashboard at http://localhost:3000"