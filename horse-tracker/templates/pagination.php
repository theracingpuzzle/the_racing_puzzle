<?php
// Pagination variables
$total_pages = isset($total_pages) ? $total_pages : 1;
$current_page = isset($page) ? $page : 1;

// Preserve all other GET parameters
$params = $_GET;
unset($params['page']); // Remove page parameter to add it fresh
$query_string = http_build_query($params);
$query_string = !empty($query_string) ? '&' . $query_string : '';
?>

<div class="pagination">
    <?php if($current_page > 1): ?>
        <a href="?page=1<?php echo $query_string; ?>" title="First page"><i class="bi bi-chevron-double-left"></i></a>
        <a href="?page=<?php echo $current_page - 1 . $query_string; ?>" title="Previous page"><i class="bi bi-chevron-left"></i></a>
    <?php else: ?>
        <span class="disabled"><i class="bi bi-chevron-double-left"></i></span>
        <span class="disabled"><i class="bi bi-chevron-left"></i></span>
    <?php endif; ?>
    
    <?php
    // Calculate range of pages to show
    $range = 2; // Show 2 pages before and after current page
    $start_page = max(1, $current_page - $range);
    $end_page = min($total_pages, $current_page + $range);
    
    // Always show first page
    if($start_page > 1) {
        echo '<a href="?page=1' . $query_string . '">1</a>';
        if($start_page > 2) {
            echo '<span class="ellipsis">...</span>';
        }
    }
    
    // Show page numbers
    for($i = $start_page; $i <= $end_page; $i++) {
        if($i == $current_page) {
            echo '<span class="active">' . $i . '</span>';
        } else {
            echo '<a href="?page=' . $i . $query_string . '">' . $i . '</a>';
        }
    }
    
    // Always show last page
    if($end_page < $total_pages) {
        if($end_page < $total_pages - 1) {
            echo '<span class="ellipsis">...</span>';
        }
        echo '<a href="?page=' . $total_pages . $query_string . '">' . $total_pages . '</a>';
    }
    ?>
    
    <?php if($current_page < $total_pages): ?>
        <a href="?page=<?php echo $current_page + 1 . $query_string; ?>" title="Next page"><i class="bi bi-chevron-right"></i></a>
        <a href="?page=<?php echo $total_pages . $query_string; ?>" title="Last page"><i class="bi bi-chevron-double-right"></i></a>
    <?php else: ?>
        <span class="disabled"><i class="bi bi-chevron-right"></i></span>
        <span class="disabled"><i class="bi bi-chevron-double-right"></i></span>
    <?php endif; ?>
</div>

<div class="text-center mt-2">
    <small class="text-muted">
        Showing page <?php echo $current_page; ?> of <?php echo $total_pages; ?> 
        (<?php echo $total_records; ?> total records)
    </small>
</div>