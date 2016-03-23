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
namespace SmartWork;

/**
 * Basic list class.
 *
 * @package SmartWork
 * @author  Marian Pollzien <map@wafriv.de>
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
    public function getList(): array
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
    public function setList(array $list)
    {
        $this->list = $list;
    }

    /**
     * Get a model by its id.
     *
     * @param int $id
     *
     * @return \SmartWork\Model
     */
    public abstract function getById(int $id);

    /**
     * Get a list of arrays from the loaded models.
     *
     * @return array
     */
    public function getAsArray(): array
    {
        $list = array();
        foreach ($this->list as $row)
        {
            $list[] = $row->getAsArray();
        }

        return $list;
    }
}
