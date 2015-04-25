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
 * Name:     number_format<br>
 * Purpose:  format numbers with number_format
 *
 * @author Neithan
 * @param number $number input number
 * @param int $decimals number of decimals
 * @param string $decimal decimal point
 * @param string $thousands thousands separator
 * @return string formatted string
 */
function smarty_modifier_number_format($number, $decimals = 2, $decimal = '.', $thousands = ',')
{
	return number_format($number, $decimals, $decimal, $thousands);
}