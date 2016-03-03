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
 * @package   SmartWork
 * @author    Marian Pollzien <map@wafriv.de>
 * @copyright (c) 2015, Marian Pollzien
 * @license   https://www.gnu.org/licenses/lgpl.html LGPLv3
 */

/**
 * handles the MySQL queries
 * if the query is a select, it returns an array if there is only one value, otherwise it returns the value
 * if the query is an update, replace or delete from, it returns the number of affected rows
 * if the query is an insert, it returns the last insert id
 * @author friend8
 * @param string $sql
 * @param bool $noTransform (default = false) if set to "true" the query function always returns a multidimension array
 *
 * @return array|string|int|float
 */
function query($sql, $noTransform = false, $raw = false)
{
	global $debug, $firePHP_debug, $smarty_debug;

	$mysql = connect();

	$sql = ltrim($sql);
	if ($debug == true)
	{
		$res = $mysql->query($sql);
	}
	else
	{
		$res = @$mysql->query($sql);
	}

	if (!$res && $debug)
	{
		$backtrace = debug_backtrace();
		$html = '<br />Datenbank Fehler '.$mysql->error.'<br /><br />';
		$html .= $sql.'<br />';
		$html .= '<table>';
		foreach ($backtrace as $part)
		{
			$html .= '<tr><td width="100">';
			$html .= 'File: </td><td>'.$part['file'];
			$html .= ' in line '.$part['line'];
			$html .= '</td></tr><tr><td>';
			$html .= 'Function: </td><td>'.$part['function'];
			$html .= '</td></tr><tr><td>';
			$html .= 'Arguments: </td><td>';
			foreach ($part['args'] as $args)
				$html .= $args.', ';
			$html = \substr($html, 0, -2);
			$html .= '</td></tr>';
		}
		$html .= '</table>';
		die($html);
	}

	if ($res || is_object($res))
	{
		if (substr($sql,0,6) == "SELECT" || substr($sql, 0, 4) == 'SHOW')
		{
			$out = array();

			if ($res->num_rows > 1 || ($noTransform && $res->num_rows > 0))
			{
				if (method_exists('mysqli_result', 'fetch_all'))
					$out = $res->fetch_all(MYSQLI_ASSOC);
				else
					while ($row = $res->fetch_assoc())
						$out[] = $row;
			}
			elseif ($res->num_rows == 1 && !$noTransform)
			{
				$out = $res->fetch_assoc();

				if (count($out) == 1)
					$out = current($out);
			}
			else
				$out = false;

			return $out;
		}

		if (substr($sql,0,6) == "INSERT" && $noTransform == false)
		    return $mysql->insert_id;
		elseif (substr($sql,0,6) == "INSERT" && $noTransform == true)
			return $mysql->affected_rows;

		if (substr($sql,0,6) == "UPDATE")
			return $mysql->affected_rows;

		if (substr($sql,0,7) == "REPLACE")
			return $mysql->affected_rows;

		if (substr($sql,0,11) == "DELETE FROM")
			return $mysql->affected_rows;

		if ($raw)
			return $res;
	}
	else
		return false;
}

/**
 * See query()
 *
 * @param string $sql
 *
 * @return mixed
 */
function query_raw($sql)
{
	return query($sql, false, true);
}

/**
 * Let the Transaction begin
 *
 * @author BlackIce
 *
 * @return void
 */
function transactionBegin()
{
    query("BEGIN");
}

/**
 * Save Changes on Database
 *
 * @author BlackIce
 *
 * @return void
 */
function transactionCommit()
{
    query("COMMIT");
}

/**
 * Rollback Changes
 *
 * @author BlackIce
 *
 * @return void
 */
function transactionRollback()
{
    query("ROLLBACK");
}

/**
 * Escapes and wraps the given value. If it's an array, all elements will be
 * escaped separately.
 *
 * @param mixed $value
 *
 * @return String
 */
function sqlval($value, $wrap = true)
{
	$mysql = connect();

	if (is_array($value))
	{
		foreach ($value as &$row)
			$row = sqlval($row, $wrap);
		unset($row);

		return $value;
	}
	else
	{
		$escapedString = '';

		if ($wrap)
			$escapedString .= '"';

		$escapedString .= $mysql->real_escape_string($value);

		if ($wrap)
			$escapedString .= '"';

		return $escapedString;
	}
}

