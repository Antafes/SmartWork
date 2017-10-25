<?php
/**
 * This file is part of SmartWork.
 *
 * SmartWork is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * SmartWork is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with SmartWork.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   SmartWork
 * @author    Marian Pollzien <map@wafriv.de>
 * @copyright (c) 2015, Marian Pollzien
 * @license   https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
require_once(__DIR__.'/Classes/Utility/General.php');

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
$GLOBALS['config']['dir_fs_system'] = __DIR__;

//mail
$GLOBALS['mail']['sender'] = 'test@test.org';
$GLOBALS['mail']['admin'] = 'admin@test.org';

$GLOBALS['config']['unAllowedPages'] = array(
    'Header',
);

// Modules
// Order of the entries defines the load order of the modules
$GLOBALS['config']['useModules'] = true;
$GLOBALS['config']['modules'] = array(
    0 => 'Base',
    1 => 'Index',
    2 => 'UserSystem',
    3 => 'Imprint',
);

// Menu items
// To add new menu items, use \SmartWork\Utility\General::addMenuPage().
$GLOBALS['config']['menu'] = array(
    array(
        'page' => 'Login',
        'show' => 0,
        'default' => 0,
    ),
    array(
        'page' => 'Register',
        'show' => 0,
    ),
    array(
        'page' => 'Index',
        'show' => 1,
        'default' => 1,
    ),
    array(
        'page' => 'Admin',
        'show' => 2,
    ),
    9998 => array(
        'page' => 'Logout',
        'show' => 1,
    ),
    9999 => array(
        'page' => 'Imprint',
        'show' => -1,
    ),
);

// Imprint configuration
// This is an array of arrays with the following structure:
// array(
//     'name' => '',
//     'street' => '',
//     'number' => '',
//     'zip' => '',
//     'city' => '',
//     'email' => '',
// )
$GLOBALS['config']['imprint'] = array();

// load the default config of the page, if existing
if (file_exists(__DIR__.'/../config.default.php'))
{
    require_once(__DIR__.'/../config.default.php');
}

// load the specific config of the page
if (file_exists(__DIR__.'/config.php'))
{
    require_once(__DIR__.'/config.php');
}
elseif (file_exists(__DIR__.'/../config.php'))
{
    require_once(__DIR__.'/../config.php');
}

//autoloader
//spl_autoload_register('\\SmartWork\\Utility\\General::classLoad');
if (file_exists(__DIR__.'/vendor/autoload.php'))
{
    require_once __DIR__.'/vendor/autoload.php';
}
elseif (file_exists(__DIR__.'/../../autoload.php'))
{
    require_once __DIR__.'/../../autoload.php';
}
else
{
    throw new Error("Use composer install to fetch Smarty and every other dependency!", 1);
}
