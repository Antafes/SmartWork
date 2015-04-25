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
 * Name:     include_css<br>
 * Date:     September 16, 2011<br>
 * Purpose:  load a given js-file<br>
 *
 * Examples:<br>
 * <pre>
 * {include_css}
 * </pre>
 * @author friend8 <map@wafriv.de>
 * @version  1.0
 * @param array $params
 * @param Smarty_Internal_Template $template
 */
function smarty_function_include_css($params, $template)
{
	if ($_SESSION['css']['file'])
	{
		foreach ($_SESSION['css']['file'] as $file)
		{
			$path = 'css/' . $file . '.css';

			if (!file_exists($path))
			{
				$path = $GLOBALS['config']['dir_ws_system'].'/css/' . $file . '.css';
			}

			echo '<link rel="stylesheet" type="text/css" href="'.$path.'" />';
		}
	}

	if ($_SESSION['css']['script'])
	{
		foreach ($_SESSION['css']['script'] as $script)
		{
			echo '<style type="text/css">';
			echo $script;
			echo '</style>';
		}
	}
}