<?php
/**
 * Part of the SmartWork framework.
 *
 * @package SmartWork
 * @author  friend8 <map@wafriv.de>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
?>
<form method="post" action="transformToMigrations.php">
	SQL query:<br />
	<textarea name="sql" rows="20" cols="80"></textarea><br />
	SQL file: <input type="text" name="sql_file" /><br />
	<input type="submit" />
</form>
<?php
if ($_POST['sql'] || $_POST['sql_file'])
{
	$sql_content = $_POST['sql'];

	if ($_POST['sql_file'] && file_exists(__DIR__.'/'.$_POST['sql_file']))
	{
		set_time_limit(240);
		$sql_content = '';

		while ($data = file_get_contents(__DIR__.'/'.$_POST['sql_file'], false, null, 0, 10000))
		{
			$sql_content .= $data;
		}
	}

	$sql_snippets = explode(';', $sql_content);

	echo '<textarea rows="20" cols="80">'."\n";
	foreach ($sql_snippets as $sql)
	{
		if (trim($sql))
		{
			$sql_expl = explode("\n", trim(str_replace('\'', '"', $sql)));
			$sqlString = '';
			foreach ($sql_expl as $part)
			{
				$part = trim($part);
				if ($part && substr($part, 0, 2) !== '--' && substr($part, 0, 2) !== '/*')
				{
					if (substr($part, 0, 4) !== 'DROP' && substr($part, 0 ,6) !== 'CREATE'
						&& substr($part, 0, 5) !== 'ALTER' && substr($part, 0, 1) !== ')')
					{
						$sqlString .= "\t";
					}

					$sqlString .= "\t\t".$part."\n";
				}
			}

			if (!$sqlString)
			{
				continue;
			}

			if (substr($sqlString, 0, 4) === 'DROP')
			{
				echo "\n\t\t".'query_raw(\''."\n".$sqlString."\t\t".'\');';
			}
			else
			{
				echo "\n\n\t\t".'$results[] = query_raw(\''."\n".$sqlString."\t\t".'\');';
			}
		}
	}
	echo '</textarea>';
}