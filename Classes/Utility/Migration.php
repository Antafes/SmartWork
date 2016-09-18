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
 * Utility class for handling the database migrations.
 *
 * @package    SmartWork
 * @subpackage Utility
 * @author     Marian Pollzien <map@wafriv.de>
 */
class Migration
{
    /**
     * The global configuration handler.
     *
     * @var \SmartWork\GlobalConfig
     */
    protected $globalConfig;

    /**
     * manager for the migrations
     *
     * @param array $post
     *
     * @return string
     */
    public function manager(array $post): string
    {
        $this->globalConfig = \SmartWork\GlobalConfig::getInstance();
        $globalMigrationsDir = $this->getGlobalMigrationsDirectory();
        $web_path = $this->globalConfig->getConfig('dir_ws_migrations');
        $moduleMigrationDirs = $this->getModuleMigrationDirectories();

        $migrationFiles = array();
        if (!file_exists($globalMigrationsDir) && empty($moduleMigrationDirs))
        {
            die('Migration files directory not found.');
        }

        $this->checkForOldEntries();

        foreach ($moduleMigrationDirs as $module)
        {
            $directoryHandle = \opendir($module['path']);

            while (($filename = \readdir($directoryHandle)) !== false)
            {
                if (substr($filename, -4) == '.php')
                {
                    $migrationFiles[] = array(
                        'file' => $filename,
                        'path' => $module['path'] . '/' . $filename,
                        'module' => $module['module'],
                    );
                }
            }

            closedir($directoryHandle);
        }

        $dh = \opendir($globalMigrationsDir);

        while (($filename = \readdir($dh)) !== false)
        {
            if (substr($filename, -4) == '.php')
            {
                $migrationFiles[] = array(
                    'file' => $filename,
                    'path' => $globalMigrationsDir . $filename,
                );
            }
        }

        \closedir($dh);

        if ($post['initialize'])
        {
            $this->initializeMigration();
            General::redirect($web_path . '/migrations.php');
        }

        if ($post['create_new'])
        {
            $this->createNew($post);
        }

        if ($post['apply'] || $post['unapply'])
        {
            return $this->setApplyStatus($post, $migrationFiles);
        }

        if ($this->isMigrationsInitialized())
        {
            return $this->showList($migrationFiles);
        }
        else
        {
            $this->initializeMigration();
        }
    }

    /**
     * Checks the modules for database migrations and returns an array with the paths.
     *
     * @return array
     */
    protected function getModuleMigrationDirectories(): array
    {
        if (!$this->globalConfig->getConfig('useModules'))
        {
            return array();
        }

        $paths = array();
        $modules = $this->globalConfig->getConfig('modules');
        $systemPath = $this->replaceBackslash($this->globalConfig->getConfig('dir_fs_system'));

        // Load SmartWork Modules
        foreach ($modules as $module)
        {
            $path = $systemPath . '/Modules/' . $module . '/DbMigrations';

            if (file_exists($path))
            {
                $paths[] = array(
                    'path' => $path,
                    'module' => 'SmartWork/' . $module,
                );
            }
        }

        // Load the pages Modules
        foreach ($modules as $module)
        {
            $path = $systemPath . '/../Modules/' . $module . '/DbMigrations';

            if (file_exists($path))
            {
                $paths[] = array(
                    'path' => $path,
                    'module' => $module,
                );
            }
        }

        return $paths;
    }

    /**
     * Create a new migrations file and redirect to the migrations page.
     *
     * @param array $post
     *
     * @return void
     */
    protected function createNew(array $post)
    {
        $migrationFilesDir = $this->getGlobalMigrationsDirectory();
        $webPath = $this->globalConfig->getConfig('dir_ws_migrations');
        $filename = \date('Y-m-d_His');

        if ($post['name'])
        {
            $filename .= '-' . $post['name'];
        }

        $content = <<<'HTML'
<?php
$DB_MIGRATION = array(
    'description' => function () {
        return '';
    },
    'up' => function ($migration_metadata) {
        $results = array();

        $results[] = \SmartWork\Utility\Database::query_raw('

        ');

        return !in_array(false, $results);
    },
    'down' => function ($migration_metadata) {
        $results = array();

        $results[] = \SmartWork\Utility\Database::query_raw('
            ALTER TABLE tbl CHANGE col col_to_delete TEXT
        ');

        return !in_array(false, $results);
    }
);

HTML;

        \file_put_contents($migrationFilesDir . '/' . $filename . '.php', $content);
        General::redirect($webPath . '/migrations.php');
    }

    /**
     * Apply or unapply a migrations file.
     *
     * @param array $post
     * @param array $migrationFiles
     *
     * @return string
     */
    protected function setApplyStatus(array $post,  array$migrationFiles): string
    {
        $webPath = $this->globalConfig->getConfig('dir_ws_migrations');

        foreach ($migrationFiles as $filename)
        {
            $eligible = false;

            if ($filename['path'] == $post['filename'])
            {
                $eligible = true;
            }

            if ($post['next'])
            {
                if (!$this->isMigrationApplied($filename['path']))
                {
                    $eligible = true;
                }
            }

            if ($eligible)
            {
                require_once($filename['path']);

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
                        $this->markMigrationUnapplied($filename['path']);
                        redirect($webPath.'/migrations.php');
                    }
                    else
                    {
                        $this->markMigrationApplied($filename['path']);
                        redirect($webPath.'/migrations.php');
                    }
                }
                else
                {
                    $status = $post['unapply'] ? 'unapplying' : 'applying';
                    $escapeFilename = htmlentities($filename['path']);
                    $message = <<<MSG
<div style="font-family: sans-serif; font-size: 13px;">
    There has been a problem $status '$escapeFilename'<br>
</div>
<a href="$webPath/migrations.php">Back</a>
MSG;

                    return $message;
                }
            }
        }
    }

