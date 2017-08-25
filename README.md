# Team Michaelangelo

## Installation

### Dependencies
Install the dependencies via composer from the project root:

```bash
composer install
```

### Run webserver

#### Using PHP's internal webserver
Go inside the `web` directory and start PHP's internal webserver.

NOTE: this webserver is _single-threaded_ and is not for production!

```bash
cd web
php -S 0.0.0.0:8000
````

#### Using Docker
Use the helper-script in the project-directory to spin up the Docker-container

```bash
# run docker-compose
docker-compose -f docker-compose.dev.yml up

# using the helper script in the root of the project
./dev up
```

##### Change PHP-version
If you want to change the PHP-version to work with, edit the file `docker-compose.dev.yml`.
Comment out the line for the PHP-version you don't want and un-comment the line for
the PHP-version you do want. Then start the Docker-containers with one of the commands above
like `./dev up`.

Then install the database (ignore the errors)
```bash
./dev exec db mysql -u root -proot api < resources/schema.sql
./dev exec db mysql -u root -proot api < resources/fixtures.sql
```

### Run tests
There are functional tests to assure the correct working of the API.
These tests are created with behat.

Execute the tests with either the helper-script or with behat itself.
```bash
# use behat
cd tests/behat
../../vendor/bin/behat

# OR use the helper script in the root of the project
./dev test
```

## Minimal Viable Product

* ☒ API: retun static data
* ☒ parsing data from HTML
* ☐ make selectors dynamic: 
  * ☐ create an API-endpoint to store new endpoints for an application
  * ☐ get rid of hardcoded endpoints
  * ☐ retrieve and process endpoints from storage
* ☐ dynamic url: using the real websource instead of saved website-page

## Milestones

☒ API with 1 endpoint which provides hardcoded data
☒ Scrape data from website into storage
* Visually select HTML-element from target-website which contains relevant data

## Minimal Viable Learning 

* ☐ The coding-process
* ☐ Pair/peer-programming
* ☐ Understanding why doing decisions are made
* ☐ How to debug an API
* ☐ Kanban + / tasks / 
* ☐ scrum
* ☐ Agile - user stories with ...
* ☐ Sync front- and back-end
* ☐ IT-landscape
* ☐ understanding versioncontrol: github/gitlab/etc.

* ☐ mentoring / coaching
* ☐ pairprogramming
* ☐ setup an API from scratch
* ☐ gain further insights to develop an API from a website
