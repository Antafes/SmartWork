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
 * Template class which initializes Smarty and the translator.
 *
 * @package SmartWork
 * @author  Marian Pollzien <map@wafriv.de>
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
        $this->smarty = new \Smarty();
        $this->smarty->addTemplateDir(array(
            'templates/',
            __DIR__.'/../templates/',
        ));
        $globalConfig = GlobalConfig::getInstance();

        if ($globalConfig->getConfig('useModules'))
        {
            foreach ($globalConfig->getConfig('modules') as $module)
            {
                $this->smarty->addTemplateDir(array(
                    $globalConfig->getConfig('dir_fs_system') . '/../Modules/' . $module . '/Templates/',
                    __DIR__ . '/../Modules/' . $module . '/Templates/',
                ));
            }
        }

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
    public function getSmarty(): \Smarty
    {
        return $this->smarty;
    }

    /**
     * Set the template to use.
     *
     * @param string $template
     *
     * @return void
     */
    public function setTemplate(string$template)
    {
        $this->template = $template;
    }

    /**
     * Assign a value with a name to the smarty instance.
     *
     * @param array|string $name
     * @param mixed        $value
     *
     * @return void
     */
    public function assign($name, $value = null)
    {
        $this->smarty->assign($name, $value);
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
    public function getTranslator(): Translator
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
    public function loadJs(string $file)
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
    public function loadJsScript(string $script)
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
    public function loadJsReadyScript(string $script)
    {
        $_SESSION['scripts']['ready_script'][] = $script;
    }

    /**
     * Load a css file in smarty.
     * Use only the filename without ending.
     *
     * @param string $file
     *
     * @return void
     */
    public function loadCss(string $file)
    {
        if (!is_array($_SESSION['css']['file']))
            $_SESSION['css']['file'] = array();

        if (!in_array($file, $_SESSION['css']['file']))
            $_SESSION['css']['file'][] = $file;
    }

    /**
     * Remove all stored JS and CSS files and inline scripts.
     *
     * @return void
     */
    public function clearJsAndCss()
    {
        $this->clearJs();
        $this->clearCss();
    }

    /**
     * Remove all stored JS files and inline scripts.
     *
     * @return void
     */
    public function clearJs()
    {
        unset($_SESSION['scripts']);
    }

    /**
     * Remove all stored CSS files.
     *
     * @return void
     */
    public function clearCss()
    {
        unset($_SESSION['css']);
    }
}
