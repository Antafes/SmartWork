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
 * Name:     is_array<br>
 * Date:     November 9th, 2011<br>
 * Purpose:  check if the given variable is an array<br>
 * Input:
 *         - var = the variable to check
 *         - assign = assigns to template var
 *
 * Examples:<br>
 * <pre>
 * {is_array var=$variable assign="check_result"}
 * </pre>
 * @author friend8 <map@wafriv.de>
 * @version  1.0
 * @param array
 * @param Smarty
 */
function smarty_function_is_array(array $params, Smarty $smarty)
{
    if (!in_array('var', array_keys($params)))
    {
        $smarty->trigger_error('explode: missing "var" parameter');
    }

    if (!in_array('assign', array_keys($params)))
    {
        $smarty->trigger_error('explode: missing "assign" parameter');
    }

    $smarty->assign($params['assign'], is_array($params['var']));
}