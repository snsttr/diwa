DIWA
====
This is just a short description. For full Documentation see [docs](docs) Directory.

## Requirements
* PHP 5.6 or higher (PHP 7 is recommended) with at least one of the following PDO-Drivers: pdo_sqlite or pdo_mysql
* A web browser (Firefox or Chrome are recommended)

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

## Reset DIWA
There are three options to reset DIWA's Database:

* Click on the bomb-icon on the bottom of the page to get to the Reset-Page. There you can trigger
  the database reset.
* When calling `/?reset=diwa` the database is being resetted instantly (unprompted).
* To also reset file changes and delete created files, just reset your git workspace (reset / clean).
  When using MySQL you should additionally use one of the reset methods above.