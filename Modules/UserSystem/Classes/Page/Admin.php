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
 * along with the SmartWork. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   SmartWork
 * @author    Marian Pollzien <map@wafriv.de>
 * @copyright (c) 2015, Marian Pollzien
 * @license   https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
namespace SmartWork\UserSystem\Page;
/**
 * Class for the admin page.
 *
 * @package Page
 * @author  friend8 <map@wafriv.de>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class Admin extends \SmartWork\Page
{
    /**
     * Set the template.
     */
    public function __construct()
    {
        parent::__construct('admin');
    }

    /**
     * Process the activate, setAdmin and removeAdmin options and show the user list.
     *
     * @return void
     */
    public function process()
    {
        if ($_GET['activate'])
        {
            $this->activateUser($_GET['activate']);
            \SmartWork\Utility\General::redirect('index.php?page=Admin');
        }

        if ($_GET['setAdmin'])
        {
            $this->changeAdminStatus($_GET['setAdmin'], true);
            \SmartWork\Utility\General::redirect('index.php?page=Admin');
        }

        if ($_GET['removeAdmin'])
        {
            $this->changeAdminStatus($_GET['removeAdmin'], false);
            \SmartWork\Utility\General::redirect('index.php?page=Admin');
        }

        $user = \SmartWork\User::getUserById($_SESSION['userId']);

        if (!$user->getAdmin())
        {
            \SmartWork\Utility\General::redirect('index.php?page=Index');
        }

        $this->template->assign('userList', $this->getUserList());
    }

    /**
     * Get a list with all users that are not deleted.
     *
     * @return array
     */
    protected function getUserList(): array
    {
        $sql = '
            SELECT userId
            FROM users
            WHERE !deleted
        ';
        $users = query($sql, true);

        $userList = array();
        foreach ($users as $user)
        {
            $userList[] = \SmartWork\User::getUserById($user['userId']);
        }

        return $userList;
    }

    /**
     * Activate the given user.
     *
     * @param int $userId
     *
     * @return void
     */
    protected function activateUser(int $userId)
    {
        $user = \SmartWork\User::getUserById($userId);
        $user->activate();
    }

    /**
     * Set the admin status of the given user to $status.
     *
     * @param int  $userId
     * @param bool $status
     *
     * @return void
     */
    protected function changeAdminStatus(int $userId, bool $status)
    {
        $user = \SmartWork\User::getUserById($userId);
        $user->setAdmin($status);
    }
}