/**
 * Handles the MySQL connection.
 * Should only be used in sqlval() and query()
 *
 * @author friend8
 *
 * @global array $lang
 * @staticvar mysqli $mysql
 *
 * @return mysqli
 */
function connect()
{
	global $lang;
	static $mysql;

	if (!is_object($mysql))
	{
        $globalConfig = \SmartWork\GlobalConfig::getInstance();
        $dbConfig = $globalConfig->getGlobal('db');
		$mysql = new mysqli($dbConfig['server'], $dbConfig['user'], $dbConfig['password']);

		if ($mysql->connect_error)
		{
			echo 'No database found. Please contact <br /><a href=\"mailto:admin@dynasty-wars.de\">admin@wafriv.de</a>';
			exit;
		}
		else
		{
			$mysql->set_charset($dbConfig['charset']);
			$mysql->select_db($dbConfig['db']);
		}
	}

	$timezone = $mysql->query('SELECT @@session.time_zone');

	if ($timezone == 'SYSTEM')
    {
		$mysql->query('SET time_zone = "+00:00"');
    }

	return $mysql;
}

/**
 * manager for the migrations
 *
 * @author friend8
 *
 * @param array $post
 *
 * @return string
 */
function migration_manager($post)
{
    $globalConfig = \SmartWork\GlobalConfig::getInstance();
	$migration_files_dir = $globalConfig->getConfig('migrations_dir');
	$web_path = $globalConfig->getConfig('dir_ws_migrations');

	$migration_files = array();
	$dh = opendir($migration_files_dir);
	if (!$dh)
    {
		die('Migration files directory not found.');
    }

	while (($filename = readdir($dh)) !== false)
    {
		if (substr($filename, -4) == '.php')
        {
			$migration_files[] = $filename;
        }
    }

	closedir($dh);
	natsort($migration_files);

	if ($post['initialize'])
	{
		initialize_migration();
		redirect($web_path.'/migrations.php');
	}

	if ($post['create_new'])
	{
		$filename = date('Y-m-d_His');

		if ($post['name'])
		{
			$filename .= '-'.$post['name'];
		}

		file_put_contents($migration_files_dir.'/'.$filename.'.php', "<?php\n\n\$DB_MIGRATION = array(\n\n\t'description' => function () "
			."{\n\t\treturn '';\n\t},\n\n\t'up' => function (\$migration_metadata) {\n\n\t\t\$results = array();\n\n\t\t\$results[] = "
			."query_raw('\n\t\t\t\n\t\t');\n\n\t\treturn !in_array(false, \$results);\n\n\t},\n\n\t'down' => function "
			."(\$migration_metadata) {\n\n\t\t\$result = query_raw('\n\t\t\tALTER TABLE tbl CHANGE col col_to_delete TEXT\n\t\t');"
			."\n\n\t\treturn !!\$result;\n\n\t}\n\n);");
		redirect($web_path.'/migrations.php');
	}

	if ($post['apply'] || $post['unapply'])
	{
		foreach ($migration_files as $filename)
		{
			$eligible = false;

			if ($filename == $post['filename'])
			{
				$eligible = true;
			}

			if ($post['next'])
			{
				if (!is_migration_applied($filename))
				{
					$eligible = true;
				}
			}

			if ($eligible)
			{
				require_once($migration_files_dir.'/'.$filename);

				set_time_limit(0);

				if ($post['unapply'])
				{
					$result = $DB_MIGRATION['down'](array());
				}
				else
				{
					$result = $DB_MIGRATION['up'](array());
				}

				if ($result === true)
				{
					if ($post['unapply'])
					{
						mark_migration_unapplied($filename);
						redirect($web_path.'/migrations.php');
					}
					else
					{
						mark_migration_applied($filename);
						redirect($web_path.'/migrations.php');
					}
				}
				else
				{
					$message = '<div style="font-family: sans-serif; font-size: 13px;">There has been a problem '
						.($post['unapply'] ? 'unapplying' : 'applying').' '.htmlentities($filename).'<br></div>';
					$message .= $result;
					$message .= '<a href="'.$web_path.'/migrations.php">Back</a>';
					return $message;
				}
			}
		}
	}

	$all_applied = true;
	if (is_migrations_initialized())
	{
		$message = '
			<script language="javascript" type="text/javascript" src="../dw/lib/js/jquery-1.9.1.min.js"></script>
			<script language="javascript" type="text/javascript">
				$(function() {
					$("#addNew").click(function(e) {
						e.preventDefault();
						var name = prompt("Name of the file (optional)");
						var target = $(this).attr("href") + "&name=" + name;
						window.location.href = target;
					});
				});
			</script>
			<style type="text/css">
				table.df_migration_manager { font-family: sans-serif; font-size: 13px; border-spacing: 0px 3px; }
				table.df_migration_manager td { background-color: #EEEEEE; padding: 0px 4px; }
				table.df_migration_manager th { padding: 2px 4px; }
				table.df_migration_manager th { text-align: left; }
				table.df_migration_manager td.df_migration_manager_applied_col { text-align: center; }
				table.df_migration_manager td[colspan] { background-color: inherit; }
			</style>
		';
		$message .= '<table class="df_migration_manager">';
		$message .= '<tr><th>Filename</th><th>Description</th><th>Applied?</th><th></th><th></th></tr>';

		foreach ($migration_files as $filename)
		{
			require_once($migration_files_dir . '/' . $filename);

			$description = $DB_MIGRATION['description']();

			$applied = is_migration_applied($filename);
			if (!$applied)
				$all_applied = false;

			$message .= '
				<tr class="'.($applied ? 'df_migration_manager_applied' : '').'">
					<td class="df_migration_manager_filename_col">'.htmlentities($filename).'</td>
					<td class="df_migration_manager_description_col">'.htmlentities($description).'</td>
					<td class="df_migration_manager_applied_col">'.($applied ? '&#x2714;' : ' ').'</td>
					<td class="df_migration_manager_apply_col">'.(!$applied ? '<a href="'.$web_path.'/migrations.php?apply=1&amp;filename='
						.urlencode($filename).'">' : '').'Apply'.(!$applied ? '</a>' : '').'</td>
					<td class="df_migration_manager_unapply_col">'.($applied ? '<a href="'.$web_path.'/migrations.php?unapply=1&amp;filename='.
						urlencode($filename).'">' : '').'Unapply'.($applied ? '</a>' : '').'</td>
				</tr>
			';
		}

		$message .= '
			<tr><td colspan="5">
				'.(!$all_applied ? '<a href="'.$web_path.'/migrations.php?next=1&amp;apply=1">' : '').'Apply next'.(!$all_applied ? '</a>' : '').'
				'.(is_writable($migration_files_dir) ? '<a id="addNew" href="'.$web_path.'/migrations.php?create_new=1">' : '').'Create new'.
					(is_writable($migration_files_dir) ? '</a>' : '').'
			</td></tr>
		';
		$message .= '</table>';

		return $message;
	}
	else
		initialize_migration();
}

