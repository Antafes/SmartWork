<?php
/**
 * Part of the SmartWork framework.
 *
 * @package SmartWork
 * @author  friend8 <map@wafriv.de>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
require_once(__DIR__.'/../config.default.php');
require_once(__DIR__.'/../util/mysql.php');
require_once(__DIR__.'/../util/general.php');

$result = migration_manager($_REQUEST);

echo $result;