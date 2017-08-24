  CREATE TABLE `websites` (
	`id` VARCHAR(256) NOT NULL,
	`url` TEXT(2083) NOT NULL,
	`url_hash` VARCHAR(256) NOT NULL,
	`name` VARCHAR(50) NOT NULL UNIQUE,
	PRIMARY KEY (`id`)
);

CREATE TABLE `endpoints` (
	`id` VARCHAR(256) NOT NULL,
	`website_id` VARCHAR(256) NOT NULL,
	`name` VARCHAR(50) NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `selectors` (
	`id` VARCHAR(256) NOT NULL,
	`endpoint_id` VARCHAR(256) NOT NULL,
	`selector` VARCHAR(150) NOT NULL,
	`alias` VARCHAR(150) NOT NULL,
	PRIMARY KEY (`id`)
);

ALTER TABLE `endpoints` ADD CONSTRAINT `endpoints_fk0` FOREIGN KEY (`website_id`) REFERENCES `websites`(`id`);

ALTER TABLE `selectors` ADD CONSTRAINT `selectors_fk0` FOREIGN KEY (`endpoint_id`) REFERENCES `endpoints`(`id`);
