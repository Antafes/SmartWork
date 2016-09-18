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
 * along with SmartWork.
 * If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   SmartWork
 * @author    Marian Pollzien <map@wafriv.de>
 * @copyright (c) 2016, Marian Pollzien
 * @license   https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
namespace SmartWork\Imprint\Page;

/**
 * Description of Imprint
 *
 * @author Marian Pollzien <map@wafriv.de>
 */
class Imprint extends \SmartWork\Page
{
    /**
     * Constructor for the page class.
     * Sets the template to use.
     */
    public function __construct()
    {
        parent::__construct('imprint');
    }

    /**
     * Fetches the configured imprint entries and assigns them to the template.
     *
     * @return void
     */
    public function process()
    {
        $this->getTemplate()->assign(
            'imprints', \SmartWork\GlobalConfig::getInstance()->getConfig('imprint')
        );
    }
}
