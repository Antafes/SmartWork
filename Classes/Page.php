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
 * Basic page class.
 *
 * @package SmartWork
 * @author  Marian Pollzien <map@wafriv.de>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
abstract class Page
{
	/**
	 * @var \SmartWork\Template
	 */
	protected $template;

    /**
     * Whether to render the template or not
     *
     * @var boolean
     */
    protected $doRender = true;

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
	 * Assign a value with a name to the smarty instance.
	 *
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @return void
	 */
	public function assign($name, $value)
	{
		$this->getTemplate()->assign($name, $value);
	}

	/**
	 * Process possibly entered data of the page.
	 */
	abstract public function process();

    /**
     * Whether the page is called as ajax page
     *
     * @return boolean
     */
    public function isAjax()
    {
        return !!$_REQUEST['ajax'];
    }

    /**
     * Echo the ajax response and die.
     *
     * @param mixed $response
     */
    protected function echoAjaxResponse($response)
    {
        header('Content-Type: application/json');
        echo json_encode($response);
        die();
    }
}
