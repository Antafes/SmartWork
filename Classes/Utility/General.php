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
    public static function redirect(string $location)
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
    public static function classLoad(string $name)
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
        $pieces = \explode('\\', $name);

        if ($pieces[0] === 'SmartWork')
        {
            $dirs[0] = $dirFsSystem . '/Classes/';
            \array_shift($pieces);
        }

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
}
