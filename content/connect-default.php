<?php
session_start();
//$_SESSION['user']="";
// MySQL settings
// --------------------------------------------------
define('database', '');
define('hostname', 'localhost');
define('username', '');
define('password', '');
define('table', 'modulo');
define('connection', true);
define('error', false);
define('cdn', null);

// Path for static assets
$issetcdn   = cdn ? true : false;
$cdn_host   = parse_url(cdn, PHP_URL_HOST);
$cdn_scheme = parse_url(cdn, PHP_URL_SCHEME);
$cdn_scheme = isset($cdn_scheme) ? $cdn_scheme . '://' : '//';
$cdn_uri    = $cdn_scheme . $cdn_host;
$debug      = isset($debug) ? $debug : null;
$path       = isset($path) ? $path : null;
$assets     = $debug ? $path . 'assets/' : ($issetcdn ? cdn : $path . 'assets/');

$con = @mysql_connect(hostname, username, password); // Connect to db
$f   = 'content/connect.php';

if (@mysql_select_db(database, $con)) {
    if (!connection) { // Define MySQL connection status



        header("Location: {$_SERVER['REQUEST_URI']}");
        exit;
    }

    array_map('trim', $_GET);
    array_map('stripslashes', $_GET);
    array_map('mysql_real_escape_string', $_GET);

    $url   = isset($_GET['url']) ? $_GET['url'] : null;
    $urlid = isset($urlid) ? $urlid : null;

    if (!mysql_query('SELECT * FROM `' . table .'`')) {
        // Set the global server timezone to GMT, needs for SUPER privileges
        mysql_query("SET GLOBAL time_zone = '+00:00'");

        // MySQL backward compatibility
        $ver = preg_replace('#[^0-9\.]#', '', mysql_get_server_info());
        if (version_compare($ver, '5.5.3', '>=')) {
            $char = 'CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';
        } else {
            mysql_query('SET NAMES utf8');
            $char = 'CHARSET=utf8 COLLATE=utf8_unicode_ci';
        }

        // Create table

        // Create trigger (needs TRIGGER global privilege)

        // Insert data
    }
}
