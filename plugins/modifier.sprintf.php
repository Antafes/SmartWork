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
 * sprintf modifier plugin
 *
 * Type:     modifier<br>
 * Name:     sprintf<br>
 * Purpose:  format strings via sprintf
 *
 * @author friend8 <map@wafriv.de>
 * @param string $format format string
 * @return string formatted string
 */
function smarty_modifier_sprintf()
{
	$args = func_get_args();
	$format = $args[0];
	unset($args[0]);
	$params = $args;
	return vsprintf($format, $params);
}