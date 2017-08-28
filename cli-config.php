<?php
// cli-config.php
include ('web/bootstrap.php');

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);