<?php

use YOOtheme\Application;

$autoloader = require __DIR__.'/../../autoload.php';

return new Application(compact('autoloader'));
