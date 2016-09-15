<?php
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
class Database
{
    /**
     * Static variable for holding the database connection.
     *
     * @var \mysqli
     */
    protected static $mysql;

    /**
     * handles the MySQL queries
     * if the query is a select, it returns an array if there is only one value, otherwise it returns the value
     * if the query is an update, replace or delete from, it returns the number of affected rows
     * if the query is an insert, it returns the last insert id
     *
     * @param string $sql
     * @param bool $noTransform (default = false) if set to "true" the query function always returns a multidimension array
     *
     * @return array|string|int|float
     */
    public static function query($sql, $noTransform = false, $raw = false)
    {
        global $debug, $firePHP_debug, $smarty_debug;

        $mysql = self::connect();

        $sql = ltrim($sql);
        if ($debug == true)
        {
            $res = $mysql->query($sql);
        }
        else
        {
            $res = @$mysql->query($sql);
        }

        if (!$res && $debug)
        {
            $backtrace = debug_backtrace();
            $html = '<br />Datenbank Fehler '.$mysql->error.'<br /><br />';
            $html .= $sql.'<br />';
            $html .= '<table>';
            foreach ($backtrace as $part)
            {
                $html .= '<tr><td width="100">';
                $html .= 'File: </td><td>'.$part['file'];
                $html .= ' in line '.$part['line'];
                $html .= '</td></tr><tr><td>';
                $html .= 'Function: </td><td>'.$part['function'];
                $html .= '</td></tr><tr><td>';
                $html .= 'Arguments: </td><td>';
                foreach ($part['args'] as $args)
                    $html .= $args.', ';
                $html = \substr($html, 0, -2);
                $html .= '</td></tr>';
            }
            $html .= '</table>';
            die($html);
        }

        if ($res || is_object($res))
        {
            if (substr($sql,0,6) == "SELECT" || substr($sql, 0, 4) == 'SHOW')
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

            if (substr($sql,0,6) == "INSERT" && $noTransform == false)
            {
                return $mysql->insert_id;
            }
            elseif (substr($sql,0,6) == "INSERT" && $noTransform == true)
            {
                return $mysql->affected_rows;
            }

            if (substr($sql,0,6) == "UPDATE")
            {
                return $mysql->affected_rows;
            }

            if (substr($sql,0,7) == "REPLACE")
            {
                return $mysql->affected_rows;
            }

            if (substr($sql,0,11) == "DELETE FROM")
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
     * See query()
     *
     * @param string $sql
     *
     * @return mixed
     */
    public static function query_raw($sql)
    {
        return self::query($sql, false, true);
    }

    /**
     * Let the Transaction begin
     *
     * @return void
     */
    public static function transactionBegin()
    {
        self::query("BEGIN");
    }

    /**
     * Save Changes on Database
     *
     * @return void
     */
    public static function transactionCommit()
    {
        self::query("COMMIT");
    }

    /**
     * Rollback Changes
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
     *
     * @return String
     */
    public static function sqlval($value, $wrap = true)
    {
        $mysql = self::connect();

        if (is_array($value))
        {
            foreach ($value as &$row)
            {
                $row = sqlval($row, $wrap);
            }
            unset($row);

            return $value;
        }
        else
        {
            $escapedString = '';

            if ($wrap)
            {
                $escapedString .= '"';
            }

            $escapedString .= $mysql->real_escape_string($value);

            if ($wrap)
            {
                $escapedString .= '"';
            }

            return $escapedString;
        }
    }

    /**
     * Handles the MySQL connection.
     * Should only be used in sqlval() and query()
     *
     * @return mysqli
     */
    protected static function connect()
    {
        if (!is_object(self::$mysql))
        {
            $globalConfig = \SmartWork\GlobalConfig::getInstance();
            $dbConfig = $globalConfig->getGlobal('db');
            self::$mysql = new \mysqli($dbConfig['server'], $dbConfig['user'], $dbConfig['password']);

            if (self::$mysql->connect_error)
            {
                echo 'No database found. Please contact <br /><a href=\"mailto:admin@dynasty-wars.de\">admin@wafriv.de</a>';
                exit;
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
}
