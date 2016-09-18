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
 * @package    SmartWork
 * @subpackage Model
 * @author     Marian Pollzien <map@wafriv.de>
 * @copyright  (c) 2015, Marian Pollzien
 * @license    https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
namespace SmartWork\Model;
use \SmartWork\Utility\Database;

/**
 * Model class for the languages used with SmartWork.
 *
 * @package    SmartWork
 * @subpackage Model
 * @author     Marian Pollzien <map@wafriv.de>
 * @license    https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class Language extends \SmartWork\Model
{
    /**
     * @var integer
     */
    protected $languageId;

    /**
     * @var string
     */
    protected $language;

    /**
     * @var string
     */
    protected $iso2code;

    /**
     * Get a language by iso2code.
     *
     * @param string $iso2code
     *
     * @return \self
     */
    public static function getLanguageByIso2Code($iso2code)
    {
        $sql = '
            SELECT languageId, language, iso2code
            FROM languages
            WHERE iso2code = ' . Database::sqlval($iso2code) . '
        ';
        $data = Database::query($sql);
        $language = new self();
        $language->fill($data);

        return $language;
    }

    /**
     * Get a language by id.
     *
     * @param integer $id
     *
     * @return \self
     */
    public static function loadById(int $id)
    {
        $sql = '
            SELECT
                languageId,
                language,
                iso2code
            FROM languages
            WHERE languageId = ' . Database::sqlval($id) . '
        ';
        $data = Database::query($sql);
        $language = new self();
        $language->fill($data);

        return $language;
    }

    /**
     * Get the language id.
     *
     * @return integer
     */
    public function getLanguageId()
    {
        return $this->languageId;
    }

    /**
     * Get the language name.
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Get the ISO 2 code for the language.
     *
     * @return string
     */
    public function getIso2code()
    {
        return $this->iso2code;
    }
}
