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
 * Basic model class.
 *
 * @package SmartWork
 * @author  friend8 <map@wafriv.de>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
abstract class Model
{
	/**
	 * Load the model by a given id.
	 *
	 * @return \self
	 */
	public abstract static function loadById($id);

	/**
	 * Get the models properties as array.
	 *
	 * @return array
	 */
	public abstract function getAsArray();

	/**
	 * Fill the objects properties with the given data and cast them if possible to the best
	 * matching type. Only existing properties are filled.
	 *
	 * @param array $data
	 *
	 * @return void
	 */
	public function fill($data)
	{
		foreach ($data as $key => $value)
		{
			if (property_exists($this, $key))
			{
				$this->$key = $this->castToType($value);
			}
		}
	}

	/**
	 * Cast the value to the best matching type. Currently only float and integer are recognized.
	 *
	 * @param string $value
	 *
	 * @return mixed
	 */
	protected function castToType($value)
	{
		if (is_numeric($value))
		{
			if (stripos($value, '.') !== false)
			{
				return \floatval($value);
			}
			else
			{
				return \intval($value);
			}
		}

		return $value;
	}
}
