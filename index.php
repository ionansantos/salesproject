<?php

require_once(__DIR__ . '/vendor/autoload.php');

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

date_default_timezone_set(getenv('TIMEZONE', 'America/Bahia'));



use System\Route\GetRoute;
use System\Route\SelectController;

# Load controllers
$route = new SelectController(new GetRoute);

# Load routes
require_once(__DIR__ . '/routes/routes.php');



// System\Session\Session::start();

// System\Session\Token::verify();

// if(!file_exists(__DIR__ . '/.env') ){
//     require_once(__DIR__ . 'System/Bootstrap/install.php');
//     exit;
// }