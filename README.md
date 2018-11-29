APM Events for Laravel
=====

Scalable Events Database for Laravel

This is a rewrite of Buonzz\Evorg

### Requirements

* PHP >= 7.0
* ElasticSearch Servers

### Installation

require in composer.json

    "buonzz/apm-events": "2.*"

update composer by executing this in your project base folder

    composer update

add the service provider in config/app.php in providers array

    'Buonzz\Evorg\ServiceProvider',

add the alias in config/app.php in aliases array

```
        'Evorg'   => Buonzz\Evorg\EvorgFacade::class
```
    
publish the config settings

```
php artisan vendor:publish
```

edit config/apm-events.php

* app_id - is a unique number to identify your app
* app_name - is the unique name of your app
* logging - setting this to true makes a log file for apm-events in app/storage/logs/apm-events.log , good for troubleshooting
* hosts - address of the elasticsearch servers (see http://www.elasticsearch.org/guide/en/elasticsearch/client/php-api/current/_configuration.html)


create the schemas in ElasticSearch

```
php artisan apm-events:create_schema
```

NOTE: The application assumes that the Laravel Task scheduling cron entry is added in your crontab
```
* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
```
if not, kindly do so, as its needed to perform background tasks that the package needs to do


### Usage

Insert a click for a particular thumbnail

```
Route::get('click', function()
{
    return Evorg::event("click")
                   ->insert('thumbnail', array(
                        'movie_name' => 'Interstellar',
                          'year' => '2014')
                );
});
```

Retrieve all click events

```
Route::get('all', function()
{
    return Evorg::event("click")->get();
});
```
