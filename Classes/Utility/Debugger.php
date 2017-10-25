<?php
declare(strict_types=1);
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

namespace SmartWork\Utility;

/**
 * Description of Debugger
 *
 * @package    SmartWork
 * @subpackage Utility
 * @author     Marian Pollzien <map@wafriv.de>
 */
class Debugger
{
    /**
     * The error message.
     *
     * @var string
     */
    protected $errorMessage;

    /**
     * Set the error message.
     *
     * @param string $errorMessage
     *
     * @return void
     */
    function setErrorMessage(string $errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * Show the error message and the backtrace.
     *
     * @return string
     */
    public function show()
    {
        $globalConfig = \SmartWork\GlobalConfig::getInstance();
        $debug = $globalConfig->getConfig('debug');

        if ($debug)
        {
            $html = <<<HTML
<br />$this->errorMessage<br />
<table>
    $this->getBacktrace()
</table>
HTML;
        }
        else
        {
            $adminMail = $globalConfig->getGlobal(array('mail' => 'admin'));
            $html = <<<HTML
<br />An error occured during execution of this website.<br/>
Please wait a moment and retry or write to <a href="mailto:{$adminMail['mail']}">{$adminMail['mail']}</a>
HTML;
        }

        die($html);
    }

    /**
     * Get the backtrace as preformatted html.
     *
     * @return string
     */
    protected function getBacktrace(): string
    {
        $backtrace = debug_backtrace();
        $html = '';

        foreach ($backtrace as $part)
        {
            $html .= <<<HTML
<tr>
<td width="100">
    File:
</td>
<td>
    {$part['file']} in line {$part['line']}
</td>
</tr>
<tr>
<td>
    Function:
</td>
<td>
    {$part['function']}
</td>
</tr>
<tr>
<td>
    Arguments:
</td>
<td>
HTML;

            foreach ($part['args'] as $args)
            {
                $html .= $args.', ';
            }

            $html = \substr($html, 0, -2);
            $html .= '</td></tr>';
        }

        return $html;
    }
}
