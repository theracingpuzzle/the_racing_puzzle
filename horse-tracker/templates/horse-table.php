<table class="table horse-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Breed</th>
            <th>Age</th>
            <th>Color</th>
            <th>Health Status</th>
            <th>Last Check</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Connect to database (this should be in a separate include file in production)
        $conn = new mysqli("localhost", "username", "password", "horse_db");
        
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // Define query parameters (for filtering/sorting)
        $where = "1=1"; // Default where clause
        $order = "name ASC"; // Default order
        
        // Apply filters if set
        if (isset($_GET['breed']) && !empty($_GET['breed'])) {
            $breed = $conn->real_escape_string($_GET['breed']);
            $where .= " AND breed = '$breed'";
        }
        
        if (isset($_GET['min_age']) && is_numeric($_GET['min_age'])) {
            $min_age = (int)$_GET['min_age'];
            $where .= " AND age >= $min_age";
        }
        
        if (isset($_GET['max_age']) && is_numeric($_GET['max_age'])) {
            $max_age = (int)$_GET['max_age'];
            $where .= " AND age <= $max_age";
        }
        
        if (isset($_GET['health_status']) && !empty($_GET['health_status'])) {
            $status = $conn->real_escape_string($_GET['health_status']);
            $where .= " AND health_status = '$status'";
        }
        
        // Apply search if provided
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $conn->real_escape_string($_GET['search']);
            $where .= " AND (name LIKE '%$search%' OR breed LIKE '%$search%')";
        }
        
        // Apply sorting
        if (isset($_GET['sort']) && !empty($_GET['sort'])) {
            $sort_options = [
                'name_asc' => 'name ASC',
                'name_desc' => 'name DESC',
                'age_asc' => 'age ASC',
                'age_desc' => 'age DESC',
                'last_check_asc' => 'last_check ASC',
                'last_check_desc' => 'last_check DESC'
            ];
            
            if (array_key_exists($_GET['sort'], $sort_options)) {
                $order = $sort_options[$_GET['sort']];
            }
        }
        
        // Pagination
        $records_per_page = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $records_per_page;
        
        // Query to get total records (for pagination)
        $count_query = "SELECT COUNT(*) as total FROM horses WHERE $where";
        $count_result = $conn->query($count_query);
        $count_row = $count_result->fetch_assoc();
        $total_records = $count_row['total'];
        $total_pages = ceil($total_records / $records_per_page);
        
        // Main query
        $query = "SELECT * FROM horses WHERE $where ORDER BY $order LIMIT $offset, $records_per_page";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                // Calculate health status class
                $status_class = "";
                switch($row['health_status']) {
                    case 'Excellent':
                        $status_class = "text-success";
                        break;
                    case 'Good':
                        $status_class = "text-info";
                        break;
                    case 'Fair':
                        $status_class = "text-warning";
                        break;
                    case 'Poor':
                        $status_class = "text-danger";
                        break;
                }
                
                // Format the last check date
                $last_check = new DateTime($row['last_check']);
                $formatted_date = $last_check->format('M d, Y');
                
                echo "<tr>
                    <td>{$row['name']}</td>
                    <td>{$row['breed']}</td>
                    <td>{$row['age']}</td>
                    <td>{$row['color']}</td>
                    <td class='$status_class'>{$row['health_status']}</td>
                    <td>$formatted_date</td>
                    <td>
                        <button class='btn btn-info btn-sm view-horse' data-id='{$row['id']}' data-bs-toggle='modal' data-bs-target='#horseDetailsModal'>
                            <i class='bi bi-eye'></i> View
                        </button>
                        <button class='btn btn-warning btn-sm edit-horse' data-id='{$row['id']}' data-bs-toggle='modal' data-bs-target='#editHorseModal'>
                            <i class='bi bi-pencil'></i> Edit
                        </button>
                        <button class='btn btn-danger btn-sm delete-horse' data-id='{$row['id']}'>
                            <i class='bi bi-trash'></i>
                        </button>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='7' class='text-center'>No horses found</td></tr>";
        }
        
        // Close the database connection
        $conn->close();
        ?>
    </tbody>
</table>