/**
 * check if the migrations have been initialized
 *
 * @author friend8
 *
 * @return boolean
 */
function is_migrations_initialized()
{
	$sql = 'SHOW TABLES LIKE "db_migrations"';
	return !!query($sql);
}

/**
 * initialize the migrations
 *
 * @author friend8
 *
 * @return void
 */
function initialize_migration()
{
	$sql = '
		CREATE TABLE `db_migrations` (
			`filename` VARCHAR(255) NOT NULL COLLATE "utf8_general_ci",
			`status` ENUM("unapplied","applied") NOT NULL COLLATE "utf8_general_ci",
			PRIMARY KEY (`filename`)
		)
		COLLATE="utf8_general_ci"
	';
	query($sql);
}

/**
 * check if the migration has been applied
 *
 * @author friend8
 *
 * @param string $filename
 *
 * @return boolean
 */
function is_migration_applied($filename)
{
	$sql = '
		SELECT IF (`status` = "applied", 1, 0)
		FROM `db_migrations`
		WHERE `filename` = '.sqlval($filename).'
	';
	return !!query($sql);
}

/**
 * mark the migration as applied
 *
 * @author friend8
 *
 * @param string $filename
 *
 * @return void
 */
function mark_migration_applied($filename)
{
	$sql = '
		INSERT INTO `db_migrations`
		SET `filename` = '.sqlval($filename).',
			`status` = "applied"
		ON DUPLICATE KEY UPDATE `status` = VALUES(status)
	';
	query($sql);
}

/**
 * mark the migration as unapplied
 *
 * @author friend8
 *
 * @param string $filename
 *
 * @return void
 */
function mark_migration_unapplied($filename)
{
	$sql = '
		UPDATE `db_migrations`
		SET `status` = "unapplied"
		WHERE `filename` = '.sqlval($filename).'
	';
	query($sql);
}