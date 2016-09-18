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
        return 'Translation tables';
    },

    'up' => function ($migration_metadata) {

        $results = array();

        $results[] = query_raw('
            CREATE TABLE languages (
                languageId INT UNSIGNED NOT NULL AUTO_INCREMENT,
                language VARCHAR(255) NOT NULL COLLATE "utf8_general_ci",
                iso2code CHAR(2) NOT NULL COLLATE "utf8_bin",
                deleted TINYINT(1) NOT NULL,
                PRIMARY KEY (`languageId`)
            )
            COLLATE="utf8_bin"
            ENGINE=InnoDB
        ');

        $results[] = query_raw('
            CREATE TABLE `translations` (
                `translationId` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `languageId` INT(10) UNSIGNED NOT NULL,
                `key` VARCHAR(255) NOT NULL COLLATE "utf8_general_ci",
                `value` TEXT NOT NULL COLLATE "utf8_general_ci",
                `deleted` TINYINT(1) NOT NULL,
                PRIMARY KEY (`translationId`),
                INDEX `translation_language` (`languageId`),
                CONSTRAINT `translation_language` FOREIGN KEY (`languageId`) REFERENCES `languages` (`languageId`)
            )
            COLLATE="utf8_bin"
            ENGINE=InnoDB
        ');

        $results[] = query_raw('
            INSERT INTO languages (languageId, language, iso2code)
            VALUES (1, "german", "de"), (2, "english", "en")
        ');

        $results[] = query_raw('
            INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "german", "Deutsch", 0)
        ');

        $results[] = query_raw('
            INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (2, "german", "Deutsch", 0)
        ');

        $results[] = query_raw('
            INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "english", "English", 0)
        ');

        $results[] = query_raw('
            INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (2, "english", "English", 0)
        ');

        $results[] = query_raw('
            INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "index", "Startseite", 0)
        ');

        $results[] = query_raw('
            INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (2, "index", "Index", 0)
        ');

        return !in_array(false, $results);

    },

    'down' => function ($migration_metadata) {

        $result = array();

        $result[] = query_raw('
            DROP TABLE translations
        ');

        $result[] = query_raw('
            DROP TABLE languages
        ');

        return !!$result;

    }

);