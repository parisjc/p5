<?php

use Lib\Application;
use Lib\Autoload;

require_once '../vendor/autoload.php';
require_once '../src/Lib/Autoload.php';

Autoload::init();
new Application();