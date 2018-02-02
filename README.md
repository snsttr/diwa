DIWA
====
This is just a short description. For full Documentation see [docs](docs) Directory.

## Requirements
* PHP 5.6 or higher (PHP7 is recommended) with at least one PDO-Driver (pdo_sqlite, pdo_mysql or pdo_pgsql) enabled
* Browser (Firefox or Chrome are recommended)

## Manual Installation

1. Clone the repository

2. *Optional:* Install composer dependencies with `composer install`

3. *Optional:* Change the config settings in `app/config.php` 

4. Use a Webserver of your choice. Make sure you use the project's "app" Directory as root path.

   The easiest way is to use the PHP Built-in Webserver:

       cd app
       php -S 127.0.0.1:80 -t .
       
5. Use a browser of your choice to open `http://localhost/`

## Docker
To use DIWA within a Docker Container just clone the repository and run the following Docker
commands:

    docker build -t diwa .
    docker run -p 8080:80 -d diwa:latest

DIWA will be accessible on port 8080.