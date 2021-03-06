CREATE TABLE IF NOT EXISTS `websites` (
	`id` VARCHAR(255) NOT NULL,
	`url` TEXT(2083) NOT NULL,
	`url_hash` VARCHAR(255) NOT NULL,
	`name` VARCHAR(50) NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `endpoints` (
	`id` VARCHAR(255) NOT NULL,
	`website_id` VARCHAR(255) NOT NULL,
	`name` VARCHAR(50) NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `selectors` (
  `id` varchar(255) NOT NULL,
  `endpoint_id` varchar(255) NOT NULL,
  `selector` varchar(150) NOT NULL,
  `alias` varchar(150) NOT NULL,
  `type` enum('CSS','XPATH','REGEX') DEFAULT 'CSS',
  PRIMARY KEY (`id`),
  KEY `selectors_fk0` (`endpoint_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `selector_options` (
	`id` varchar(255) NOT NULL,
	`selector_id` varchar(255) NOT NULL,
	`key` varchar(255) NOT NULL,
	`value` varchar(255) NOT NULL,
	PRIMARY KEY (`id`)
);

ALTER TABLE `selector_options` ADD CONSTRAINT `selector_options_fk0` FOREIGN KEY (`selector_id`) REFERENCES `selectors`(`id`);

ALTER TABLE `endpoints` ADD CONSTRAINT `endpoints_fk0` FOREIGN KEY (`website_id`) REFERENCES `websites`(`id`);

ALTER TABLE `selectors` ADD CONSTRAINT `selectors_fk0` FOREIGN KEY (`endpoint_id`) REFERENCES `endpoints`(`id`);
