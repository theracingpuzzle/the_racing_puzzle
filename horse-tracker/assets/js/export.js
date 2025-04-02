/**
 * Horse Tracker - Export and Print Functionality
 */
const HorseExport = {
    /**
     * Initialize the export module
     */
    init: function() {
        this.bindEvents();
    },
    
    /**
     * Bind event handlers
     */
    bindEvents: function() {
        // Export to CSV
        $('#exportCSV').on('click', this.handleExportCSV);
        
        // Print list
        $('#printList').on('click', this.handlePrint);
    },
    
    /**
     * Handle CSV export
     */
    handleExportCSV: function() {
        // Get current filters
        const filters = {
            search: $('#searchInput').val().toLowerCase(),
            trainer: $('#filterTrainer').val(),
            sort: $('#sortOption').val(),
            export: 'csv'
        };
        
        // Request CSV data
        axios.get('api/get-horses.php', { params: filters })
            .then(response => {
                if (response.data.success && response.data.csvData) {
                    // Create and download CSV file
                    HorseExport.downloadCSV(response.data.csvData, 'horse-tracker-export.csv');
                } else {
                    alert('Error generating CSV export');
                }
            })
            .catch(error => {
                console.error('Error during CSV export:', error);
                alert('An error occurred during export.');
            });
    },
    
    /**
     * Create and download a CSV file
     */
    downloadCSV: function(csvContent, filename) {
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        
        const link = document.createElement('a');
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    },
    
    /**
     * Handle print functionality
     */
    handlePrint: function() {
        // Create a print-friendly version
        const printWindow = window.open('', '_blank');
        
        // Collect current visible data
        const tableData = [];
        $('#horse-table-body tr').each(function() {
            const row = $(this);
            tableData.push({
                name: row.find('td:eq(0) span').text(),
                trainer: row.find('td:eq(1)').text(),
                updated: row.find('td:eq(2) span').text(),
                status: row.find('td:eq(3) span').text()
            });
        });
        
        // Create print HTML
        let printContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Horse Tracker - Print List</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    h1 { color: #333; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
                    th { background-color: #f2f2f2; }
                    .print-header { display: flex; justify-content: space-between; align-items: center; }
                    .print-date { font-size: 14px; color: #666; }
                </style>
            </head>
            <body>
                <div class="print-header">
                    <h1>Horse Tracker - Horse List</h1>
                    <div class="print-date">Printed on: ${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}</div>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>Horse Name</th>
                            <th>Trainer</th>
                            <th>Last Updated</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        // Add table rows
        tableData.forEach(horse => {
            printContent += `
                <tr>
                    <td>${horse.name}</td>
                    <td>${horse.trainer}</td>
                    <td>${horse.updated}</td>
                    <td>${horse.status}</td>
                </tr>
            `;
        });
        
        // Close HTML
        printContent += `
                    </tbody>
                </table>
                <script>
                    window.onload = function() {
                        window.print();
                    }
                </script>
            </body>
            </html>
        `;
        
        // Write to print window and trigger print
        printWindow.document.write(printContent);
        printWindow.document.close();
    }
};

// Initialize on document ready
$(document).ready(function() {
    HorseExport.init();
});