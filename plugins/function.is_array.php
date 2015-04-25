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
 * Smarty {explode} function plugin
 *
 * Type:     function<br>
 * Name:     is_array<br>
 * Date:     November 9th, 2011<br>
 * Purpose:  check if the given variable is an array<br>
 * Input:
 *         - var = the variable to check
 *		   - assign = assigns to template var
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
function smarty_function_is_array($params, $smarty)
{
	if (!in_array('var', array_keys($params)))
		$smarty->trigger_error('explode: missing "var" parameter');

	if (!in_array('assign', array_keys($params)))
		$smarty->trigger_error('explode: missing "assign" parameter');

	$smarty->assign($params['assign'], is_array($params['var']));
}