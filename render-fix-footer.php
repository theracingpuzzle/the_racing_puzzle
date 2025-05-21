<?php
// Only apply this fix on Render
if (getenv('RENDER') === 'true') {
    $content = ob_get_clean(); // Get buffer content and end buffering
    
    // Add the fix script
    $fixScript = <<<'HTML'
<script>
// Fix for port 10000 links on Render
document.addEventListener('DOMContentLoaded', function() {
    // Fix all links in the document
    var links = document.getElementsByTagName('a');
    for (var i = 0; i < links.length; i++) {
        var link = links[i];
        if (link.href.includes(':10000')) {
            link.href = link.href.replace(':10000', '');
        }
    }
    
    // Fix dynamically created links
    document.addEventListener('click', function(e) {
        if (e.target.tagName === 'A' && e.target.href.includes(':10000')) {
            e.preventDefault();
            window.location.href = e.target.href.replace(':10000', '');
        }
    });
    
    // Monitor form submissions
    document.addEventListener('submit', function(e) {
        var form = e.target;
        if (form.action && form.action.includes(':10000')) {
            e.preventDefault();
            form.action = form.action.replace(':10000', '');
            form.submit();
        }
    });
    
    // Patch window.location methods
    var originalHref = window.location.href;
    Object.defineProperty(window.location, 'href', {
        set: function(url) {
            if (typeof url === 'string' && url.includes(':10000')) {
                url = url.replace(':10000', '');
            }
            window.location = url;
        },
        get: function() {
            return originalHref;
        }
    });
});
</script>
HTML;
    
    // Insert the fix script just before </head> if it exists
    if (strpos($content, '</head>') !== false) {
        $content = str_replace('</head>', $fixScript . '</head>', $content);
    } 
    // If </head> doesn't exist, insert at the beginning of <body> if it exists
    else if (strpos($content, '<body') !== false) {
        $pos = strpos($content, '<body');
        $pos = strpos($content, '>', $pos) + 1;
        $content = substr_replace($content, $fixScript, $pos, 0);
    } 
    // If neither exists, just add to the very beginning
    else {
        $content = $fixScript . $content;
    }
    
    echo $content;
}
?>