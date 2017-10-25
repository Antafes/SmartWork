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
 */
class DB extends BaseDatabase
{
    /**
     * Static variable for holding the database connection.
     *
     * @var \mysqli
     */
    protected static $mysql;

    /**
     * Mapping for casting database values.
     *
     * @var array
     */
    protected $typeMapping = array(
        'bool' => 'bool',
        'tinyint(1)' => 'bool',
        'bit' => 'int',
        'int' => 'int',
        'decimal' => 'float',
        'dec' => 'float',
        'float' => 'float',
        'double' => 'float',
    );

    /**
     * Insert a new row into the database.
     *
     * @param string $table The table to add the values to.
     * @param array $values The values to add.
     *
     * @return int The id of the new row.
     */
    public function insert(string $table, array $values): int
    {
        $escapedValues = $this->sqlvalMultiple($values);
        $sql = '
            INSERT INTO ' . $this->sqlval($table, false) . '
            SET ';

        foreach ($escapedValues as $key => $value)
        {
            $sql .= $key . ' = ' . $value . "\n";
        }

        return $this->execute($sql);
    }

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
    public function update(string $table, array $values, int $id): int
    {
        return $this->updateWithWhere(
            $table,
            $values,
            $this->getPrimaryKey($table) . ' = ' . $this->sqlval($id)
        );
    }

    /**
     * Update the table with the given values. The rows to update are selected with the where parameter.
     *
     * @param string $table The table to update.
     * @param array $values An array of keys and values to update the rows with.
     * @param string $where The where clause defining the rows to update.
     *
     * @return int The number of updated rows.
     */
    public function updateWithWhere(string $table, array $values, string $where): int
    {
        $escapedValues = $this->sqlvalMultiple($values);
        $sql = '
            UPDATE ' . $this->sqlval($table, false) . '
            SET ';

        foreach ($escapedValues as $key => $value)
        {
            $sql .= $key . ' = ' . $value . "\n";
        }

        $sql .= 'WHERE ' . $where;

        return $this->execute($sql);
    }

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
    public function delete(string $table, int $id): int
    {
        return $this->deleteWithWhere(
            $table,
            $this->getPrimaryKey($table) . ' = ' . $this->sqlval($id)
        );
    }

    /**
     * Delete rows with the given where clause.
     *
     * @param string $table The table to delete the rows from
     * @param string $where The where clause defining the rows to delete.
     *
     * @return int The number of deleted rows.
     */
    public function deleteWithWhere(string $table, string $where): int
    {
        $sql = '
            DELETE FROM ' . $this->sqlval($table, false) . '
            WHERE ' . $where . '
        ';

        return $this->execute($sql);
    }

    /**
     * Fetch the first row returned from the database for the given id.
     * The fields to fetch can be limited with the fields array, if ommitted all fields will be fetched.
     * If $cast is true (default), the resulting values will be cast according to the database field types.
     *
     * @param string $table The table to fetch the values from.
     * @param int $id The id defining the row to fetch.
     * @param array $fields The fields to fetch, might be empty.
     * @param bool $cast Whether to cast the result according to the database field types.
     *
     * @return array
     */
    public function fetch(string $table, int $id, array $fields = array(), bool $cast = true): array
    {
        return $this->fetchWithWhere(
            $table,
            $this->getPrimaryKey($table) . ' = ' . $this->sqlval($id),
            $fields,
            $cast
        );
    }

    /**
     * Fetch the first row returned from the database for the given where clause.
     * The fields to fetch can be limited with the fields array, if ommitted all fields will be fetched.
     * If $cast is true (default), the resulting values will be cast according to the database field types.
     *
     * @param string $table The table to fetch the values from.
     * @param string $where The where clause defining the row to fetch.
     * @param array $fields The fields to fetch, might be empty.
     * @param bool $cast Whether to cast the result according to the database field types.
     *
     * @return array
     */
    public function fetchWithWhere(string $table, string $where, array $fields = array(), bool $cast = true): array
    {
        $tableFields = $this->getFields($table);

        if (empty($fields))
        {
            $fields = array_column($tableFields, 'Field');
        }

        $sql = '
            SELECT ' . implode(', ', $this->sqlvalMultiple($fields, false)) . '
            FROM ' . $this->sqlval($table, false) . '
            WHERE ' . $where . '
        ';
        $data = $this->execute($sql, true);

        if ($cast)
        {
            return $this->castValues($tableFields, $data[0]);
        }

        return $data[0];
    }

