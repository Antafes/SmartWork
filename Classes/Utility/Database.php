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
 * along with SmartWork. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   SmartWork
 * @author    Marian Pollzien <map@wafriv.de>
 * @copyright (c) 2016, Marian Pollzien
 * @license   https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
namespace SmartWork\Utility;

/**
 * Utility class to handle the database connection and queries to the database.
 *
 * @package    SmartWork
 * @subpackage Utility
 * @author     Marian Pollzien <map@wafriv.de>
 * @deprecated since version 3.0, use DatabaseNew instead
 */
class Database
{
    /**
     * Handles the MySQL queries.
     * If the query is a select, it returns an array if there is only one value, otherwise it
     * returns the value.
     * If the query is an update, replace or delete from, it returns the number of affected rows
     * If the query is an insert, it returns the last insert id
     *
     * @param string $trimmedSql
     * @param bool $noTransform (default = false) if set to "true" the query function always returns
     *                          a multidimension array
     * @param bool $raw         Whether the result should return the raw mysqli result
     *
     * @return array|string|int|float
     */
    public static function query(string $sql, bool $noTransform = false, bool $raw = false)
    {
        $db = new DB();
        return $db->execute($sql, $noTransform, $raw);
    }

    /**
     * See query()
     *
     * @param string $sql
     *
     * @return mixed
     */
    public static function query_raw(string $sql)
    {
        $db = new DB();
        return $db->execute($sql, false, true);
    }

    /**
     * Let the transaction begin.
     * This is only used if transations for mysql is active.
     *
     * @return void
     */
    public static function transactionBegin()
    {
        self::query("BEGIN");
    }

    /**
     * Save changes on database.
     * This is only used if transations for mysql is active.
     *
     * @return void
     */
    public static function transactionCommit()
    {
        self::query("COMMIT");
    }

    /**
     * Rollback changes.
     * This is only used if transations for mysql is active.
     *
     * @return void
     */
    public static function transactionRollback()
    {
        self::query("ROLLBACK");
    }

    /**
     * Escapes and wraps the given value. If it's an array, all elements will be
     * escaped separately.
     *
     * @param mixed $value
     * @param bool  $wrap
     *
     * @return string|array
     */
    public static function sqlval($value, bool $wrap = true)
    {
        $db = new DB();

        if (is_array($value))
        {
            return $db->sqlvalMultiple($value, $wrap);
        }
        else
        {
            return $db->sqlval($value, $wrap);
        }
    }
}
