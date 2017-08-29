  CREATE TABLE `websites` (
	`id` VARCHAR(255) NOT NULL,
	`url` TEXT(2083) NOT NULL,
	`url_hash` VARCHAR(255) NOT NULL,
	`name` VARCHAR(50) NOT NULL UNIQUE,
	PRIMARY KEY (`id`)
);

CREATE TABLE `endpoints` (
	`id` VARCHAR(255) NOT NULL,
	`website_id` VARCHAR(255) NOT NULL,
	`name` VARCHAR(50) NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `selectors` (
  `id` varchar(256) NOT NULL,
  `endpoint_id` varchar(256) NOT NULL,
  `selector` varchar(150) NOT NULL,
  `alias` varchar(150) NOT NULL,
  `type` enum('CSS','XPATH','REGEX') DEFAULT 'CSS',
  PRIMARY KEY (`id`),
  KEY `selectors_fk0` (`endpoint_id`),
  CONSTRAINT `selectors_fk0` FOREIGN KEY (`endpoint_id`) REFERENCES `endpoints` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `endpoints` ADD CONSTRAINT `endpoints_fk0` FOREIGN KEY (`website_id`) REFERENCES `websites`(`id`);

ALTER TABLE `selectors` ADD CONSTRAINT `selectors_fk0` FOREIGN KEY (`endpoint_id`) REFERENCES `endpoints`(`id`);
