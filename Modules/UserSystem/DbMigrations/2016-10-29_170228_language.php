<?php
$DB_MIGRATION = array(
    'description' => function () {
        return 'Add language id field';
    },
    'up' => function ($migration_metadata) {
        $db = new SmartWork\Utility\DB();
        $results = array();

		$results[] = $db->query_raw('
            ALTER TABLE `users`
                ADD COLUMN `languageId` INT UNSIGNED NOT NULL AFTER `email`
		');

		$results[] = $db->query_raw('
			UPDATE users
			SET languageId = 1
		');

		$results[] = $db->query_raw('
            ALTER TABLE `users`
                ADD CONSTRAINT `userToLanguage` FOREIGN KEY (`languageId`) REFERENCES `languages` (`languageId`) ON UPDATE CASCADE ON DELETE CASCADE
		');

        return !in_array(false, $results);
    },
    'down' => function ($migration_metadata) {
        $db = new SmartWork\Utility\DB();
        $results = array();

		$results[] = $db->query_raw('
            ALTER TABLE `users`
                DROP COLUMN `languageId`,
                DROP FOREIGN KEY `userToLanguage`
		');

        return !in_array(false, $results);
    }
);
