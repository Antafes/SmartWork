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
 * @package   SmartWork
 * @author    Marian Pollzien <map@wafriv.de>
 * @copyright (c) 2015, Marian Pollzien
 * @license   https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
namespace SmartWork;

/**
 * Template class which initializes Smarty and the translator.
 *
 * @package SmartWork
 * @author  Marian Pollzien <map@wafriv.de>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class NoTemplate extends Template
{
	/**
	 * @var \SmartWork\Translator
	 */
	protected $translator;

	/**
	 * Creates an instance of Smarty and a the translator.
	 * Also sets the template directories for Smarty.
	 */
	function __construct()
	{
		$translator = Translator::getInstance();
		$this->translator = $translator;	}

	/**
	 * Get the objects smarty instance.
	 *
	 * @return void
	 */
	public function getSmarty()
	{
	}

	/**
	 * Set the template to use.
	 *
	 * @param string $template
     *
     * @return void
	 */
	public function setTemplate(string $template)
	{
	}

	/**
	 * Assign a value with a name to the smarty instance.
	 *
	 * @param array|string $name
	 * @param mixed        $value
	 *
	 * @return void
	 */
	public function assign($name, $value = null)
	{
	}

	/**
	 * Render the defined template
	 *
	 * @return void
	 */
	public function render()
	{
	}

	/**
	 * Get the translator object.
	 *
	 * @return Translator
	 */
	public function getTranslator(): Translator
	{
		return $this->translator;
	}

	/**
	 * Load a js file in smarty.
	 * Use only the filename without ending.
	 *
	 * @param string $file
	 *
	 * @return void
	 */
	public function loadJs(string $file)
	{
	}

    /**
	 * Load a js script in smarty.
	 *
	 * @param string $script Contains only the js!
	 *
	 * @return void
	 */
	public function loadJsScript(string $script)
	{
	}

	/**
	 * Load a js ready script in smarty.
	 *
	 * @param string $script Contains only the js!
	 *
	 * @return void
	 */
	public function loadJsReadyScript(string $script)
	{
	}

	/**
	 * Load a css file in smarty.
	 * Use only the filename without ending.
	 *
	 * @param string $file
	 *
	 * @return void
	 */
	public function loadCss(string $file)
	{
	}

    /**
     * Remove all stored JS and CSS files and inline scripts.
     *
     * @return void
     */
    public function clearJsAndCss()
    {
    }

    /**
     * Remove all stored JS files and inline scripts.
     *
     * @return void
     */
    public function clearJs()
    {
    }

    /**
     * Remove all stored CSS files.
     *
     * @return void
     */
    public function clearCss()
    {
    }
}
