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
 * json_encode modifier plugin
 *
 * Type:     modifier<br>
 * Name:     json_encode<br>
 * Purpose:  encode an array or an object as a json string
 *
 * @author Neithan
 * @param string $format format string
 * @return string formatted string
 */
function smarty_modifier_json_encode($string, $options = 0, $depth = 512)
{
	return json_encode($string, $options, $depth);
}