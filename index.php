<?php
/**
 * Part of the SmartWork framework.
 *
 * @package SmartWork
 * @author  friend8 <map@wafriv.de>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
require_once(__DIR__.'/lib/config.default.php');
require_once(__DIR__.'/lib/util/mysql.php');

session_start();

$display = new \Display();

$page = $_GET['page'];

if (!$page)
	$page = 'Index';

if ($_GET['language'])
{
	$translator = \Translator::getInstance();
	$translator->setCurrentLanguage($_GET['language']);

	redirect('index.php?page='.$page);
}

if (!$_SESSION['userId'] && $page != 'Register' && $page != 'Imprint' && $page != 'LostPassword')
{
	$display->showPage('Login');
}
else
{
	$display->showPage($page);
}
