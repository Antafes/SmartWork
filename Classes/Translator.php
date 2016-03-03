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
	 * Constructor
	 */
	private function __construct()
	{
		$this->fillLanguages();
		$this->fillTranslations();
	}

	/**
	 * Get the singleton instance.
	 *
	 * @return Translator
	 */
	public static function getInstance()
	{
		if (!self::$translator)
		{
			$translator = new self();
			$translator->setCurrentLanguage($_COOKIE['esLanguage']);
			self::$translator = $translator;
		}

		return self::$translator;
	}

	/**
	 * Add a translation to the database and the translator.
	 *
	 * @param integer $language
	 * @param string  $key
	 * @param string  $value
	 *
	 * @return void
	 */
	public function addTranslation($language, $key, $value)
	{
		if ($this->translations[$language][$key])
		{
			return;
		}

		$sql = '
			INSERT INTO translations
			SET languageId = '.\sqlval($language).',
				`key` = '.\sqlval($key).',
				`value` = '.\sqlval($value).'
		';
		$translationId = query($sql);

		if ($translationId)
		{
			$this->translations[$language][$key] = $value;
		}
	}

	/**
	 * Fetches all languages and adds them as EsModelLanguage to the language list.
	 *
	 * @return void
	 */
	protected function fillLanguages()
	{
		$sql = '
			SELECT languageId
			FROM languages
			WHERE !deleted
		';
		$languages = query($sql, true);

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
		/* @var EsModelLanguage $language */
		foreach ($this->languages as $language)
		{
			$sql = '
				SELECT `key`, `value`
				FROM translations
				WHERE languageId = '.\sqlval($language->getLanguageId()).'
					AND !deleted
			';
			$translations = query($sql, true);

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
	public function getAllLanguages()
	{
		return $this->languages;
	}

	/**
	 * Get the id of the users language.
	 *
	 * @return integer
	 */
	public function getCurrentLanguage()
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
	 * @param integer $currentLanguage
	 *
	 * @return void
	 */
	public function setCurrentLanguage($currentLanguage)
	{
		$this->currentLanguage = $currentLanguage;
		$this->setUserLanguage($this->currentLanguage);
	}

    /**
     * Get the language of the user as object
     *
     * @return \SmartWork\Model\Language
     */
    public function getCurrentLanguageObject()
    {
        $languageId = $this->getCurrentLanguage();

        return $this->languages[$languageId];
    }

	/**
	 * Get the translated name for the users language
	 *
	 * @return string
	 */
	public function getCurrentLanguageName()
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
	 * @return mixed
	 */
	public function gt($key, $arguments = array())
	{
		if (is_object($key) || is_bool($key) || is_float($key))
			return $key;

		if (is_array($key))
		{
			foreach ($key as &$item)
				$item = $this->gt($item);

			return $key;
		}

		if (array_key_exists($key, $this->translations[$this->currentLanguage]))
			return $this->replace($this->translations[$this->currentLanguage][$key], $arguments);
		else
			return $key;
	}

	/**
	 * Replace placeholder with the given arguments.
	 *
	 * @param string $translation
	 * @param array  $arguments
	 *
	 * @return string
	 */
	protected function replace($translation, $arguments)
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
	 * @return integer
	 */
	protected function getUserLanguage()
	{
		$languageId = $_COOKIE['esLanguage'];

		if (!$languageId)
		{
			$iso2code   = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
			$language   = Model\Language::getLanguageByIso2Code($iso2code);
			$languageId = $language->getLanguageId();
		}

		return $languageId;
	}

	/**
	 * Set the logged in user language.
	 *
	 * @param integer $languageId
	 *
	 * @return void
	 */
	protected function setUserLanguage($languageId)
	{
		setcookie('esLanguage', $languageId, time() + 86400);
	}

	/**
	 * Get all translations as an array.
	 *
	 * @return array
	 */
	public function getAsArray()
	{
		return $this->translations[$this->getCurrentLanguage()];
	}
}
