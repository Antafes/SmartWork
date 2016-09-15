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
// This is only for fallback.
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
$GLOBALS['config']['dir_fs_system'] = __DIR__;

//mail
$GLOBALS['mail']['sender'] = 'test@test.org';

$GLOBALS['config']['unAllowedPages'] = array(
    'Header',
);

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
spl_autoload_register('\\SmartWork\\Utility\\General::classLoad');
