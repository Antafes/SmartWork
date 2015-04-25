<?php
/**
 * Part of the SmartWork framework.
 *
 * @package SmartWork
 * @author  friend8 <map@wafriv.de>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
namespace SmartWork;

/**
 * Template class which initializes Smarty and the translator.
 *
 * @package SmartWork
 * @author  friend8 <map@wafriv.de>
 * @license https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class Template
{
	/**
	 * @var \Smarty
	 */
	protected $smarty;

	/**
	 * @var string
	 */
	protected $template;

	/**
	 * @var \SmartWork\Translator
	 */
	protected $translator;

	/**
	 * Creates an instance of Smarty and a the translator.
	 * Also sets the template directories for Smarty.
	 */
	function __construct()
	{
		$this->smarty = new Smarty();
		$this->smarty->addTemplateDir(array(
			'templates/',
			__DIR__.'/../templates/',
		));

		if (file_exists(__DIR__.'/../plugins/'))
		{
			$this->smarty->addPluginsDir(__DIR__.'/../plugins/');
		}

		$translator = Translator::getInstance();
		$this->translator = $translator;
		$this->smarty->assign('translator', $translator);
		$this->smarty->assign('languages', $this->translator->getAllLanguages());
		$this->smarty->assign('currentLanguage', $this->translator->getCurrentLanguage());
	}

	/**
	 * Get the objects smarty instance.
	 *
	 * @return Smarty
	 */
	public function getSmarty()
	{
		return $this->smarty;
	}

	/**
	 * Set the template to use.
	 *
	 * @param string $template
	 */
	public function setTemplate($template)
	{
		$this->template = $template;
	}

	/**
	 * Assign a value with a name to the smarty instance.
	 * All values are pre processed by the translator.
	 *
	 * @param string $name
	 * @param string $value
	 *
	 * @return void
	 */
	public function assign($name, $value)
	{
		$this->smarty->assign($name, $this->translator->getTranslation($value));
	}

	/**
	 * Render the defined template
	 *
	 * @return void
	 */
	public function render()
	{
		echo $this->smarty->display($this->template.'.tpl');
		unset($_SESSION['scripts']);
	}

	/**
	 * Get the translator object.
	 *
	 * @return Translator
	 */
	public function getTranslator()
	{
		return $this->translator;
	}

	/**
	 * Load a js file in smarty.
	 * Use only the filename without ending.
	 *
	 * @param string $file
	 *
	 * @return void
	 */
	public function loadJs($file)
	{
		if (!is_array($_SESSION['scripts']['file']))
			$_SESSION['scripts']['file'] = array();

		if (!in_array($file, $_SESSION['scripts']['file']))
			$_SESSION['scripts']['file'][] = $file;
	}

    /**
	 * Load a js script in smarty.
	 *
	 * @param string $script Contains only the js!
	 *
	 * @return void
	 */
	public function loadJsScript($script)
	{
		$_SESSION['scripts']['script'][] = $script;
	}

	/**
	 * Load a js ready script in smarty.
	 *
	 * @param string $script Contains only the js!
	 *
	 * @return void
	 */
	public function loadJsReadyScript($script)
	{
		$_SESSION['scripts']['ready_script'][] = $script;
	}

	/**
	 * Load a css file in smarty.
	 * Use only the filename without ending.
	 *
	 * @param String $file
	 *
	 * @return void
	 */
	public function loadCss($file)
	{
		if (!is_array($_SESSION['css']['file']))
			$_SESSION['css']['file'] = array();

		if (!in_array($file, $_SESSION['css']['file']))
			$_SESSION['css']['file'][] = $file;
	}
}
