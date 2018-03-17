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
 * @copyright (c) 2018, Marian Pollzien
 * @license   https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
namespace SmartWork\Utility;

use SmartWork\GlobalConfig;

/**
 * Utility class for creating URLs.
 *
 * @author Marian Pollzien <map@wafriv.de>
 */
class Url
{
    /**
     * Get the current url.
     *
     * @param type $absolute
     *
     * @return string
     */
    public static function getCurrentUrl($absolute = false): string
    {
        return self::buildUrl($_GET, $absolute);
    }

    /**
     * Build a url.
     *
     * @param array $parameters The parameters to build the url with
     * @param bool  $absolute   Whether the url should be absolute or not
     *
     * @return string
     */
    public static function buildUrl(array $parameters, bool $absolute = false): string
    {
        $url = '';

        if ($absolute)
        {
            $url .= '://' . GlobalConfig::getInstance()->getConfig('dir_ws') . '/';
        }

        $url .= 'index.php';

        if (!empty($parameters))
        {
            $url .= '?' . http_build_query($parameters);
        }

        return $url;
    }
}
