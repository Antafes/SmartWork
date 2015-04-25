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
 * Smarty string_format modifier plugin
 *
 * Type:     modifier<br>
 * Name:     rawurlencode<br>
 * Purpose:  encode a string with rawurlencode
 *
 * @author Neithan
 * @param number $string input number
 * @return string formatted string
 */
function smarty_modifier_rawurlencode($string)
{
	return rawurlencode($string);
}