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
 * Handles the displaying of pages.
 *
 * @package SmartWork
 * @author  friend8 <map@wafriv.de>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class Display
{
	/**
	 * A list of pages which are not accessible.
	 *
	 * @var array
	 */
	protected $unallowedPages = array();

	/**
	 * A list of unallowed pages which will be added to the existing list.
	 *
	 * @param array $unallowedPages
	 */
	function __construct($unallowedPages = array())
	{
		$this->unallowedPages += $unallowedPages;
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

		if ($page->getTemplate())
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
		if (in_array($pageName, $this->unallowedPages))
			return 'Index';
		else
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
