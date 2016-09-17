<?php
$DB_MIGRATION = array(
    'description' => function () {
        return 'Imprint';
    },
    'up' => function ($migration_metadata) {
        $results = array();

		$results[] = SmartWork\Utility\Database::query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (1, "imprint", "Impressum", 0)
		');

		$results[] = SmartWork\Utility\Database::query_raw('
			INSERT INTO `translations` (`languageId`, `key`, `value`, `deleted`) VALUES (2, "imprint", "Imprint", 0)
		');

        return !in_array(false, $results);
    },
    'down' => function ($migration_metadata) {
        $results = array();

        $results[] = \SmartWork\Utility\Database::query_raw('
            DELETE FROM translations WHERE key = "imprint"
        ');

        return !in_array(false, $results);
    }
);
