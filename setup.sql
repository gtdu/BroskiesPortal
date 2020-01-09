CREATE TABLE `users` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL DEFAULT '',
    `email` varchar(255) NOT NULL DEFAULT '',
    `password` varchar(255) NOT NULL DEFAULT '',
    `password_reset` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
    `session_token` varchar(255) DEFAULT NULL,
    `core` int(1) NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `users` (`id`, `name`, `email`, `password`, `core`,)
VALUES
(1, 'TEST', 'admin@email.com', '$2y$10$Rb8cN1e8qpTdKHfGlwNkgu/sf/r439X61yKVtujZ0Gt2f747VMigW', 2);

CREATE TABLE `modules` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `api_token` varchar(255) NOT NULL DEFAULT '',
    `name` varchar(255) NOT NULL DEFAULT '',
    `root_url` varchar(255) NOT NULL DEFAULT '',
    `pem_name` varchar(255) NOT NULL DEFAULT '',
    `external` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `modules` (`id`, `api_token`, `name`, `root_url`, `pem_name`)
VALUES
	(1, '0', 'core', '/', 'core');
