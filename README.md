DIWA
====
This is just a short description. For full Documentation see [docs](docs) Directory.

## Manual Installation

1. Clone the repository

2. *Optional:* Install composer dependencies

       cd /<Path to DIWA>/
       composer install

3. *Optional:* Change the config Settings in `app/config.php` 
4. Use a Webserver of your choice. Make sure you use the project's "app" Directory as root path.
   The easiest way is to use the PHP Built-in Webserver:

       php -S localhost:80 -t /path/to/diwa/app

## Docker
To use DIWA within a Docker Container just clone the repository and run the following Docker
commands:

    docker build -t diwa .
    docker run -p 8080:80 -d diwa:latest

DIWA will be accessible on port 8080 of your dockers hostname / ip.