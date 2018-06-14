<?php
declare(strict_types=1);

/*
 +------------------------------------------------------------------------+
 | Phosphorum                                                             |
 +------------------------------------------------------------------------+
 | Copyright (c) 2013-present Phalcon Team and contributors               |
 +------------------------------------------------------------------------+
 | This source file is subject to the New BSD License that is bundled     |
 | with this package in the file LICENSE.txt.                             |
 |                                                                        |
 | If you did not receive a copy of the license and are unable to         |
 | obtain it through the world-wide-web, please send an email             |
 | to license@phalconphp.com so we can send you a copy immediately.       |
 +------------------------------------------------------------------------+
*/

defined('APP_START_TIME')   || define('APP_START_TIME', microtime(true));
defined('APP_START_MEMORY') || define('APP_START_MEMORY', memory_get_usage());

require __DIR__.'/../vendor/autoload.php';

$application = (new Phosphorum\Core\Bootstrap(realpath(__DIR__.'/../')))->makeMvcApplication();

$response = $application->handle();
// TODO: Enable debug component
echo $response->getContent();
