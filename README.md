# Symfony Sample Pricing API

This is a simple program that uses an Excel file as a data source and presents the data as an API with filtering capabilities. There is no plan to place this in a database in the near future.

## Requirements

- Unit test
- Functional Test
- A back-end!
- API

## Bonus points

- Maintainable
- Code quality
- Application structure
- User interface
- Optimization - load times and rendering performance

If something is unclear regarding the scope of the assignment, you can make reasonable assumptions.

## Step by step

1. Start write readme :)
2. In the second step, the Docker compose setup is done. So the launch of this project is easily possible. Docker contains three services `Nginx`, `PHP`, and `Redis`. A network to connect them. Docker file for PHP to install PHP extensions, composer, etc. Another Docker file for Nginx to customize and handle requests.
3. Install & setting up the Symfony framework + Twig and test-pack
4. The [PhpSpreadsheet](https://github.com/PHPOffice/PhpSpreadsheet) library is chosen to read the Excel file because it is significantly updated compared to the other options.
5. Analyzed excel data and create datalist-analyzed.xlsx


## Installation

Clone the repository somewhere, then run below command.

```docker compose up --build```

then run ip or point some url.
