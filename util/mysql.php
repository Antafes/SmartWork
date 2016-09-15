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
use SmartWork\Utility\Database;
use SmartWork\Utility\Migration;

/**
 * Handles the MySQL queries.
 * If the query is a select, it returns an array if there is only one value, otherwise it returns the value
 * If the query is an update, replace or delete from, it returns the number of affected rows
 * If the query is an insert, it returns the last insert id
 *
 * @author friend8
 * @param string $sql
 * @param bool $noTransform (default = false) If set to "true" the query function always returns a multidimension array
 * @param bool $raw         (default = false) Set to true to get the raw result
 *
 * @return array|string|int|float
 * @deprecated since version 1.1
 */
function query(string $sql, bool $noTransform = false, bool $raw = false)
{
    return Database::query($sql, $noTransform, $raw);
}

/**
 * See query()
 *
 * @param string $sql
 *
 * @return mixed
 * @deprecated since version 1.1
 */
function query_raw(string $sql)
{
    return Database::query_raw($sql);
}

/**
 * Let the Transaction begin
 *
 * @author BlackIce
 *
 * @return void
 * @deprecated since version 1.1
 */
function transactionBegin()
{
    Database::transactionBegin();
}

/**
 * Save Changes on Database
 *
 * @author BlackIce
 *
 * @return void
 * @deprecated since version 1.1
 */
function transactionCommit()
{
    Database::transactionCommit();
}

/**
 * Rollback Changes
 *
 * @author BlackIce
 *
 * @return void
 * @deprecated since version 1.1
 */
function transactionRollback()
{
    Database::transactionRollback();
}

/**
 * Escapes and wraps the given value. If it's an array, all elements will be
 * escaped separately.
 *
 * @param mixed $value The value to escape
 * @param bool  $wrap  (default = true) Whether to wrap the value with quotations marks
 *
 * @return string|array
 * @deprecated since version 1.1
 */
function sqlval($value, bool $wrap = true)
{
    return Database::sqlval($value, $wrap);
}

/**
 * Handles the MySQL connection.
 * Should only be used in sqlval() and query()
 *
 * @author friend8
 *
 * @global array $lang
 * @staticvar mysqli $mysql
 *
 * @return void
 * @deprecated since version 1.1
 */
function connect(): mysqli
{
}

/**
 * manager for the migrations
 *
 * @author friend8
 *
 * @param array $post
 *
 * @return string
 * @deprecated since version 1.1
 */
function migration_manager(array $post): string
{
    $migration = new Migration();
    return $migration->manager($post);
}

/**
 * check if the migrations have been initialized
 *
 * @author friend8
 *
 * @return void
 * @deprecated since version 1.1
 */
function is_migrations_initialized(): bool
{
}

/**
 * initialize the migrations
 *
 * @author friend8
 *
 * @return void
 * @deprecated since version 1.1
 */
function initialize_migration()
{
}

/**
 * check if the migration has been applied
 *
 * @author friend8
 *
 * @param string $filename
 *
 * @return void
 * @deprecated since version 1.1
 */
function is_migration_applied(string $filename): bool
{
}

/**
 * mark the migration as applied
 *
 * @author friend8
 *
 * @param string $filename
 *
 * @return void
 * @deprecated since version 1.1
 */
function mark_migration_applied(string $filename)
{
}

/**
 * mark the migration as unapplied
 *
 * @author friend8
 *
 * @param string $filename
 *
 * @return void
 * @deprecated since version 1.1
 */
function mark_migration_unapplied(string $filename)
{
}
