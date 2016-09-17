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
 * Class for the logout page.
 *
 * @package Page
 * @author  Marian Pollzien <map@wafriv.de>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class Logout extends \SmartWork\Page
{
	/**
	 * No template used.
	 */
	public function __construct()
	{
        parent::__construct('');
    }

	/**
	 * Destroy the session and redirect to the login page.
	 *
	 * @return void
	 */
	public function process()
	{
		session_destroy();
		\SmartWork\Utility\General::redirect('index.php?page=Login');
	}
}
