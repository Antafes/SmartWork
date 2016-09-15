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
 * Smarty {explode} function plugin
 *
 * Type:     function<br>
 * Name:     in_array<br>
 * Date:     November 9th, 2011<br>
 * Purpose:  check if the given value is in the array<br>
 * Input:
 *         - value = the value to search for
 *         - var = the array to check
 *         - assign = assigns to template var
 *
 * Examples:<br>
 * <pre>
 * {is_array var=$variable value="blub" assign="check_result"}
 * </pre>
 * @author friend8 <map@wafriv.de>
 * @version  1.0
 * @param array $params
 * @param Smarty_Internal_Template $template
 */
function smarty_function_in_array(array $params, Smarty_Internal_Template $template)
{
    if (!in_array('var', array_keys($params)))
    {
        $template->smarty->trigger_error('explode: missing "var" parameter');
    }

    if (!in_array('value', array_keys($params)))
    {
        $template->smarty->trigger_error('explode: missing "value" parameter');
    }

    if (!in_array('assign', array_keys($params)))
    {
        $template->smarty->trigger_error('explode: missing "assign" parameter');
    }

    if (!$params['var'])
    {
        $template->assign($params['assign'], false);
    }
    else
    {
        $template->assign($params['assign'], in_array($params['value'], $params['var']));
    }
}