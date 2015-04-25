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
 * Basic list class.
 *
 * @package SmartWork
 * @author  friend8 <map@wafriv.de>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
abstract class Listing
{
	/**
	 * @var array
	 */
	protected $list = array();

	/**
	 * Load the list of models.
	 *
	 * @return \self
	 */
	public abstract static function loadList();

	/**
	 * Get the list.
	 *
	 * @return array
	 */
	public function getList()
	{
		return $this->list;
	}

	/**
	 * Override the current loaded list.
	 *
	 * @param array $list
	 *
	 * @return void
	 */
	public function setList($list)
	{
		$this->list = $list;
	}

	/**
	 * Get a model by its id.
	 *
	 * @return \SmartWork\Model
	 */
	public abstract function getById($id);

	/**
	 * Get a list of arrays from the loaded models.
	 *
	 * @return array
	 */
	public function getAsArray()
	{
		$list = array();
		foreach ($this->list as $row)
		{
			$list[] = $row->getAsArray();
		}

		return $list;
	}
}
