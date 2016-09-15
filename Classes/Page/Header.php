<?php
declare(strict_types=1);
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
namespace SmartWork\Page;

/**
 * Description of EsHeader
 *
 * @package    SmartWork
 * @subpackage Page
 * @author     Marian Pollzien <map@wafriv.de>
 * @license    https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class Header extends \SmartWork\Page
{
    /**
     * Constructor
     *
     * @param \SmartWork\Template $template
     */
    public function __construct(\SmartWork\Template $template)
    {
        $this->template = $template;
    }

    /**
     * Add css and javascript files.
     * Load the translations for javascripts.
     * Create the menu.
     *
     * @return void
     */
    public function process()
    {
        // Add basic CSS files
        $this->template->loadCss('common');
        $this->template->loadCss('jquery-ui');

        // Add JS files
        $this->template->loadJs('jquery-2.1.4');
        $this->template->loadJs('jquery-ui');

        // Add the language entries for JavaScripts
        $this->template->assign(
            array(
                'translations' => json_encode($this->template->getTranslator()->getAsArray()),
                'languageCode' => $this->template->getTranslator()->getCurrentLanguageObject()->getIso2code(),
            )
        );

        $this->createMenu();
    }

    /**
     * Check whether a user is logged in and if the user has admin privileges.
     *
     * @return void
     */
    protected function createMenu()
    {
        if (array_key_exists('userId', $_SESSION) && $_SESSION['userId'])
        {
            $user = \SmartWork\User::getUserById($_SESSION['userId']);
            $this->template->assign('isAdmin', $user->getAdmin());
        }
    }
}
