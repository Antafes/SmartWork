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
namespace SmartWork;

/**
 * Handles the global configuration derived from $GLOBALS.
 *
 * @package SmartWork
 * @author  Marian Pollzien <map@wafriv.de>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class GlobalConfig
{
    /**
     * Singleton instance of the config
     *
     * @var \self
     */
    protected static $globalConfig;

    /**
     * @var array
     */
    protected $globals;

    /**
     * Constructor
     */
    private function __construct()
    {
        $this->globals = $GLOBALS;
    }

    /**
     * Get the singleton instance.
     *
     * @return self
     */
    public static function getInstance(): self
    {
        if (!self::$globalConfig)
        {
            $config = new self();
            self::$globalConfig = $config;
        }

        return self::$globalConfig;
    }

    /**
     * Get the global configuration value.
     *
     * @param array|string $config May be a string containing only one specific
     *                             config to fetch, or an array of the following
     *                             structure:
     *                             array(
     *                                 'key1' => array(
     *                                     'key1.1',
     *                                     'key1.2',
     *                                 ),
     *                                 'key2' => 'key2.1',
     *                             )
     *
     * @return mixed
     */
    public function getConfig($config)
    {
        if (!is_array($this->globals['config']))
        {
            $this->globals['config'] = array();
        }

        return $this->getGlobal($config, $this->globals['config']);
    }

    /**
     * Get the global hook value.
     *
     * @param array|string $hook May be a string containing only one specific
     *                           hook to fetch, or an array of the following
     *                           structure:
     *                           array(
     *                               'key1' => array(
     *                                   'key1.1',
     *                                   'key1.2',
     *                               ),
     *                               'key2' => 'key2.1',
     *                           )
     *
     * @return mixed
     */
    public function getHook($hook)
    {
        if (!is_array($this->globals['hooks']))
        {
            $this->globals['hooks'] = array();
        }

        return $this->getGlobal($hook, $this->globals['hooks']);
    }

    /**
     * Get the global defined by the given parameters.
     *
     * @param array|string $global May be a string containing only one specific
     *                             global to fetch, or an array of the following
     *                             structure:
     *                             array(
     *                                 'key1' => array(
     *                                     'key1.1',
     *                                     'key1.2',
     *                                 ),
     *                                 'key2' => 'key2.1',
     *                             )
     * @param array        $parent The parent array
     *
     * @return mixed
     */
    public function getGlobal($global, $parent = null)
    {
        if ($parent == null)
        {
            $parent = $this->globals;
        }

        if (!is_array($global))
        {
            return $parent[$global];
        }

        $results = array();

        foreach ($global as $key => $value)
        {
            if (!is_array($parent[$key]))
            {
                $parent[$key] = array();
            }

            $result = $this->getGlobal($value, $parent[$key]);

            if ($result)
            {
                $results[$key] = $result;
            }
        }

        return $results;
    }
}
