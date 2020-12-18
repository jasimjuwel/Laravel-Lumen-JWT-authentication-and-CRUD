# Lumen JWT authentication and Simple CRUD(create,read,update, delete) Operation

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://poser.pugx.org/laravel/lumen-framework/d/total.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/lumen-framework/v/stable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://poser.pugx.org/laravel/lumen-framework/license.svg)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen JWT authentication and Simple CRUD(create,read,update, delete) Operation. Here I did simple jwt authentication and product, edit, update and delete operation using jwt. 

## What's included
* [Lumen 8](https://lumen.laravel.com/docs/8.x)
* [Lumen config discover](https://github.com/chuckrincon/lumen-config-discover)
* [intervention/image](http://image.intervention.io/getting_started/installation)
* [rap2hpoutre/laravel-log-viewer](http://image.intervention.io/getting_started/installation) (to seee log base_url/logs)
* [tymon/jwt-auth](https://jwt-auth.readthedocs.io/en/develop/lumen-installation/)

## Installation:
* Clone the repo
* Copy `.env.example` to `.env`
* Configure `.env`
* Run `php artisan cache:clear`
* Run `php artisan storage:link`
* `cd` to the repo
* Run `composer install --no-scripts`
* Run `php artisan key:generate`
* Run `php artisan cache:clear`
* Run `php artisan view:clear`
* Run `php artisan route:clear`
* Run `composer dump-autoload -o`
* Run `php artisan migrate`. Database will be created:
* View the site by
    * Either running `php artisan serve` if you are not using vagrant homestead or laravel valet (in a new terminal/command prompt)
    * Otherwise go to your local dev url configured in vagrant

## Instruction:
*  `Postman collection`

        Postman collection added in project public folder
  
*  `Update image path and upload dir in .env`

       IMAGE_PATH=http://localhost:8000/uploads/
       UPLOAD_DIR=/var/www/html/lumenapi/public/uploads/

## Note:

I tried to follow the best practices, but any suggestion, modification is highly appreciated.
