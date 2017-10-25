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
 * @package   SmartWork
 * @author    Marian Pollzien <map@wafriv.de>
 * @copyright (c) 2015, Marian Pollzien
 * @license   https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
namespace SmartWork;

/**
 * Translator class
 *
 * @package SmartWork
 * @author  Marian Pollzien <map@wafriv.de>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class Translator
{
    /**
     * Singleton instance of the translator
     *
     * @var \self
     */
    protected static $translator;

    /**
     * An array of Language objects.
     *
     * @var array
     */
    protected $languages;

    /**
     * The id of the current language.
     *
     * @var integer
     */
    protected $currentLanguage;

    /**
     * @var array
     */
    protected $translations;

    /**
     * @var Utility\DB
     */
    protected $db;

    /**
     * Constructor
     */
    private function __construct()
    {
        $this->db = new Utility\DB();
        $this->fillLanguages();
        $this->fillTranslations();
    }

    /**
     * Get the singleton instance.
     *
     * @return \self
     */
    public static function getInstance(): self
    {
        if (!self::$translator)
        {
            $translator = new self();
            // This is called to automatically fetch the current language.
            $translator->getCurrentLanguage();
            self::$translator = $translator;
        }

        return self::$translator;
    }

    /**
     * Add a translation to the database and the translator.
     *
     * @param int    $language
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    public function addTranslation(int $language, string $key, string $value)
    {
        if ($this->translations[$language][$key])
        {
            return;
        }

        $sql = '
            INSERT INTO translations
            SET languageId = ' . $this->db->sqlval($language) . ',
                `key` = ' . $this->db->sqlval($key) . ',
                `value` = ' . $this->db->sqlval($value) . '
        ';
        $translationId = $this->db->execute($sql);

        if ($translationId)
        {
            $this->translations[$language][$key] = $value;
        }
    }

    /**
     * Fetches all languages and adds them as \SmartWork\Model\Language to the
     * language list.
     *
     * @return void
     */
    protected function fillLanguages()
    {
        $languages = $this->db->fetchMultipleWithWhere('languages', '!deleted', array('languageId'));

        foreach ($languages as $language)
        {
            $this->languages[$language['languageId']] = \SmartWork\Model\Language::loadById(
                $language['languageId']
            );
        }
    }

    /**
     * Fetches all translations and adds them to the translation list.
     *
     * @return void
     */
    protected function fillTranslations()
    {
        /* @var \SmartWork\Model\Language $language */
        foreach ($this->languages as $language)
        {
            $sql = '
                SELECT `key`, `value`
                FROM translations
                WHERE languageId = ' . $this->db->sqlval($language->getLanguageId()) . '
                    AND !deleted
            ';
            $translations = $this->db->execute($sql, true);

            foreach ($translations as $translation)
            {
                $this->translations[$language->getLanguageId()][$translation['key']]
                    = $translation['value'];
            }
        }
    }

    /**
     * Get all fetched languages.
     *
     * @return array
     */
    public function getAllLanguages(): array
    {
        return $this->languages;
    }

    /**
     * Get the id of the users language.
     *
     * @return int
     */
    public function getCurrentLanguage(): int
    {
        if (!$this->currentLanguage)
        {
            $this->currentLanguage = $this->getUserLanguage();
            $this->setUserLanguage($this->currentLanguage);
        }

        return $this->currentLanguage;
    }

    /**
     * Set the language of the user
     *
     * @param int $currentLanguage
     *
     * @return void
     */
    public function setCurrentLanguage(int $currentLanguage)
    {
        $this->currentLanguage = $currentLanguage;
        $this->setUserLanguage($this->currentLanguage);
    }

    /**
     * Get the language of the user as object
     *
     * @return \SmartWork\Model\Language
     */
    public function getCurrentLanguageObject(): Model\Language
    {
        $languageId = $this->getCurrentLanguage();

        return $this->languages[$languageId];
    }

    /**
     * Get the translated name for the users language
     *
     * @return string
     */
    public function getCurrentLanguageName(): string
    {
        return $this->gt($this->languages[$this->currentLanguage]->getLanguage());
    }

    /**
     * Get the translation for the given key. If there is no translation available, the key is
     * returned.
     *
     * @deprecated Removed in two versions.
     * @param string $key
     *
     * @return mixed
     */
    public function getTranslation($key)
    {
        return $this->gt($key);
    }

    /**
     * Get the translation for the given key. If there is no translation available, the key is
     * returned.
     *
     * @param string $key
     * @param array  $arguments
     *
     * @return string
     */
    public function gt(string $key, array $arguments = array()): string
    {
        if (is_object($key) || is_bool($key) || is_float($key))
        {
            return $key;
        }

        if (is_array($key))
        {
            foreach ($key as &$item)
            {
                $item = $this->gt($item);
            }

            return $key;
        }

        if (array_key_exists($key, $this->translations[$this->currentLanguage]))
        {
            return $this->replace($this->translations[$this->currentLanguage][$key], $arguments);
        }
        else
        {
            return $key;
        }
    }

    /**
     * Replace placeholder with the given arguments.
     *
     * @param string $translation
     * @param array  $arguments
     *
     * @return string
     */
    protected function replace(string $translation, array $arguments): string
    {
        if (!$arguments)
        {
            return $translation;
        }

        $needle = array_map(
            function ($entry) {
                return '##'.strtoupper($entry).'##';
            },
            array_keys($arguments)
        );

        return str_replace($needle, $arguments, $translation);
    }

    /**
     * Get the logged in users language id.
     *
     * @return int
     */
    protected function getUserLanguage(): int
    {
        $languageId = $_COOKIE['language'];

        if (!$languageId)
        {
            $iso2code   = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            $language   = Model\Language::getLanguageByIso2Code($iso2code);
            $languageId = $language->getLanguageId();
        }

        return (int) $languageId;
    }

    /**
     * Set the logged in user language.
     *
     * @param int $languageId
     *
     * @return void
     */
    protected function setUserLanguage(int $languageId)
    {
        setcookie('language', $languageId, time() + 86400);
    }

    /**
     * Get all translations as an array.
     *
     * @return array
     */
    public function getAsArray(): array
    {
        return $this->translations[$this->getCurrentLanguage()];
    }
}
