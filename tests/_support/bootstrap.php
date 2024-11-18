<?php

// Include autoload file

use Monarch\DotEnv;

define('DEBUG', true);

define('START_TIME', microtime(true));
define('ENVIRONMENT', 'test');
define('ROOTPATH', realpath(__DIR__ .'/../../') .'/');
define('TESTPATH', realpath(ROOTPATH.'tests') .'/');
define('APPPATH', realpath(TESTPATH.'_support') .'/');
define('MONARCHPATH', realpath(ROOTPATH.'src') .'/');
define('WRITEPATH', realpath(ROOTPATH.'writable') .'/');

// Load .env file
(new DotEnv(ROOTPATH .'/.env'))->load();

require_once __DIR__ . '/../../vendor/autoload.php';

// Include common functions
require_once MONARCHPATH . 'Helpers/common.php';
