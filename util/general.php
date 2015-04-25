<?php
/**
 * Part of the SmartWork framework.
 *
 * @package SmartWork
 * @author  friend8 <map@wafriv.de>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */

/**
 * Redirect to the given location
 *
 * @param string $location
 *
 * @return void
 */
function redirect($location)
{
	header('Location: '.$location);
	die();
}

/**
 * Class loader
 *
 * @param string $name
 *
 * @return boolean
 */
function classLoad($name)
{
	$dir = __DIR__.'/../../Classes/';
	$pieces = explode('\\', $name);

	if ($pieces[0] === 'SmartWork')
	{
		$dir = __DIR__.'/../Classes/';
		array_shift($pieces);
	}

	$class = array_pop($pieces);

	$dir .= implode('/', $pieces) . '/';

	if(file_exists($dir . $class .'.php'))
	{
		require_once($dir . $class . '.php');
		return true;
	}
	elseif ($name == 'Smarty')
	{
		require_once(__DIR__.'/../smarty3/Smarty.class.php');
		return true;
	}
	elseif ($name == 'PHPMailer')
	{
		require_once(__DIR__.'/../phpmailer/class.phpmailer.php');
		return true;
	}

	return false;
}