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
- [ ] User interface
- [ ] Optimization - load times and rendering performance
  - [x] Convert excel file to JSON
  - [ ] Save data inside database
  - [x] Set HTTP Cache

If something is unclear regarding the scope of the assignment, you can make reasonable assumptions.

## Step by step

1. Start write readme :)
2. In the second step, the Docker compose setup is done. So the launch of this project is easily possible. Docker contains three services `Nginx`, `PHP`, and `Redis`. A network to connect them. Docker file for PHP to install PHP extensions, composer, etc. Another Docker file for Nginx to customize and handle requests.
3. Install & setting up the Symfony framework + Twig and test-pack
4. The [PhpSpreadsheet](https://github.com/PHPOffice/PhpSpreadsheet) library is chosen to read the Excel file because it is significantly updated compared to the other options.
5. Analyzed excel data and create datalist-analyzed.xlsx
6. Add a class to save each serverInfo
7. Read Excel Data and save each item inside one instance object of serverInfo class
8. Add Import class to read excel and save inside JSON to improve performance. In the future we can add Redis or Memcached.
9. Add Reader class to open JSON file and load all records.
10. Add inherited class Filter extended from Server that apply and validate filters
11. Add controllers of /api/pricing and connect them to Reader class and /api/pricing/import to Import class
12. Handle get requests of /api/pricing and apply related filter on Reader class
13. Write PHPUnit Unit tests
14. Write PHPUnit Functional tests

15. to be continued...

## Run PHPUnit Test

Tests is categorized as Unit test and Functional Test. So you can run each one or all together.

### Run All tests

```php bin/phpunit```

### Run All Unit tests

```php bin/phpunit tests/Unit```

### Run All Functional tests

```php bin/phpunit tests/Functional```

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

try to run docker. it takes some minute to do everything. > 7 min for first run

```docker compose up --build```

If you wanna to run containers in the background use below command - detached mode

```docker compose up --build -d```

If you have problem with composer, you must find *CONTAINER ID* with `docker ps` command. Then run `docker exec -it 123`. Then you are inside container, so run `composer install`. now installation is done.

Open IP address or point some URL to server. For temporary usage below domain is connected.

- <https://symfony1.mradib.com/>
- <https://symfony1.mradib.com/api>
