<?php
// session_cache_limiter('private');
// $cache_limiter = session_cache_limiter();
//
// session_cache_expire(15);
// $cache_expire = session_cache_expire();
session_start();
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time();
?>
