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

If you want to see the version of PHP you're currently using in the PHP-docker container,
you can issue the following command:
```bash
./dev exec php php -v

PHP 7.1.8-2+ubuntu16.04.1+deb.sury.org+4 (cli) (built: Aug  4 2017 13:04:12) ( NTS )
Copyright (c) 1997-2017 The PHP Group
Zend Engine v3.1.0, Copyright (c) 1998-2017 Zend Technologies
    with Zend OPcache v7.1.8-2+ubuntu16.04.1+deb.sury.org+4, Copyright (c) 1999-2017, by Zend Technologies
    with Xdebug v2.5.5, Copyright (c) 2002-2017, by Derick Rethans
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
* ☒ make selectors dynamic: 
  * ☒ create an API-endpoint to store new endpoints for an application
  * ☒ get rid of hardcoded endpoints
  * ☒ retrieve and process endpoints from storage
* ☒ dynamic url: using the real websource instead of saved website-page

## Milestones

☒ API with 1 endpoint which provides hardcoded data
☒ Scrape data from website into storage
* Visually select HTML-element from target-website which contains relevant data

## Minimal Viable Learning 

* ☒ The coding-process
* ☒ Pair/peer-programming
* ☒ Understanding why doing decisions are made
* ☒ How to debug an API
* ☐ Kanban + / tasks / 
* ☐ scrum
* ☐ Agile - user stories with ...
* ☐ Sync front- and back-end
* ☒ IT-landscape
* ☒ understanding versioncontrol: github/gitlab/etc.

* ☒ mentoring / coaching
* ☒ pairprogramming
* ☒ setup an API from scratch
* ☒ gain further insights to develop an API from a website


## Usage

### Create call

In order to create a new call, specify the following data:
- website name (will be used in the route)
- website URL (will be used to scrape the information from)
- (one or more) endpoint name (will be used in the url)
- (one or more) selector. Should be a CSS selector to select the element(s) of the HTML source of the website you wish to be returned
- (one or more) alias. This is the key which will be used to return the content of the above mentioned element

On successful creation, an ID should be returned to you, which can be used to update or delete the call.
![Create call](web/images/usage_create_call.png)
 
### List call

To call the data you created in the create call, simply use the website name and endpoint name in your url as such:
<website_name>/<end_point_name>

![list call](web/images/usage_list_call.png) 

### Search call
You can search inside one of the specified keys for a certain value. It will also search for partial matches.
To do this specify your route as such: <website_name>/<end_point>/search/<key>/<query>

![Search call](web/images/usage_search.png)

### Delete call

To delete a call, simply call the following url: delete/<id>

The ID is returned upon creation.

![Delete call](web/images/usage_delete_call.png)