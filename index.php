<?php

include 'content/config.php';  // Configuration
include 'content/connect.php'; // Connect to MySQL
include 'content/functions.php'; // functions


$fn               = 'Waposat';
$meta_description = null;

if (connection) {
    $result = mysql_query("SELECT url, `meta-title`, `meta-description`, title, content  FROM `modulo` WHERE url = '$url' limit 0,1");

    // JSON/JSONP respond
    if (isset($_GET['api'])) {
        include 'content/api.php';
        exit;
    }

    $pagetitle = $pagetitle_error = 'Page not found';
    $title     = $title_error     = '404 Not Found';
    $content   = $content_error   = '<p>Sorry, this page cannot be found.</p>'.$url;

    // Check if url exist
    if (@mysql_num_rows($result)) {


        while ($row = @mysql_fetch_array($result, MYSQL_ASSOC)) {
            $row[]            = array('row' => array_map('htmlspecialchars', $row));
            $urlid            = $row['url'];
            $title            = isset($row['title']) ? $row['title'] : null;
            $meta_title       = isset($row['meta-title']) ? $row['meta-title'] : $title;
            $meta_description = isset($row['meta-description']) ? $row['meta-description'] : null;
            $content          = isset($row['content']) ? $row['content'] : null;
        }

        $pagetitle = $meta_title . ' - ' . $fn;

        // SEO page title improvement for the root page
        if (strlen($url) == 0) {
            $pagetitle = $fn;
        }
    } else { // If URL does not exist, return 404 error
        http_response_code(404);
    }
}

// Avoid undefined variables
$note           = isset($note) ? $note : null;
$optional_title = isset($optional_title) ? $optional_title : null;
$content        = isset($content) ? $content : null;


$metadata  = "<title>".strip_tags(utf8_encode($pagetitle))."</title>";

 
$metadata .= "\n<meta property=og:title content='".strip_tags($pagetitle)."'>";
$metadata .= "\n<meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\">";
$metadata .= "\n<meta name=referrer content=never>";
$metadata .= "\n<meta name=viewport content=\"width=device-width, user-scalable=0\">";


echo "<!DOCTYPE html>
<html itemscope itemtype=http://schema.org/WebPage prefix=\"og: http://ogp.me/ns#\">
<head>
<meta charset=UTF-8>

$metadata

<link rel=stylesheet href=$assets$css>
<link rel=stylesheet href={$assets}jquery.animateSlider.css>
<link rel=stylesheet href={$assets}font-awesome.css></link>


<!--[if lt IE 9]><script src=//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.2/html5shiv.min.js></script><![endif]-->
";
?>

</head>

<body class=clearfix>

<header class=header>
<div class=logo><a class=js-as href='/'><img src=img/water-protection-monitoreo-del-agua-logo-waposat.png></a></div>
<div class=login>Log In</div>
</header>

<main class="main js-content">


<?php
echo utf8_encode($content);
?>
</main>

<footer class="footer"></footer>

<?php
if(connection){


echo "\n<!--[if lt IE 9]><script src=//cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js></script><![endif]-->
<!--[if gte IE 9]><!--><script src=//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js></script><!--<![endif]-->

<script src=$assets$js></script>
<script src={$assets}myscript.min.js></script>
<script src={$assets}jquery.scrollme.js></script>
<script src={$assets}jquery.animateSlider.js></script>
<script src={$assets}modernizr.js></script>
";
?>

<script async>
(function() {
    'use strict';

    // Common variables
    // --------------------------------------------------
    var isDevice = /mobile|android/i.test(navigator.userAgent.toLowerCase()),

        // Remove 300ms click delay on mobile devices by using touchstart event
        // Usage: $(selector).on(pointer, (function() { });
        //pointer  = (('ontouchstart' in window) || window.DocumentTouch && document instanceof window.DocumentTouch) ? 'touchstart' : 'click',

        nav     = $('.js-as'),
        content = $('.js-content'),
        init     = true,
        state    = window.history.pushState !== undefined,
        // Google Universal Analytics tracking
        tracker  = function() {
            if (typeof ga !== 'undefined') {
                return ga && ga('send', 'pageview', {
                    // window.location.pathname + window.location.search + window.location.hash
                    page: decodeURI(window.location.pathname)
                });
            }
        },
        this, request, fadeTimer,
        // Response
        handler = function(data) {
            document.title = data.pagetitle;
            content.fadeTo(20, 1).html(data.content);
            tracker();
        };

    // Avoid console.log on devices and not supported browsers
    // --------------------------------------------------
    if (!window.console || isDevice) {
        window.console = {
            log: function() {}
        };
    }

    // Mobile optimization
    // --------------------------------------------------
    if (isDevice) {
        // Auto-hide mobile device address bar
        if (window.location.hash.indexOf('#') === -1) {
            var hideAddressbar = function() {
                var deviceHeight = screen.height,
                    bodyHeight   = document.body.clientHeight;

                // Viewport height at fullscreen
                // Android 2.3 orientationchange issue - needs for more 50px
                if (deviceHeight >= bodyHeight) {
                    document.body.style.minHeight = deviceHeight + 'px';
                }

                // Perform autoscroll
                setTimeout(window.scrollTo(0, 1), 100);
            };

            // Auto-hide address bar
            hideAddressbar();

            // Hide address bar on device orientationchange
            window.addEventListener('orientationchange', function() {
                // Hide address bar if not already scrolled
                if (window.pageYOffset === 0) {
                    hideAddressbar();
                }
            });
        }
    }

    $.address.state('/').init(function() {
        // Initialize jQuery Address
        nav.address();
    }).change(function(e) {
        if (state && init) {
            init = false;
        } else {
            // Halt previously created request
            if (request && request.readyState !== 4) {
                request.abort();
            }

            // Select link
            nav.each(function() {
                this = $(this);

                if (this.attr('href') === decodeURI($.address.state() + e.path).replace(/\/\//, '/')) {
                    this.addClass('selected').focus();
                } else {
                    this.removeClass('selected');
                }
            });

            // Load API content
            request = $.ajax({
                url: '/api' + (e.path.length !== 1 ? '/' + encodeURI(e.path.substr(1)) : ''),
                //dataType: 'jsonp',
                //jsonpCallback: 'foo',
                //cache: true,
                beforeSend: function() {

                         content.fadeOut(500);


                },
                success: function(data) {

                     	content.hide();
						content.fadeIn(500);
						handler(data);

                },
                error: function(jqXHR, textStatus) {
                    if (fadeTimer) {
                        clearTimeout(fadeTimer);
                    }
                    if (textStatus !== 'abort') {
                        console.log(textStatus);

                        if (textStatus === 'timeout') {
                            content.html('Loading seems to be taking a while...');
                        }

                        nav.removeClass('selected');
                        document.title = '$pagetitle_error';
                        content.fadeTo(20, 1).html('<h1>$title_error</h1>$content_error');
                        tracker();
                    }
                }
            });
        }
    });

    // Bind whatever event to Ajax loaded content
    //$(document).on('click', '.js-as', function(e) {
    //    console.log(e.target);
    //});
})();\n";

<?php

} else {
    echo "\n<script async>";
}

// Optimized Google Analytics snippet http://mathiasbynens.be/notes/async-analytics-snippet
?>
</script>

</body>
</html>
