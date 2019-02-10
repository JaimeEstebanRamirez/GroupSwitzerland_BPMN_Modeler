<?php

// Base URL of the application
$baseURL = (isset($_SERVER["HTTPS"]) ? "https://" : "http://").$_SERVER["HTTP_HOST"];
$baseURL .= str_replace(basename($_SERVER["SCRIPT_NAME"]), "", $_SERVER["SCRIPT_NAME"]);
define('BASE_URL', $baseURL);

define('CSS_URL', $baseURL.'assets/css/');
define('JS_URL', $baseURL.'assets/js/');
define('IMG_URL', $baseURL.'assets/images/');
define('BST_URL', $baseURL.'assets/bootstrap/');
define('UPLOAD_URL', $baseURL.'uploads/');

define('SITE_NAME', 'GroupSwitzerland');
define('PER_PAGE_LIMIT', 5);

// Email configuration
define('SENDER_NAME', 'GroupSwitzerland');
define('SENDER_EMAIL', 'noreply.groupswitzerland@gmail.com');

define('SMTP', TRUE);
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USERNAME', 'noreply.groupswitzerland@gmail.com');
define('SMTP_PASSWORD', '@Italy2018');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');