**Value First Client For Laravel**

Require this package in your composer.json and update composer.

    composer require lixus/valuefirst

## Installation

### Laravel 5.x:

After updating composer, add the ServiceProvider to the providers array in config/app.php

    Lixus\ValueFirst\ServiceProvider::class,

You can optionally use the facade for shorter code. Add this to your facades:

    'VFirstClient' => Lixus\ValueFirst\Facade::class,

### Lumen:

To change the configuration, copy the config file to your config folder and enable it in `bootstrap/app.php`:

  ```
  $app->configure('valuefirst');
  ```

After updating composer add the following lines to register provider in `bootstrap/app.php`

  ```
  $app->register(\Lixus\ValueFirst\ServiceProvider::class);
  ```

### Usage:
 with default configuration
 ```
 VFirstClient::send(to,template,tag,delivery_url)
 ```
 or with custom credential
 ```
 VFirstClient::withCredential(username, password, sender)
    ->send(to,template,tag,delivery_url)
 ```
  