    /**
     * Create the list with the migration files.
     *
     * @param array $migrationFiles
     *
     * @return string
     */
    protected function showList(array $migrationFiles): string
    {
        $webPath = $this->globalConfig->getConfig('dir_ws_migrations');
        $message = <<<HTM
<style type="text/css">
    table.df_migration_manager { font-family: sans-serif; font-size: 13px; border-spacing: 0px 3px; }
    table.df_migration_manager td { background-color: #EEEEEE; padding: 0px 4px; }
    table.df_migration_manager th { padding: 2px 4px; }
    table.df_migration_manager th { text-align: left; }
    table.df_migration_manager td.df_migration_manager_applied_col { text-align: center; }
    table.df_migration_manager td[colspan] { background-color: inherit; }
</style>
<table class="df_migration_manager">
    <tr>
        <th>Filename</th>
        <th>Description</th>
        <th>Applied?</th>
        <th colspan="2"></th>
    </tr>
HTM;

        $all_applied = true;
        $module = '';
        foreach ($migrationFiles as $filename)
        {
            if (array_key_exists('module', $filename) && $module != $filename['module'])
            {
                $module = $filename['module'];
                $message .= <<<HTM
    <tr>
        <th colspan="5">&nbsp;&nbsp;$module</th>
    </tr>
HTM;
            }

            if (!array_key_exists('module', $filename) && $module != 'Global')
            {
                $module = 'Global';
                $message .= <<<HTM
    <tr>
        <th colspan="5">&nbsp;&nbsp;$module</th>
    </tr>
HTM;
            }

            require_once($filename['path']);

            $description = $DB_MIGRATION['description']();
            $applied = $this->isMigrationApplied($filename['path']);

            if (!$applied)
            {
                $all_applied = false;
            }

            $appliedClass = '';
            $escapedFilename = \htmlentities($filename['file']);
            $escapedDescription = \htmlentities($description);
            $appliedMark = '';
            $applyLink = '<a href="' . $webPath . '/migrations.php?apply=1&amp;filename='
                . urlencode($filename['path']) . '">Apply</a>';
            $unapplyLink = 'Unapply';

            if ($applied)
            {
                $appliedClass = 'df_migration_manager_applied';
                $appliedMark = '&#x2714;';
                $applyLink = 'Apply';
                $unapplyLink = '<a href="' . $webPath . '/migrations.php?unapply=1&amp;filename='
                    . urlencode($filename['path']) . '">Unapply</a>';
            }
            $message .= <<<HTM
    <tr class="$appliedClass">
        <td class="df_migration_manager_filename_col">$escapedFilename</td>
        <td class="df_migration_manager_description_col">$escapedDescription</td>
        <td class="df_migration_manager_applied_col">$appliedMark</td>
        <td class="df_migration_manager_apply_col">$applyLink</td>
        <td class="df_migration_manager_unapply_col">$unapplyLink</td>
    </tr>
HTM;
        }

        $applyNextLink = 'Apply next';

        if (!$all_applied)
        {
            $applyNextLink = '<a href="' . $webPath . '/migrations.php?next=1&amp;apply=1">Apply next</a>';
        }

        $message .= <<<HTM
    <tr>
        <td colspan="5">
            $applyNextLink
            <a id="addNew" href="$webPath/migrations.php?create_new=1">Create new</a>
        </td>
    </tr>
</table>
HTM;

        return $message;
    }

    /**
     * check if the migrations have been initialized
     *
     * @return bool
     */
    protected function isMigrationsInitialized(): bool
    {
        $sql = 'SHOW TABLES LIKE "db_migrations"';
        return !!Database::query($sql);
    }

    /**
     * initialize the migrations
     *
     * @return void
     */
    protected function initializeMigration()
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
     * @param string $filename
     *
     * @return bool
     */
    protected function isMigrationApplied(string $filename): bool
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
     * @param string $filename
     *
     * @return void
     */
    protected function markMigrationApplied(string $filename)
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
     * @param string $filename
     *
     * @return void
     */
    protected function markMigrationUnapplied(string $filename)
    {
        $sql = '
            UPDATE `db_migrations`
            SET `status` = "unapplied"
            WHERE `filename` = ' . Database::sqlval($filename) . '
        ';
        Database::query($sql);
    }

    /**
     * Check for old database entries and rework them to the new format.
     *
     * @return void
     */
    protected function checkForOldEntries()
    {
        $migrationFilesDir = $this->getGlobalMigrationsDirectory();

        $sql = '
            SELECT filename
            FROM db_migrations
        ';
        $data = Database::query($sql, true);

        foreach ($data as $file)
        {
            if (substr($file['filename'], 0, 10) !== substr($migrationFilesDir, 0, 10))
            {
                $sql = '
                    UPDATE db_migrations
                    SET filename = ' . Database::sqlval($migrationFilesDir . $file['filename']) . '
                    WHERE filename = ' . Database::sqlval($file['filename']) . '
                ';
                Database::query($sql);
            }
        }
    }

    /**
     * Get the global migration files directory and checks if the last character is a slash.
     * If not, the slash will be appended.
     *
     * @return string
     */
    protected function getGlobalMigrationsDirectory(): string
    {
        $migrationFilesDir = $this->globalConfig->getConfig('migrations_dir');

        if (substr($migrationFilesDir, -1) !== '/')
        {
            $migrationFilesDir .= '/';
        }

        return $migrationFilesDir;
    }

    /**
     * Replace the backslash from windows with a normal slash.
     *
     * @param string $path
     *
     * @return string
     */
    protected function replaceBackslash(string $path): string
    {
        return str_replace('\\', '/', $path);
    }
}
