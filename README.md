# Symfony Sample Pricing API

This is a simple program that uses an Excel file as a data source and presents the data as an API with filtering capabilities. There is no plan to place this in a database in the near future.

## Requirements

- [x] Unit test
- [x] Functional Test
- [x] A back-end!
- [x] API

## Bonus points

- [x] Maintainable
- [x] Code quality
- [x] Application structure
- [x] [User interface - Seperated respository used as Git submodule](https://github.com/MrJavadAdib/symfony-sample-pricing-ui)
- [x] Optimization - load times and rendering performance

If something is unclear regarding the scope of the assignment, you can make reasonable assumptions.

## Run PHPUnit Test

Tests is categorized as Unit test and Functional Test. So you can run each one or all together.

### Run All tests

```php bin/phpunit```

![PHPUnit Tests](documents/phpunit-tests.jpg?raw=true|width=400px "PHPUnit Tests")

### Run Unit tests

```php bin/phpunit tests/Unit```

![PHPUnit Tests](documents/phpunit-tests-unit.jpg?raw=true|width=400px "PHPUnit Tests")

### Run Functional tests

```php bin/phpunit tests/Functional```

![PHPUnit Tests](documents/phpunit-tests-functional.jpg?raw=true|width=400px "PHPUnit Tests")

## Install Dependencies - Docker & git

First, update your existing list of packages

```sudo apt update```

Next, install a few prerequisite packages which let apt use packages over HTTPS

```sudo apt install apt-transport-https ca-certificates curl software-properties-common```

Then add the GPG key for the official Docker repository to your system

```curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg```

Add the Docker repository to APT sources

```echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null```

Update your existing list of packages again for the addition to be recognized

```sudo apt update```

Make sure you are about to install from the Docker repo instead of the default Ubuntu repo

```apt-cache policy docker-ce```

Finally, install Docker, Docker compose and Git

```sudo apt install -y docker-ce docker-compose-plugin git```

## Install Project - Clone git and run via docker compose

Clone the repository somewhere with below command.

```git clone https://github.com/MrJavadAdib/symfony-sample-pricing.git```

Go to cloned folder

```cd symfony-sample-pricing```

update submodules, like frontend with below command

```git submodule update --init --recursive```

Before running docker you need to config env to set mysql password

- copy `.env.dev` file on root to `.env` and update password of mysql
- add new file `src/backend/.env.local` and set mysql connection. simply update password

```DATABASE_URL="mysql://root:__PUT_MYSQL_PASS_HERE__@mysql:3306/servers?serverVersion=5.7&charset=utf8mb4"```

Try to run docker. it takes some minute to do everything. `-d` for detached mode.

```docker compose up --build -d```

If you have problem with composer, you must find *CONTAINER ID* with `docker ps` command. Then run `docker exec -it 123 sh`. Then you are inside container, go to backend folder with `cd backend`, so run `composer install`. now installation is done.

Also you need to run symfony migrate command to create database for the first time with below commands

```php bin/console doctrine:database:create```

Then run migrate

```php bin/console doctrine:migrations:migrate```

Finally, open IP address or point some URL to server. For temporary usage below domain is connected.

- <https://symfony1.mradib.com/>
- <https://symfony1.mradib.com/api>

## Third Party Library

The [PhpSpreadsheet](https://github.com/PHPOffice/PhpSpreadsheet) library is chosen to read the Excel file because it is significantly updated compared to the other options.


## Checklist

### Backend Checklist

- [x] write readme
- [x] write installation manual
- [x] implement a class to read from Excel - OOP
- [x] import data to database
- [x] empty table before new import
- [x] add index to search fields
- [x] extract important data from values and save inside new fields
- [x] check exactly duplicate records and only insert once - 13 duplicate in example
- [x] check duplicate record without price and only insert once - 277 duplicate in example
- [x] allow access to import database after change Excel file - route via /import url
- [x] route url for API to show result
- [x] successfully show list of servers
- [x] unit test
- [x] functional test
- [x] allow to filter single values like datacenter, brand, storagetype, ram
- [x] allow to filter multiple values like datacenter, brand, storagetype, ram
- [x] allow to filter range values like price, storage, ram
- [x] allow to filter all fileds together
- [x] allow to sort output based on range values
- [x] enable http cache to improve performance
- [x] return list of each filter with count of it via API

### Frontend Checklist

- [x] choose layout of page
- [x] add header
- [x] add range slider
- [x] add radio
- [x] add checkbox
- [x] add dropdown
- [x] add order by
- [x] design of each server
- [x] responsive design
- [x] load init data
- [x] design empty state
- [x] design filter without result state
- [x] design loading state
- [x] fetch servers data from API
- [x] apply filters and get updated data
- [x] get list of filters from API
- [x] don't send request if user play with filters
- [x] cancel last request if user changes filters
- [x] add link to API page
- [x] add link to MrAdib.com
- [ ] show count of each filter for radio and checkbox
- [ ] improve design of locations
- [x] get Lighthouse 100 score
- [ ] change url params based on filters
- [ ] read init state from url params

![UI](documents/ui.gif?raw=true "UI")