    /**
     * Fetch multiple rows returned from the database for the given id.
     * The fields to fetch can be limited with the fields array, if ommitted all fields will be fetched.
     * If $cast is true (default), the resulting values will be cast according to the database field types.
     *
     * @param string $table The table to fetch the values from.
     * @param int $id The id defining the row to fetch.
     * @param array $fields The fields to fetch, might be empty.
     * @param bool $cast Whether to cast the result according to the database field types.
     *
     * @return array
     */
    public function fetchMultiple(string $table, int $id, array $fields = array(), bool $cast = true): array
    {
        return $this->fetchMultipleWithWhere(
            $table,
            $this->getPrimaryKey($table) . ' = ' . $this->sqlval($id),
            $fields,
            $cast
        );
    }

    /**
     * Fetch multiple rows returned from the database for the given where clause.
     * The fields to fetch can be limited with the fields array, if ommitted all fields will be fetched.
     * If $cast is true (default), the resulting values will be cast according to the database field types.
     *
     * @param string $table The table to fetch the values from.
     * @param string $where The where clause defining the row to fetch.
     * @param array $fields The fields to fetch, might be empty.
     * @param bool $cast Whether to cast the result according to the database field types.
     *
     * @return array
     */
    public function fetchMultipleWithWhere(string $table, string $where, array $fields = array(), bool $cast = true): array
    {
        $tableFields = $this->getFields($table);

        if (empty($fields))
        {
            $fields = array_column($tableFields, 'Field');
        }

        $sql = '
            SELECT ' . implode(', ', $this->sqlvalMultiple($fields, false)) . '
            FROM ' . $this->sqlval($table, false) . '
            WHERE ' . $where . '
        ';
        $data = $this->execute($sql, true);

        if ($cast)
        {
            return $this->castValuesMultiple($tableFields, $data);
        }

        return $data;
    }

