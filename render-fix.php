<?php
// Only apply this fix on Render
if (getenv('RENDER') === 'true') {
    ob_start(); // Start output buffering
}
?>