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
 * Class for the lost password page.
 *
 * @package Page
 * @author  Marian Pollzien <map@wafriv.de>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class LostPassword extends \SmartWork\Page
{
    /**
     * Set the used template.
     */
    function __construct()
    {
        parent::__construct('lostPassword');
    }

    /**
     * Show the lost password form and init the lost password process.
     *
     * @return void
     */
    public function process()
    {
        if (!$_POST['lostPassword'] || $_POST['lostPassword'] != $_SESSION['formSalts']['lostPassword'])
        {
            return;
        }

        if (!$_POST['email'])
        {
            $this->template->assign('error', 'emptyEmail');
            return;
        }

        $user = \SmartWork\User::getUserByMail($_POST['email']);

        if ($user)
        {
            $user->lostPassword();
            $this->template->assign('message', 'lostPasswordMailSent');
        }
        else
        {
            $this->template->assign('error', 'lostPasswordNoUserFound');
        }
    }
}
