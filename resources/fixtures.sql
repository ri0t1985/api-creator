INSERT INTO `websites` (`id`, `url`, `url_hash`, `name`)
VALUES
	('5d21bd48-88c3-11e7-a49b-00e04c68128f', 'http://wedstrijdprogramma.com/wedstrijden.php?referer=www.vuc.nl&amp;week=0|1|2|3&amp;sorteer=[2]%20Senioren%20zaterdag&amp;team=', '32108fa9500f5a3ce53b891b87b40257', 'soccer');

INSERT INTO `endpoints` (`id`, `website_id`, `name`)
VALUES
	('95464acc-88c3-11e7-a49b-00e04c68128f', '5d21bd48-88c3-11e7-a49b-00e04c68128f', 'matches');


INSERT INTO `selectors` (`id`, `endpoint_id`, `selector`, `alias`)
VALUES
	('39d3adfa-88c4-11e7-a49b-00e04c68128f', '95464acc-88c3-11e7-a49b-00e04c68128f', 'td._RGPO__Datum', 'date'),
	('39d3b0c0-88c4-11e7-a49b-00e04c68128f', '95464acc-88c3-11e7-a49b-00e04c68128f', 'td._RGPO__Aanvang', 'match_start'),
	('39d3b19c-88c4-11e7-a49b-00e04c68128f', '95464acc-88c3-11e7-a49b-00e04c68128f', 'td._RGPO__TeamT', 'home_team'),
	('39d3b1f6-88c4-11e7-a49b-00e04c68128f', '95464acc-88c3-11e7-a49b-00e04c68128f', 'td._RGPO__TeamU', 'visiting_team'),
	('39d3b232-88c4-11e7-a49b-00e04c68128f', '95464acc-88c3-11e7-a49b-00e04c68128f', 'td._RGPO__Scheidsrechter', 'referee'),
	('39d3b26e-88c4-11e7-a49b-00e04c68128f', '95464acc-88c3-11e7-a49b-00e04c68128f', 'td._RGPO__Info', 'info');
