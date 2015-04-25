<?php
/**
 * Part of the SmartWork framework.
 *
 * @package SmartWork
 * @author  friend8 <map@wafriv.de>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
namespace SmartWork;

/**
 * Basic page class.
 *
 * @package SmartWork
 * @author  friend8 <map@wafriv.de>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
abstract class Page
{
	/**
	 * @var \SmartWork\Template
	 */
	protected $template;

	/**
	 * Constructor
	 *
	 * @param string $template
	 */
	function __construct($template)
	{
		$this->template = new \SmartWork\Template();
		$this->template->setTemplate($template);
	}

	/**
	 * Render and output the template
	 *
	 * @return void
	 */
	public function render()
	{
		$this->template->render();
	}

	/**
	 * Get the template object.
	 *
	 * @return \SmartWork\Template
	 */
	public function getTemplate()
	{
		return $this->template;
	}

	/**
	 * Process possibly entered data of the page.
	 */
	abstract public function process();
}
