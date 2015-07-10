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
 * Basic model class.
 *
 * @package SmartWork
 * @author  Marian Pollzien <map@wafriv.de>
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
