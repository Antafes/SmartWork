<?php
/**
 * Part of the SmartWork framework.
 *
 * @package    SmartWork
 * @subpackage Page
 * @author     friend8 <map@wafriv.de>
 * @license    https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
namespace SmartWork\Page;

/**
 * Description of EsHeader
 *
 * @package    SmartWork
 * @subpackage Page
 * @author     friend8 <map@wafriv.de>
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
		$this->template->loadCss('jquery-ui-1.11.0.custom');

		// Add JS files
		$this->template->loadJs('jquery-2.1.1');
		$this->template->loadJs('jquery-ui-1.11.0.custom');

		// Add the language entries for JavaScripts
		$this->template->assign(
			'translations', json_encode($this->template->getTranslator()->getAsArray())
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
		if ($_SESSION['userId'])
		{
			$user = \SmartWork\User::getUserById($_SESSION['userId']);
			$this->template->assign('isAdmin', $user->getAdmin());
		}
	}
}
