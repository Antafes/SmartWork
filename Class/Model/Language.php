<?php
/**
 * Part of the SmartWork framework.
 *
 * @package    SmartWork
 * @subpackage Model
 * @author     friend8 <map@wafriv.de>
 * @license    https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
namespace SmartWork\Model;

/**
 * Model class for the languages used with SmartWork.
 *
 * @package    SmartWork
 * @subpackage Model
 * @author     friend8 <map@wafriv.de>
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
			WHERE iso2code = '.\sqlval($iso2code).'
		';
		$data = query($sql);

		$language = new self();
		$language->languageId = $data['languageId'];
		$language->language   = $data['language'];
		$language->iso2code   = $data['iso2code'];

		return $language;
	}

	/**
	 * Get a language by id.
	 *
	 * @param integer $id
	 *
	 * @return \self
	 */
	public static function loadById($id)
	{
		$sql = '
			SELECT languageId, language, iso2code
			FROM languages
			WHERE languageId = '.\sqlval($id).'
		';
		$data = query($sql);

		$language = new self();
		$language->languageId = intval($data['languageId']);
		$language->language   = $data['language'];
		$language->iso2code   = $data['iso2code'];

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

	/**
	 * Not used
	 *
	 * @return void
	 */
	public function getAsArray()
	{
	}
}
