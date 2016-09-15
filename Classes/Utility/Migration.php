<?php
/**
 * This file is part of SmartWork.
 *
 * Image Upload is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Image Upload is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with SmartWork. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   SmartWork
 * @author    Marian Pollzien <map@wafriv.de>
 * @copyright (c) 2016, Marian Pollzien
 * @license   https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
namespace SmartWork\Utility;

/**
 * Description of Migration
 *
 * @author Marian Pollzien <map@wafriv.de>
 */
class Migration
{
    /**
     * manager for the migrations
     *
     * @author friend8
     *
     * @param array $post
     *
     * @return string
     */
    public function manager($post)
    {
        $globalConfig = \SmartWork\GlobalConfig::getInstance();
        $migration_files_dir = $globalConfig->getConfig('migrations_dir');
        $web_path = $globalConfig->getConfig('dir_ws_migrations');

        $migration_files = array();
        $dh = \opendir($migration_files_dir);
        if (!$dh)
        {
            die('Migration files directory not found.');
        }

        while (($filename = \readdir($dh)) !== false)
        {
            if (substr($filename, -4) == '.php')
            {
                $migration_files[] = $filename;
            }
        }

        \closedir($dh);
        \natsort($migration_files);

        if ($post['initialize'])
        {
            $this->initialize_migration();
            redirect($web_path.'/migrations.php');
        }

        if ($post['create_new'])
        {
            $filename = \date('Y-m-d_His');

            if ($post['name'])
            {
                $filename .= '-'.$post['name'];
            }

            \file_put_contents($migration_files_dir.'/'.$filename.'.php', "<?php\n\n\$DB_MIGRATION = array(\n\n\t'description' => function () "
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
                    if (!$this->is_migration_applied($filename))
                    {
                        $eligible = true;
                    }
                }

                if ($eligible)
                {
                    require_once($migration_files_dir.'/'.$filename);

                    \set_time_limit(0);

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
                            $this->mark_migration_unapplied($filename);
                            redirect($web_path.'/migrations.php');
                        }
                        else
                        {
                            $this->mark_migration_applied($filename);
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
        if ($this->is_migrations_initialized())
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

                $applied = $this->is_migration_applied($filename);
                if (!$applied)
                    $all_applied = false;

                $message .= '
                    <tr class="' . ($applied ? 'df_migration_manager_applied' : '') . '">
                        <td class="df_migration_manager_filename_col">' . \htmlentities($filename) . '</td>
                        <td class="df_migration_manager_description_col">' . \htmlentities($description) . '</td>
                        <td class="df_migration_manager_applied_col">' . ($applied ? '&#x2714;' : ' ') . '</td>
                        <td class="df_migration_manager_apply_col">' . (!$applied ? '<a href="' . $web_path . '/migrations.php?apply=1&amp;filename='
                            . urlencode($filename) . '">' : '') . 'Apply' . (!$applied ? '</a>' : '') . '</td>
                        <td class="df_migration_manager_unapply_col">' . ($applied ? '<a href="' . $web_path . '/migrations.php?unapply=1&amp;filename='
                            . urlencode($filename) . '">' : '') . 'Unapply' . ($applied ? '</a>' : '') . '</td>
                    </tr>
                ';
            }

            $message .= '
                <tr><td colspan="5">
                    ' . (!$all_applied ? '<a href="' . $web_path . '/migrations.php?next=1&amp;apply=1">' : '') . 'Apply next' . (!$all_applied ? '</a>' : '') . '
                    ' . (\is_writable($migration_files_dir) ? '<a id="addNew" href="' . $web_path . '/migrations.php?create_new=1">' : '') . 'Create new'
                        . (\is_writable($migration_files_dir) ? '</a>' : '') . '
                </td></tr>
            ';
            $message .= '</table>';

            return $message;
        }
        else
        {
            $this->initialize_migration();
        }
    }

    /**
     * check if the migrations have been initialized
     *
     * @author friend8
     *
     * @return boolean
     */
    protected function is_migrations_initialized()
    {
        $sql = 'SHOW TABLES LIKE "db_migrations"';
        return !!Database::query($sql);
    }

    /**
     * initialize the migrations
     *
     * @author friend8
     *
     * @return void
     */
    protected function initialize_migration()
    {
        $sql = '
            CREATE TABLE `db_migrations` (
                `filename` VARCHAR(255) NOT NULL COLLATE "utf8_general_ci",
                `status` ENUM("unapplied","applied") NOT NULL COLLATE "utf8_general_ci",
                PRIMARY KEY (`filename`)
            )
            COLLATE="utf8_general_ci"
        ';
        Database::query($sql);
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
    protected function is_migration_applied($filename)
    {
        $sql = '
            SELECT IF (`status` = "applied", 1, 0)
            FROM `db_migrations`
            WHERE `filename` = ' . Database::sqlval($filename) . '
        ';
        return !!Database::query($sql);
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
    protected function mark_migration_applied($filename)
    {
        $sql = '
            INSERT INTO `db_migrations`
            SET `filename` = ' . Database::sqlval($filename) . ',
                `status` = "applied"
            ON DUPLICATE KEY UPDATE `status` = VALUES(status)
        ';
        Database::query($sql);
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
    protected function mark_migration_unapplied($filename)
    {
        $sql = '
            UPDATE `db_migrations`
            SET `status` = "unapplied"
            WHERE `filename` = ' . Database::sqlval($filename) . '
        ';
        Database::query($sql);
    }
}
