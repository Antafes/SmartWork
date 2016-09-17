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
 * Utility class to handle general things like the class loader or redirects.
 *
 * @package    SmartWork
 * @subpackage Utility
 * @author     Marian Pollzien <map@wafriv.de>
 */
class General
{
    /**
     * Redirect to the given location
     *
     * @param string $location
     *
     * @return void
     */
    public static function redirect($location)
    {
        \header('Location: '.$location);
        die();
    }

    /**
     * Class loader
     *
     * @param string $name
     *
     * @return boolean
     */
    public static function classLoad($name)
    {
        $dirFsSystem = $GLOBALS['config']['dir_fs_system'];
        if ($name == 'Smarty')
        {
            require_once($dirFsSystem . '/smarty3/Smarty.class.php');
            return true;
        }
        elseif ($name == 'PHPMailer')
        {
            require_once($dirFsSystem . '/phpmailer/class.phpmailer.php');
            return true;
        }

        $dirs = array($dirFsSystem . '/../Classes/');

        if ($GLOBALS['config']['useModules'])
        {
            foreach ($GLOBALS['config']['modules'] as $module)
            {
                $dirs[] = $dirFsSystem . '/../Modules/' . $module . '/Classes/';
            }
        }

        $pieces = \explode('\\', $name);

        // Check for SmartWork classes
        if ($pieces[0] === 'SmartWork')
        {
            $dirs[0] = $dirFsSystem . '/Classes/';

            if ($GLOBALS['config']['useModules'])
            {
                foreach ($GLOBALS['config']['modules'] as $module)
                {
                    $dirs[] = $dirFsSystem . '/Modules/' . $module . '/Classes/';
                }
            }
        }

        // Check for additional auto load directories.
        if (isset($GLOBALS['autoload']))
        {
            $additionalDirs = $GLOBALS['autoload'];

            if (!\is_array($additionalDirs))
            {
                $additionalDirs = array($additionalDirs);
            }

            $dirs = \array_merge($dirs, $additionalDirs);
        }

        $class = \array_pop($pieces);
        $pieces = self::tidyNamespaces($pieces);

        foreach ($dirs as $dir)
        {
            if ($pieces)
            {
                $dir .= \implode('/', $pieces) . '/';
            }

            if(\file_exists($dir . $class . '.php'))
            {
                require_once($dir . $class . '.php');
                return true;
            }
        }

        return false;
    }

    /**
     * Tidy up the namespaces array.
     * Removes SmartWork and all modules from the array.
     *
     * @param array $parts
     *
     * @return array
     */
    protected static function tidyNamespaces($parts)
    {
        $tidy = array();

        foreach ($parts as $part)
        {
            if ($part == 'SmartWork'
                || ($GLOBALS['config']['useModules'] && in_array($part, $GLOBALS['config']['modules']))
            )
            {
                continue;
            }

            $tidy[] = $part;
        }

        return $tidy;
    }

    /**
     * Add a menu entry to $GLOBALS['config']['menu'].
     *
     * @param string $page     The page key to add.
     * @param int    $show     On which state the menu entry should be shown.
     *                         -1 -> always
     *                         0  -> logged out (only if the UserSystem module is loaded)
     *                         1  -> logged in (only if the UserSystem module is loaded)
     *                         2  -> logged in as admin (only if the UserSystem module is loaded)
     * @param int    $default  Define the default page for the above show system, null to ommit.
     * @param int    $position The position in the array, default is to add at the end, but before
     *                         the logout and imprint.
     *
     * @return void
     */
    public static function addMenuPage($page, $show, $default = null, $position = null)
    {
        $menu = $GLOBALS['config']['menu'];
        $imprint = array_pop($menu);
        $logout = array_pop($menu);

        $entry = array(
            'page' => $page,
            'show' => $show,
        );

        if ($default !== null)
        {
            $entry['default'] = $default;
        }

        if ($position !== null)
        {
            $menu[$position] = $entry;
        }
        else
        {
            $menu[count($menu)] = $entry;
        }

        $menu[9998] = $logout;
        $menu[9999] = $imprint;
        $GLOBALS['config']['menu'] = $menu;
    }
}
