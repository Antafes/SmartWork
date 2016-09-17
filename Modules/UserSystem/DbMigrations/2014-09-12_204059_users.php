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
 * along with the SmartWork. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   SmartWork
 * @author    Marian Pollzien <map@wafriv.de>
 * @copyright (c) 2015, Marian Pollzien
 * @license   https://www.gnu.org/licenses/lgpl.html LGPLv3
 */

$DB_MIGRATION = array(

	'description' => function () {
		return 'User system';
	},

	'up' => function ($migration_metadata) {

		$results = array();

		$results[] = query_raw('
			CREATE TABLE `users` (
				`userId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`name` VARCHAR(255) NOT NULL COLLATE "utf8_general_ci",
				`password` VARCHAR(255) NOT NULL COLLATE "utf8_bin",
				`email` VARCHAR(255) NOT NULL COLLATE "utf8_bin",
				`active` TINYINT(1) NOT NULL DEFAULT "0",
				`admin` TINYINT(1) NOT NULL DEFAULT "0",
				`deleted` TINYINT(1) NOT NULL,
				PRIMARY KEY (`userId`)
			)
			COLLATE="utf8_bin"
			ENGINE=InnoDB
		');

		$results[] = query_raw('
			INSERT INTO `users` (`name`, `password`, `email`, `active`, `admin`)
			VALUES ("Admin", "$m5$sdgse5se$cb2bf6d82e1a5e5eaf78c78e74d8f018", "admin@localhost", 1, 1)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "username", "Benutzername", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (2, "username", "Username", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "password", "Passwort", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (2, "password", "Password", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "login", "Login", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (2, "login", "Login", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "register", "Registrieren", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (2, "register", "Register", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "invalidLogin", "Die eingegebenen Logindaten sind nicht bekannt.", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (2, "invalidLogin", "The entered login data are not known.", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "emptyLogin", "Bitte fülle alle Felder aus.", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (2, "emptyLogin", "Please fill in all fields.", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "logout", "Logout", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (2, "logout", "Logout", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "repeatPassword", "Passwort wiederholen", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (2, "repeatPassword", "Repeat password", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "registerEmpty", "Bitte fülle alle Felder aus.", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (2, "registerEmpty", "Pleas fill in all fields.", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "passwordsDontMatch", "Die eingegebenen Passwörter stimmen nicht überein.", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (2, "passwordsDontMatch", "The entered passwords don\'t match.", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "usernameAlreadyInUse", "Der Benutzername wird bereits verwendet.", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (2, "usernameAlreadyInUse", "The username is already in use.", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "registrationSuccessful", "Die Registrierung war erfolgreich.<br />Du erhältst eine E-Mail sobald du freigeschalten wurdest.", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (2, "registrationSuccessful", "The registration was successful.<br />You will receive an email on activation.", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "admin", "Admin", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (2, "admin", "Admin", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`) VALUES (1, "userId", "Benutzernummer")
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (2, "userId", "User number", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`) VALUES (1, "status", "Status")
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (2, "status", "Status", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`) VALUES (1, "activate", "aktivieren")
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (2, "activate", "activate", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`) VALUES (1, "removeAdmin", "entfernen")
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (2, "removeAdmin", "remove", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`) VALUES (1, "setAdmin", "setzen")
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (2, "setAdmin", "set", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`) VALUES (1, "active", "aktiv")
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (2, "active", "active", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "email", "Email", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "lostPassword", "Passwort vergessen?", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "retrievePassword", "Passwort anfordern", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "emailAlreadyInUse", "Die E-Mail-Adresse wird bereits verwendet.", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "lostPasswordSubject", "Passwort zurücksetzen", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "lostPasswordMessage", "<p>Hallo ##USER##,</p>\r\n<p>du hast ein neues Passwort angefordert.</p>\r\n<p>neues Passwort: ##PASSWORD##</p>\r\n<p>Grüße,</p>\r\n<p>das DSA Schmiede Team</p>", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "emptyEmail", "Es wurde keine E-Mail-Adresse eingegeben.", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "lostPasswordNoUserFound", "Es wurde kein Benutzer mit der angegebenen E-Mail-Adresse gefunden.", 0)
		');

		$results[] = query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "lostPasswordMailSent", "Es wurde eine E-Mail mit einem neuen Passwort verschickt.", 0)
		');

		return !in_array(false, $results);

	},

	'down' => function ($migration_metadata) {

		$result = array();

		$result[] = query_raw('
			DROP TABLE users
		');

        $results[] = query_raw('
			DELETE FROM `translations`
			WHERE `key` IN (
                "username", "password", "login", "register", "invalidLogin", "emptyLogin", "logout",
                "repeatPassword", "registerEmpty", "passwordsDontMatch", "usernameAlreadyInUse",
                "registrationSuccessful", "admin", "userId", "status", "activate", "removeAdmin",
                "setAdmin", "active", "email", "lostPassword", "retrievePassword", "emailAlreadyInUse",
                "lostPasswordSubject", "lostPasswordMessage", "emptyEmail", "lostPasswordNoUserFound",
                "lostPasswordMailSent"
            )
		');

		return !!$result;

	}

);