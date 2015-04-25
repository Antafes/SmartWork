<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
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
 * @author Neithan
 * @version  1.0
 * @param array $params
 * @param Smarty_Internal_Template $template
 */
function smarty_function_add_form_salt($params, $smarty)
{
	if (!in_array('formName', array_keys($params)))
		$smarty->trigger_error('explode: missing "formName" parameter');

	$prefix = '';

	if ($params['prefix'])
		$prefix = $params['prefix'].'_';

	$id = uniqid($prefix);
	$_SESSION['formSalts'][$params['formName']] = $id;
	echo '<input type="hidden" name="'.$params['formName'].'" value="'.$id.'" />';
}