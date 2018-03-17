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
 * Smarty {add_form_salt} function plugin
 *
 * Type:     function<br>
 * Name:     add_form_salt<br>
 * Date:     September 16, 2011<br>
 * Purpose:  load a given js-file<br>
 *
 * Examples:<br>
 * <pre>
 * {add_form_salt}
 * {add_form_salt prefix="test"}
 * </pre>
 * @author friend8 <map@wafriv.de>
 * @version  1.0
 * @param array $params
 * @param Smarty_Internal_Template $smarty
 */
function smarty_function_add_form_salt($params, $smarty)
{
    if (!in_array('formName', array_keys($params)))
    {
        trigger_error('explode: missing "formName" parameter');
    }

    $prefix = '';

    if ($params['prefix'])
    {
        $prefix = $params['prefix'].'_';
    }

    $id = uniqid($prefix);
    $_SESSION['formSalts'][$params['formName']] = $id;
    echo '<input type="hidden" name="'.$params['formName'].'" value="'.$id.'" />';
}