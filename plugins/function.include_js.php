<?php
/**
 * Part of the SmartWork framework.
 * Smarty plugin
 *
 * @package    SmartWork
 * @subpackage plugins
 * @author     friend8 <map@wafriv.de>
 * @license    https://www.gnu.org/licenses/lgpl.html LGPLv3
 */

/**
 * Smarty {include_js} function plugin
 *
 * Type:     function<br>
 * Name:     include_js<br>
 * Date:     September 16, 2011<br>
 * Purpose:  load a given js-file<br>
 *
 * Examples:<br>
 * <pre>
 * {include_js}
 * </pre>
 * @author friend8 <map@wafriv.de>
 * @version  1.0
 * @param array $params
 * @param Smarty_Internal_Template $template
 */
function smarty_function_include_js($params, $template)
{
	$minified = '';
	if (!$GLOBALS['config']['debug'])
		$minified = '.min';

	if ($_SESSION['scripts']['file'])
	{
		foreach ($_SESSION['scripts']['file'] as $file)
		{
			$path = 'lib/js/'.$file.$minified.'.js';

			if (!file_exists($path))
			{
				$path = $GLOBALS['config']['dir_ws_system'].'/JavaScripts/'.$file.$minified.'.js';
			}

			echo '<script language="javascript" type="text/javascript" src="'.$path.'"></script>';
		}
	}

	if ($_SESSION['scripts']['script'] || $_SESSION['scripts']['ready_script'])
	{
		echo '<script language="javascript" type="text/javascript">';

		if ($_SESSION['scripts']['script'])
		{
			foreach ($_SESSION['scripts']['script'] as $script)
			{
				echo $script;
			}
		}

		if ($_SESSION['scripts']['ready_script'])
		{
			echo '$(function() {';

			foreach ($_SESSION['scripts']['ready_script'] as $script)
			{
				echo $script;
			}

			echo '});';
		}

		echo '</script>';
	}
}