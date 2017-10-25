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
 * along with Image Upload.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   SmartWork
 * @author    Marian Pollzien <map@wafriv.de>
 * @copyright (c) 2017, Marian Pollzien
 * @license   https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
namespace Classes\Test;

use SmartWork\ {
    BaseTest,
    GlobalConfig
};

/**
 * Description of GlobalConfigTest
 *
 * @author Marian Pollzien
 */
class GlobalConfigTest extends BaseTest
{
    /**
     * @var GlobalConfig
     */
    protected $globalConfig;
    
    protected function setUp()
    {
        parent::setUp();
        
        $this->globalConfig = GlobalConfig::getInstance();
    }

    public function testGetConfig()
    {
        $config = $this->globalConfig->getConfig('db');
        
        $this->assertInternalType('array', $config);
    }
}
