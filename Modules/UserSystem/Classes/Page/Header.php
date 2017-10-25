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
 * @package    SmartWork
 * @subpackage Page
 * @author     Marian Pollzien <map@wafriv.de>
 * @copyright  (c) 2015, Marian Pollzien
 * @license    https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
namespace SmartWork\UserSystem\Page;

/**
 * Description of Header
 *
 * @package    SmartWork
 * @subpackage Page
 * @author     Marian Pollzien <map@wafriv.de>
 * @license    https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class Header extends \SmartWork\Page\Header
{
    /**
     * Check whether a user is logged in and if the user has admin privileges.
     *
     * @return void
     */
    protected function createMenu()
    {
        $globalConfig = \SmartWork\GlobalConfig::getInstance();
        $useModules = $globalConfig->getConfig('useModules');
        $userSystemActive = in_array('UserSystem', $globalConfig->getConfig('modules'));
        $configPages = $globalConfig->getConfig('menu');
        ksort($configPages);

        if ($userSystemActive && $_SESSION['userId'])
        {
            $user = \SmartWork\User::getUserById($_SESSION['userId']);
            $this->template->assign('isAdmin', $user->getAdmin());
        }

        $pages = array();
        foreach ($configPages as $page)
        {
            if ($page['show'] === -1 || (!$useModules && $page['show'] !== 2))
            {
                $pages[] = $page + array(
                    'key' => strtolower($page['page']),
                    'active' => !$_GET['page'] && $page['default'] === -1 || $_GET['page'] == $page['page'],
                );
                continue;
            }

            if ($useModules && $userSystemActive)
            {
                if ($_SESSION['userId'] && $page['show'] === 1)
                {
                    $pages[] = $page + array(
                        'key' => strtolower($page['page']),
                        'active' => !$_GET['page'] && $page['default'] === 1 || $_GET['page'] == $page['page'],
                    );
                    continue;
                }

                if (!$_SESSION['userId'] && $page['show'] === 0)
                {
                    $pages[] = $page + array(
                        'key' => strtolower($page['page']),
                        'active' => !$_GET['page'] && $page['default'] === 0 || $_GET['page'] == $page['page'],
                    );
                    continue;
                }

                if ($_SESSION['userId'] && $user->getAdmin() && $page['show'] === 2)
                {
                    $pages[] = $page + array(
                        'key' => strtolower($page['page']),
                        'active' => !$_GET['page'] && $page['default'] === 2 || $_GET['page'] == $page['page'],
                    );
                    continue;
                }
            }
        }

        $this->getTemplate()->assign('pages', $pages);
    }
}
