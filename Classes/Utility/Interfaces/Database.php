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

namespace SmartWork\Utility\Interfaces;

/**
 * Description of DatabaseInterface
 *
 * @author Marian Pollzien <map@wafriv.de>
 */
interface Database
{
    /**
     * Insert a new row into the database.
     *
     * @param string $table The table to add the values to.
     * @param array $values The values to add.
     *
     * @return int The id of the new row.
     */
    public function insert(string $table, array $values): int;

    /**
     * Update the table with the given values. This will automatically detect the tables primary key fields and use the
     * given id for it.
     * If the primary key consists of multiple fields, an exception is thrown.
     *
     * @param string $table The table to update.
     * @param array $values An array of keys and values to update the row with.
     * @param int $id The row to update.
     *
     * @return int The number of updated rows.
     * @throws DatabaseException
     */
    public function update(string $table, array $values, int $id): int;

    /**
     * Update the table with the given values. The rows to update are selected with the where parameter.
     *
     * @param string $table The table to update.
     * @param array $values An array of keys and values to update the rows with.
     * @param string $where The where clause defining the rows to update.
     *
     * @return int The number of updated rows.
     */
    public function updateWithWhere(string $table, array $values, string $where): int;

    /**
     * Delete a row with the given id. This will automatically detect the tables primary key fields and use the
     * given id for it.
     * If the primary key consists of multiple fields, an exception is thrown.
     *
     * @param string $table The table to delete the row from.
     * @param int $id The id of the row to delete.
     *
     * @return int The number of deleted rows.
     * @throws DatabaseException
     */
    public function delete(string $table, int $id): int;

    /**
     * Delete rows with the given where clause.
     *
     * @param string $table The table to delete the rows from
     * @param string $where The where clause defining the rows to delete.
     *
     * @return int The number of deleted rows.
     */
    public function deleteWithWhere(string $table, string $where): int;

    /**
     * Fetch the first row returned from the database for the given id.
     * The fields to fetch can be limited with the fields array, if ommitted all fields will be fetched.
     *
     * @param string $table The table to fetch the values from.
     * @param int $id The id defining the row to fetch.
     * @param array $fields The fields to fetch, might be empty.
     *
     * @return array
     */
    public function fetch(string $table, int $id, array $fields = array()): array;

    /**
     * Fetch the first row returned from the database for the given where clause.
     * The fields to fetch can be limited with the fields array, if ommitted all fields will be fetched.
     *
     * @param string $table The table to fetch the values from.
     * @param string $where The where clause defining the row to fetch.
     * @param array $fields The fields to fetch, might be empty.
     *
     * @return array
     */
    public function fetchWithWhere(string $table, string $where, array $fields = array()): array;

    /**
     * Fetch multiple rows returned from the database for the given id.
     * The fields to fetch can be limited with the fields array, if ommitted all fields will be fetched.
     *
     * @param string $table The table to fetch the values from.
     * @param int $id The id defining the row to fetch.
     * @param array $fields The fields to fetch, might be empty.
     *
     * @return array
     */
    public function fetchMultiple(string $table, int $id, array $fields = array()): array;

    /**
     * Fetch multiple rows returned from the database for the given where clause.
     * The fields to fetch can be limited with the fields array, if ommitted all fields will be fetched.
     *
     * @param string $table The table to fetch the values from.
     * @param string $where The where clause defining the row to fetch.
     * @param array $fields The fields to fetch, might be empty.
     *
     * @return array
     */
    public function fetchMultipleWithWhere(string $table, string $where, array $fields = array()): array;

    /**
     * Handles the MySQL queries.
     * If the query is a select, it returns an array if there is only one value, otherwise it
     * returns the value.
     * If the query is an update, replace or delete from, it returns the number of affected rows.
     * If the query is an insert, it returns the last insert id.
     *
     * @param string $sql The SQL query to execute.
     *
     * @return mixed
     */
    public function execute(string $sql);

    /**
     * Let the Transaction begin
     *
     * @return void
     */
    public function transactionBegin();

    /**
     * Save Changes on Database
     *
     * @return void
     */
    public function transactionCommit();

    /**
     * Rollback Changes
     *
     * @return void
     */
    public function transactionRollback();

    /**
     * Escapes and wraps the given value.
     *
     * @param mixed $value
     * @param bool  $wrap
     *
     * @return string
     */
    public function sqlval($value, bool $wrap = true): string;

    /**
     * Escape and wrap every value in the given array and escape every key.
     *
     * @param array $values
     * @param bool $wrap
     *
     * @return array
     */
    public function sqlvalMultiple(array $values, bool $wrap = true): array;
}
