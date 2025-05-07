<?php
// includes/date-slider.php
// Date slider component for racing page

// Get the selected date from the page
$selectedDate = isset($requestedDate) ? $requestedDate : date('Y-m-d');
$selectedDateTime = strtotime($selectedDate);

// Create a date range (7 days centered on selected date)
$dateRange = [];
for ($i = -3; $i <= 3; $i++) {
    $dateTimestamp = strtotime("$i days", $selectedDateTime);
    $dateInfo = [
        'date' => date('Y-m-d', $dateTimestamp),
        'day_name' => date('D', $dateTimestamp),
        'day_number' => date('j', $dateTimestamp),
        'month' => date('M', $dateTimestamp),
        'is_today' => date('Y-m-d', $dateTimestamp) === date('Y-m-d'),
        'is_selected' => date('Y-m-d', $dateTimestamp) === $selectedDate
    ];
    $dateRange[] = $dateInfo;
}
?>

<section class="date-slider-section">
    <div class="container">
        <div class="date-slider-container">
            <button class="date-nav-btn prev-date" aria-label="Previous date">
                <i class="fas fa-chevron-left"></i>
            </button>
            
            <div class="date-slider">
                <?php foreach ($dateRange as $dateInfo): 
                    $activeClass = $dateInfo['is_selected'] ? 'active' : '';
                    $todayClass = $dateInfo['is_today'] ? 'today' : '';
                ?>
                    <a href="?date=<?php echo $dateInfo['date']; ?>" 
                       class="date-item <?php echo $activeClass; ?> <?php echo $todayClass; ?>"
                       data-date="<?php echo $dateInfo['date']; ?>">
                        <div class="day-name"><?php echo $dateInfo['day_name']; ?></div>
                        <div class="day-number"><?php echo $dateInfo['day_number']; ?></div>
                        <div class="day-month"><?php echo $dateInfo['month']; ?></div>
                    </a>
                <?php endforeach; ?>
            </div>
            
            <button class="date-nav-btn next-date" aria-label="Next date">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</section>