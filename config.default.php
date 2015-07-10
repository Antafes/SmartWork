<?php
/**
 * Part of the SmartWork framework.
 *
 * @package SmartWork
 * @author  friend8 <map@wafriv.de>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
require_once(__DIR__.'/util/general.php');

//access data for the database
$GLOBALS['db'] = array(
	'server' => 'localhost',
	'user' => '',
	'password' => '',
	'db' => 'magic',
	'charset' => 'utf8',
);

$GLOBALS['config']['charset'] = 'UTF-8';

//enable/disable debug
$GLOBALS['config']['debug'] = false;
$GLOBALS['config']['debugSmarty'] = false;

//paths
$GLOBALS['config']['dir_ws'] = 'http://localhost';
$GLOBALS['config']['dir_ws_index'] = 'http://localhost/index.php';
$GLOBALS['config']['dir_ws_system'] = '';

$GLOBALS['config']['migrations_dir'] = '';
$GLOBALS['config']['dir_ws_migrations'] = '';

//mail
$GLOBALS['mail']['sender'] = 'test@test.org';

$GLOBALS['config']['unAllowedPages'] = array(
	'Header',
);

//autoloader
spl_autoload_register('classLoad');

if (file_exists(__DIR__.'/config.php'))
{
	require_once(__DIR__.'/config.php');
}
elseif (file_exists(__DIR__.'/../config.php'))
{
	require_once(__DIR__.'/../config.php');
}
