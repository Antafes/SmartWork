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
 * @package    SmartWork
 * @subpackage plugins
 * @author     Marian Pollzien <map@wafriv.de>
 * @copyright  (c) 2015, Marian Pollzien
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
				$path = $GLOBALS['config']['dir_ws_system'].'/Css/' . $file . '.css';
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