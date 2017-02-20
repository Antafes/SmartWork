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
 * @subpackage Helper
 * @author     Marian Pollzien <map@wafriv.de>
 * @copyright  (c) 2015, Marian Pollzien
 * @license    https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
namespace SmartWork\Helper;

/**
 * Helper class for handling mails.
 *
 * @package    SmartWork
 * @subpackage Helper
 * @author     Marian Pollzien <map@wafriv.de>
 * @license    https://www.gnu.org/licenses/lgpl.html LGPLv3
 */
class Mail
{
    /**
     * Send an email
     *
     * @param array  $recipient The recipient to send the mail to
     *                          Format:
     *                          array(
     *                              'test@example.org',
     *                              'test',
     *                          )
     * @param string $subject   The mail subjet
     * @param string $message   The mail message, may be html
     *
     * @return boolean
     */
    public static function send($recipient, $subject, $message)
    {
        $globalConfig = \SmartWork\GlobalConfig::getInstance();
        $translator = \SmartWork\Translator::getInstance();

        $mailer = new \PHPMailer(true);
        $mailer->set('CharSet', $globalConfig->getConfig('charset'));
        $sender = $globalConfig->getGlobal(array('mail' => 'sender'));
        $mailer->setFrom(
            $sender['mail'],
            $translator->gt('title')
        );
        $mailer->addAddress($recipient[0], $recipient[1]);
        $mailer->set('Subject', $subject);
        $mailer->set('AltBody', strip_tags($message));
        $mailer->msgHTML($message);
        $mailer->isHTML(true);

        return $mailer->send();
    }
}