    /**
     * Handles the MySQL queries.
     * If the query is a select, it returns an array if there is only one value, otherwise it
     * returns the value.
     * If the query is an update, replace or delete from, it returns the number of affected rows.
     * If the query is an insert, it returns the last insert id.
     *
     * @param string $sql         The SQL query to execute.
     * @param bool   $noTransform (default = false) if set to "true" the query function always returns
     *                            a multidimensional array
     * @param bool   $raw         Whether the result should return the raw mysqli result
     *
     * @return array|string|int|float
     */
    public function execute(string $sql, bool $noTransform = false, bool $raw = false)
    {
        $mysql = $this->connect();

        $trimmedSql = ltrim($sql);
        $res = $mysql->query($trimmedSql);

        if (!$res)
        {
            $debugger = new Debugger();
            $debugger->setErrorMessage(
                'Datenbank Fehler ' . $mysql->error . '<br /><br />' . $trimmedSql
            );
            $debugger->show();
        }

        if ($res || is_object($res))
        {
            if (substr($trimmedSql,0,6) == "SELECT" || substr($trimmedSql, 0, 4) == 'SHOW')
            {
                $out = array();

                if ($res->num_rows > 1 || ($noTransform && $res->num_rows > 0))
                {
                    if (method_exists('mysqli_result', 'fetch_all'))
                    {
                        $out = $res->fetch_all(MYSQLI_ASSOC);
                    }
                    else
                    {
                        while ($row = $res->fetch_assoc())
                        {
                            $out[] = $row;
                        }
                    }
                }
                elseif ($res->num_rows == 1 && !$noTransform)
                {
                    $out = $res->fetch_assoc();

                    if (count($out) == 1)
                    {
                        $out = current($out);
                    }
                }
                else
                {
                    $out = false;
                }

                return $out;
            }

            if (substr($trimmedSql,0,6) == "INSERT" && $noTransform == false)
            {
                return $mysql->insert_id;
            }
            elseif (substr($trimmedSql,0,6) == "INSERT" && $noTransform == true)
            {
                return $mysql->affected_rows;
            }

            if (substr($trimmedSql,0,6) == "UPDATE")
            {
                return $mysql->affected_rows;
            }

            if (substr($trimmedSql,0,7) == "REPLACE")
            {
                return $mysql->affected_rows;
            }

            if (substr($trimmedSql,0,11) == "DELETE FROM")
            {
                return $mysql->affected_rows;
            }

            if ($raw)
            {
                return $res;
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * Let the Transaction begin
     *
     * @return void
     */
    public function transactionBegin()
    {
        $this->execute("BEGIN");
    }

    /**
     * Save Changes on Database
     *
     * @return void
     */
    public function transactionCommit()
    {
        $this->execute("COMMIT");
    }

    /**
     * Rollback Changes
     *
     * @return void
     */
    public function transactionRollback()
    {
        $this->execute("ROLLBACK");
    }

    /**
     * Escapes and wraps the given value.
     *
     * @param mixed $value
     * @param bool  $wrap
     *
     * @return string
     */
    public function sqlval($value, bool $wrap = true): string
    {
        $mysql = $this->connect();

        $escapedString = '';

        if ($wrap)
        {
            $escapedString .= '"';
        }

        $escapedString .= $mysql->real_escape_string(strval($value));

        if ($wrap)
        {
            $escapedString .= '"';
        }

        return $escapedString;
    }

    /**
     * Handles the MySQL connection.
     * Should only be used in sqlval() and query()
     *
     * @return mysqli
     * @throws DatabaseException
     */
    protected function connect(): \mysqli
    {
        if (!is_object(self::$mysql))
        {
            $globalConfig = \SmartWork\GlobalConfig::getInstance();
            $dbConfig = $globalConfig->getGlobal('db');
            self::$mysql = new \mysqli($dbConfig['server'], $dbConfig['user'], $dbConfig['password']);

            if (self::$mysql->connect_error)
            {
                throw new DatabaseException(
                    'No database found. Please contact ' . $globalConfig->getGlobal(array('mail' => 'admin')) . '.',
                    9001
                );
            }
            else
            {
                self::$mysql->set_charset($dbConfig['charset']);
                self::$mysql->select_db($dbConfig['db']);
            }
        }

        $timezone = self::$mysql->query('SELECT @@session.time_zone');

        if ($timezone == 'SYSTEM')
        {
            self::$mysql->query('SET time_zone = "+00:00"');
        }

        return self::$mysql;
    }

    /**
     * Get the primary key field for the given table.
     * This will throw an exception if the primary key consists of multiple fields.
     *
     * @param string $table The table to fetch the primary key from.
     *
     * @return string
     * @throws DatabaseException
     */
    protected function getPrimaryKey(string $table): string
    {
        $sql = '
            SHOW KEYS FROM ' . $this->sqlval($table, false) . ' WHERE key_name = "PRIMARY"
        ';
        $data = $this->execute($sql, true);

        if (count($data) > 1)
        {
            throw new DatabaseException('Found multiple primary key fields for "' . $table . '".', 1001);
        }

        return $data[0]['Column_name'];
    }

    /**
     * Get all fields from the given table.
     *
     * @param string $table The table to get the fields from.
     *
     * @return array An array of fields from the table.
     */
    protected function getFields(string $table): array
    {
        $sql = '
            SHOW FIELDS FROM ' . $this->sqlval($table, false) . '
        ';

        return $this->execute($sql, true);
    }

    /**
     * Cast the values of the given rows into the corresponding scalar types defined in the type mapping.
     *
     * @param array $fields The tables fields.
     * @param array $rows An array of table rows.
     *
     * @return array
     */
    protected function castValuesMultiple(array $fields, array $rows): array
    {
        $result = array();

        foreach ($rows as $key => $row)
        {
            $result[$key] = $this->castValues($fields, $row);
        }

        return $result;
    }

    /**
     * Cast the values of the given array into the corresponding scalar types defined in the type mapping.
     *
     * @param array $fields The tables fields.
     * @param array $values The array of values to cast.
     *
     * @return array
     */
    protected function castValues(array $fields, array $values): array
    {
        foreach ($values as $key => &$value)
        {
            $fetchedFields = array_filter(
                $fields,
                function ($field) use ($key) {
                    return $field['Field'] == $key;
                }
            );
            $field = end($fetchedFields);
            settype($value, $this->getType($field));
        }

        return $values;
    }

    /**
     * Get the scalar php type for the field.
     *
     * @param array $field The database field.
     *
     * @return string The scalar type, this might be every type except array and object.
     */
    protected function getType(array $field): string
    {
        foreach ($this->typeMapping as $mysqlType => $phpType)
        {
            if (stripos($field['Type'], $mysqlType) !== false)
            {
                return $phpType;
            }
        }

        return 'string';
    }
}
