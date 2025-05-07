document.addEventListener('DOMContentLoaded', () => {
    // DOM elements
    const runJobBtn = document.getElementById('run-job');
    const refreshBtn = document.getElementById('refresh');
    const showScheduleBtn = document.getElementById('show-schedule');
    const lastStatus = document.getElementById('last-status');
    const lastTime = document.getElementById('last-time');
    const totalRuns = document.getElementById('total-runs');
    const successRate = document.getElementById('success-rate');
    const historyData = document.getElementById('history-data');
    
    // Modal elements
    const outputModal = document.getElementById('output-modal');
    const scheduleModal = document.getElementById('schedule-modal');
    const closeModalBtns = document.querySelectorAll('.close');
    const jobOutput = document.getElementById('job-output');
    
    // Schedule form elements
    const addScheduleForm = document.getElementById('add-schedule-form');
    const schedulePattern = document.getElementById('schedule-pattern');
    const scheduleDescription = document.getElementById('schedule-description');
    const schedulesData = document.getElementById('schedules-data');
    
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
      
      if (data.length === 0) {
        historyData.innerHTML = `
          <tr>
            <td colspan="6" class="empty-table">No job runs found</td>
          </tr>
        `;
        return;
      }
      
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
        outputModal.style.display = 'block';
      } catch (error) {
        console.error('Error fetching job output:', error);
        jobOutput.textContent = 'Error fetching job output';
        outputModal.style.display = 'block';
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
    
    // Fetch schedules data
    async function fetchSchedules() {
      try {
        const response = await fetch('/api/schedules');
        const data = await response.json();
        displaySchedules(data);
      } catch (error) {
        console.error('Error fetching schedules:', error);
      }
    }
    
    // Display schedules in table
    function displaySchedules(data) {
      schedulesData.innerHTML = '';
      
      if (data.length === 0) {
        schedulesData.innerHTML = `
          <tr>
            <td colspan="5" class="empty-table">No schedules configured</td>
          </tr>
        `;
        return;
      }
      
      data.forEach(schedule => {
        const row = document.createElement('tr');
        
        row.innerHTML = `
          <td>${schedule.id}</td>
          <td><code>${schedule.schedule_pattern}</code></td>
          <td>${schedule.description || '-'}</td>
          <td>
            <span class="status-badge ${schedule.enabled ? 'enabled' : 'disabled'}">
              ${schedule.enabled ? 'Active' : 'Inactive'}
            </span>
          </td>
          <td>
            <button class="btn small ${schedule.enabled ? 'warning' : 'success'} toggle-schedule" data-id="${schedule.id}">
              ${schedule.enabled ? 'Disable' : 'Enable'}
            </button>
            <button class="btn small danger delete-schedule" data-id="${schedule.id}">Delete</button>
          </td>
        `;
        
        schedulesData.appendChild(row);
      });
      
      // Add event listeners for schedule actions
      document.querySelectorAll('.toggle-schedule').forEach(btn => {
        btn.addEventListener('click', async () => {
          const id = btn.getAttribute('data-id');
          await toggleSchedule(id);
        });
      });
      
      document.querySelectorAll('.delete-schedule').forEach(btn => {
        btn.addEventListener('click', async () => {
          const id = btn.getAttribute('data-id');
          if (confirm('Are you sure you want to delete this schedule?')) {
            await deleteSchedule(id);
          }
        });
      });
    }
    
    // Toggle schedule (enable/disable)
    async function toggleSchedule(id) {
      try {
        const response = await fetch(`/api/schedules/${id}/toggle`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json'
          }
        });
        
        if (response.ok) {
          fetchSchedules(); // Refresh schedules
        } else {
          const data = await response.json();
          alert(`Error: ${data.error || 'Failed to toggle schedule'}`);
        }
      } catch (error) {
        console.error('Error toggling schedule:', error);
        alert('Failed to toggle schedule');
      }
    }
    
    // Delete schedule
    async function deleteSchedule(id) {
      try {
        const response = await fetch(`/api/schedules/${id}`, {
          method: 'DELETE'
        });
        
        if (response.ok) {
          fetchSchedules(); // Refresh schedules
        } else {
          const data = await response.json();
          alert(`Error: ${data.error || 'Failed to delete schedule'}`);
        }
      } catch (error) {
        console.error('Error deleting schedule:', error);
        alert('Failed to delete schedule');
      }
    }
    
    // Add new schedule
    async function addSchedule(pattern, description) {
      try {
        const response = await fetch('/api/schedules', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            schedulePattern: pattern,
            description: description
          })
        });
        
        const data = await response.json();
        
        if (response.ok) {
          // Clear form
          schedulePattern.value = '';
          scheduleDescription.value = '';
          fetchSchedules(); // Refresh schedules
        } else {
          alert(`Error: ${data.error || 'Failed to add schedule'}`);
        }
      } catch (error) {
        console.error('Error adding schedule:', error);
        alert('Failed to add schedule');
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
    
    showScheduleBtn.addEventListener('click', () => {
      fetchSchedules();
      scheduleModal.style.display = 'block';
    });
    
    closeModalBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        outputModal.style.display = 'none';
        scheduleModal.style.display = 'none';
      });
    });
    
    window.addEventListener('click', (event) => {
      if (event.target === outputModal) {
        outputModal.style.display = 'none';
      }
      if (event.target === scheduleModal) {
        scheduleModal.style.display = 'none';
      }
    });
    
    addScheduleForm.addEventListener('submit', (event) => {
      event.preventDefault();
      addSchedule(schedulePattern.value, scheduleDescription.value);
    });
    
    // Initial data fetch
    fetchHistory();
  });