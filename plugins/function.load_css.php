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
 * Smarty {load_css} function plugin
 *
 * Type:     function<br>
 * Name:     load_css<br>
 * Date:     September 16, 2011<br>
 * Purpose:  load a given js-file<br>
 * Input:
 *         - file = file to load
 *         - script = script to load
 *
 * Examples:<br>
 * <pre>
 * {load_css file="jquery-1.5.1"}
 * {load_css script="$('#test').submit();"}
 * </pre>
 * @author friend8 <map@wafriv.de>
 * @version  1.0
 * @param array $params
 * @param Smarty_Internal_Template $template
 */
function smarty_function_load_css(array $params, Smarty_Internal_Template $template)
{
    if (!in_array('file', array_keys($params)) && !in_array('script', array_keys($params)))
    {
        $template->smarty->trigger_error('load_js: missing "file" or "script" parameter');
    }

    if (in_array('file', array_keys($params)) && in_array('script', array_keys($params)))
    {
            $template->smarty->trigger_error('load_js: only "file" or "script" parameter allowed');
    }

    if (!is_array($_SESSION['css']['file']))
    {
        $_SESSION['css']['file'] = array();
    }

    if (!is_array($_SESSION['css']['script']))
    {
        $_SESSION['css']['script'] = array();
    }

    if ($params['file'] && !in_array($params['file'], $_SESSION['css']['file']))
    {
        $_SESSION['css']['file'][] = $params['file'];
    }
    elseif ($params['script'])
    {
        $_SESSION['css']['script'][] = $params['scipt'];
    }
}