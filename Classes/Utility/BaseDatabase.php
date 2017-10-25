<?php
declare(strict_types=1);
/**
 * This file is part of SmartWork.
 *
 * Image Upload is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Image Upload is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with SmartWork.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   SmartWork
 * @author    Marian Pollzien <map@wafriv.de>
 * @copyright (c) 2017, Marian Pollzien
 * @license   https://www.gnu.org/licenses/lgpl.html LGPLv3
 */

namespace SmartWork\Utility;

/**
 * Description of BaseDatabase
 *
 * @author Marian Pollzien <map@wafriv.de>
 */
abstract class BaseDatabase implements Interfaces\Database
{
    /**
     * Escape and wrap every value in the given array and escape every key.
     *
     * @param array $values
     * @param bool $wrap
     *
     * @return array
     */
    public function sqlvalMultiple(array $values, bool $wrap = true): array
    {
        $result = array();

        foreach ($values as $key => $row)
        {
            $escapedKey = $this->sqlval($key, false);
            $result[$escapedKey] = $this->sqlval($row, $wrap);
        }

        return $result;
    }

    /**
     * Handles the database connection.
     * This method always returns the resource object of the created connection.
     *
     * @return object
     */
    protected abstract function connect();

    /**
     * Get the primary key field for the given table.
     * This will throw an exception if the primary key consists of multiple fields.
     *
     * @param string $table The table to fetch the primary key from.
     *
     * @return string
     * @throws DatabaseException
     */
    protected abstract function getPrimaryKey(string $table): string;
}
