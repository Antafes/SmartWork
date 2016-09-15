<?php
declare(strict_types=1);
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
require_once(__DIR__.'/config.default.php');
require_once(__DIR__.'/util/mysql.php');

session_start();

$globalConfig = \SmartWork\GlobalConfig::getInstance();
$display = new \SmartWork\Display($globalConfig->getConfig('unAllowedPages'));

if (array_key_exists('page', $_GET))
{
    $page = $_GET['page'];
}

if (!isset($page) && empty($page))
{
    $page = 'Index';
}

if (array_key_exists('language', $_GET) && !empty($_GET['language']))
{
    $translator = \SmartWork\Translator::getInstance();
    $translator->setCurrentLanguage($_GET['language']);

    redirect('index.php?page='.$page);
}

$display->showPage($page);
