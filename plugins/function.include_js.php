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
    $globalConfig = \SmartWork\GlobalConfig::getInstance();
    $minified = '';

    if (!$globalConfig->getConfig('debug'))
    {
        $minified = '.min';
    }

    if ($_SESSION['scripts']['file'])
    {
        foreach ($_SESSION['scripts']['file'] as $file)
        {
            $path = 'lib/js/'.$file.$minified.'.js';

            if (!file_exists($path))
            {
                $path = $globalConfig->getConfig('dir_ws_system').'/JavaScripts/'.$file.$minified.'.js';
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