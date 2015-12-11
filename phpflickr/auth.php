<?php
/* Last updated with phpFlickr 1.3.1
 *
 * Edit these variables to reflect the values you need. $default_redirect
 * and $permissions are only important if you are linking here instead of
 * using phpFlickr::auth() from another page or if you set the remember_uri
 * argument to false.
 */

session_start();

$api_key          = $_SESSION['api_key'];
$api_secret       = $_SESSION['secret'];
$default_redirect = dirname($_SERVER['PHP_SELF']);
$permissions      = $_SESSION['perms'];

ob_start();
require_once(dirname(__FILE__) . "/phpFlickr.php");
unset($_SESSION['phpFlickr_auth_token']);

if (!empty($_GET['extra'])) {
$redirect = $_GET['extra'];
}

$f = new phpFlickr($api_key, $api_secret);

if (empty($_GET['frob'])) {
    $f->auth($permissions, false);
} else {
    $f->auth_getToken($_GET['frob']);
}

if (empty($redirect)) {
header("Location: " . $default_redirect);
} else {
header("Location: " . $redirect);
}

?>