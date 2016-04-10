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
 * Handles the displaying of pages.
 *
 * @package SmartWork
 * @author  Marian Pollzien <map@wafriv.de>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class Display
{
    /**
     * The global config object.
     *
     * @var \SmartWork\GlobalConfig
     */
    protected $globalConfig;

    /**
	 * A list of pages which are not accessible.
	 *
	 * @var array
	 */
	protected $unallowedPages = array();

	/**
	 * A list of pages which are accessible without login.
	 *
	 * @var array
	 */
	protected $pagesWithoutLogin = array(
		'Register',
		'Login',
		'Imprint',
		'LostPassword',
	);

	/**
	 * A list of unallowed pages which will be added to the existing list.
	 *
	 * @param array $unallowedPages
	 */
	function __construct($unallowedPages = array())
	{
        $this->globalConfig = GlobalConfig::getInstance();
		$this->unallowedPages = array_merge($unallowedPages, $this->unallowedPages);
		$globalUnallowedPages = $this->globalConfig->getConfig(
			array(
				'Display' => 'pagesWithoutLogin',
			)
		);

        if (!is_array($globalUnallowedPages['Display']))
        {
            $globalUnallowedPages['Display'] = array();
        }

        $this->pagesWithoutLogin = array_merge(
            $globalUnallowedPages['Display'],
            $this->pagesWithoutLogin
        );
	}

	/**
	 * Display the given page.
	 *
	 * @param string $pageName
	 *
	 * @return void
	 */
	public function showPage($pageName)
	{
		$pageName = $this->checkPage($pageName);

		// Create the page itself
		$class = '\\Page\\'.$pageName;
		/* @var $page \SmartWork\Page */
		$page = new $class();

		if ($page->getTemplate() && !$page->isAjax())
		{
			// Create the page header
			if (class_exists('\\Page\\Header'))
			{
				$header = new \Page\Header($page->getTemplate());
			}
			else
			{
				$header = new \SmartWork\Page\Header($page->getTemplate());
			}

			$header->process();
		}

		$page->process();
		$page->render();
	}

	/**
	 * Check if the page is in the list of unallowed pages. If so, return 'Index'.
	 *
	 * @param string $pageName
	 *
	 * @return string
	 */
	protected function checkPage($pageName)
	{
        $checkPageHooks = $this->globalConfig->getHook(
            array(
                'Display' => 'checkPage',
            )
        );

		if ($checkPageHooks)
		{
			foreach ($checkPageHooks as $hook)
			{
				$result = $hook($pageName);

				if ($result)
				{
					$pageName = $result;
					break;
				}
			}
		}

		if (in_array($pageName, $this->unallowedPages))
		{
			return 'Index';
		}

		if (!$_SESSION['userId'] && !in_array($pageName, $this->pagesWithoutLogin))
		{
			$pageName = 'Login';
		}

		return $pageName;
	}

	/**
	 * Add one or more pages to the list of unallowed pages.
	 *
	 * @param array|string $pageNames
	 *
	 * @return void
	 */
	public function addUnallowedPages($pageNames)
	{
		if (is_array($pageNames))
		{
			$this->unallowedPages += $pageNames;
		}
		else
		{
			$this->unallowedPages += array($pageNames);
		}
	}

	/**
	 * Remove one or more pages from the list of unallowed pages.
	 *
	 * @param array|string $pageNames
	 *
	 * @return void
	 */
	public function removeUnallowedPages($pageNames)
	{
		if (!is_array($pageNames))
		{
			$pageNames = array($pageNames);
		}

		foreach ($pageNames as $pageName)
		{
			$index = array_search($pageName, $this->unallowedPages);
			unset($this->unallowedPages[$index]);
		}
	}

	/**
	 * Clear the list of unallowed pages.
	 *
	 * @return void
	 */
	public function clearUnallowedPages()
	{
		$this->unallowedPages = array();
	}
}
