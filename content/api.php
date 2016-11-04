<?php

// --------------------------------------------------
// API
// --------------------------------------------------

// sleep(3); // Simulate API slow responding

header('X-Robots-Tag: nosnippet');

// Check if url exist
if (@mysql_num_rows($result)) {
  //  include 'content/cache.php'; // HTTP header caching


    $callback      = isset($_GET['callback']) ? $_GET['callback'] : null;
    $issetcallback = strlen($callback) > 0 ? true : false;

    if ($issetcallback) {
        header('Content-Type: application/javascript; charset=utf-8');
    } else {
        header('Content-Type: application/json; charset=utf-8');
    }

    while ($row = @mysql_fetch_array($result, MYSQL_ASSOC)) {
        $row[]       = array('row' => array_map('htmlspecialchars', $row));
        $urlid       = strip_tags($row['url']);
        $meta_title  = utf8_encode(strip_tags($row['meta-title']));
        $title       = utf8_encode($row['title']);

        if (strlen($title) > 0) {
            $fn = ($meta_title !== $title) ? $title : $meta_title;
        } else {
            $fn = $meta_title;
        }

        $pagetitle = $meta_title . ' - Waposat';

        // SEO page title improvement for the root page
        if (strlen($url) == 0) {
            $pagetitle = 'Waposat';
        }


if ((strlen($title) > 0)) {
        $array = array(
            'url' => $urlid,
            'pagetitle' => $pagetitle,
            'title' => $meta_title,
            'content' =>  utf8_encode($row['content'])     );
        }
        else
        {
        $array = array(
            'url' => $urlid,            
            'pagetitle' => $pagetitle,
            'title' => $meta_title, 
            'content' => utf8_encode($row['content']) );
        }

        // UTF8 decoded JSON
        if (version_compare(PHP_VERSION, '5.4', '>=')) {
            // Add option "JSON_PRETTY_PRINT" in case you care more readability than to save some bits
            $data = json_encode($array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } else {
            $data = preg_replace('/\\\\u([a-f0-9]{4})/e', "iconv('UCS-4LE', 'UTF-8', pack('V',  hexdec('U$1')))", json_encode($array));
            $data = str_replace('\\/', '/', $data);
        }

        echo $issetcallback ? $callback . '(' . $data . ')' : $data;
    }
    mysql_close($con);
} else { // If URL does not exist, return 404 error
    http_response_code(404);
    header('Content-Type: text/plain');
    exit('404 Not Found');
}
