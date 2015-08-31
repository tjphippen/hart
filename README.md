# Hart Credit Reports for Laravel 5

[![Latest Stable Version](https://poser.pugx.org/tjphippen/hart/v/stable.png)](https://packagist.org/packages/tjphippen/hart) [![Total Downloads](https://poser.pugx.org/tjphippen/hart/downloads.png)](https://packagist.org/packages/tjphippen/hart)
- [Packagist](https://packagist.org/packages/tjphippen/hart)
- [GitHub](https://github.com/tjphippen/hart)

----------
## Installation
Add the following to your `composer.json` file.

~~~
"tjphippen/hart": "0.1.*@dev"
~~~

Then run `composer install` or `composer update` to download and install.

You'll then need to register the service provider in your `config/app.php` file within `providers`.

```php
'providers' => array(
    'Tjphippen\Hart\HartServiceProvider',
)
```

This package includes a auto registered facade which provides the static syntax for running/retrieving credit reports. 

### Create configuration file using artisan

```
$ php artisan vendor:publish
```

The configuration file will be published to `config/hart.php` which must be completed to make connections to the API.
In order to use this package you must first have account credentials to access the API provided by [Hart Software](http://www.hartsoftware.com/view/Solutions_Credit_Reports)


```php

    /**
     * Environment (development or production)
     */
    'env' => 'development',

    /**
     * Hart Account
     */
    'account' => '',

    /**
     * Hart Password
     */
    'passwd' => '',
...
```

## Examples

#### Run Credit Report
You may send an array with the persons details like below

```php
Hart::getCredit(array(
   'name' => 'John Doe', 
   'address' => '123 Fake Street',
   'city' => 'Faketown',
   'state' => 'CA',
   'zip' => '55555',
   'dob' => '08/25/1991',
   'ssn' => '123456789',
   );
```

Or simply use an object returned by an Eloquent model. 
```php
$customer = Customer::findOrFail($customerId); // Model not included :P
Hart::getCredit($customer);
```


#### Get Previous Report by Token

```php
$token = 'XXXXXXXXXXXXXX...';
Hart::getByToken(['token' => $token]);
```

Currently both functions return a full response object. I've added a chainable ->parse() method to return the following.
```
{
    "transaction": "995284100",
    "token": "F94OtZHJKthWudshcdnJI3YLRRwaappmIYo2Dp...",
    "score": 566,
    "reasons": [
        "Serious delinquency, and derogatory public record or collection files",
        "Number of accounts with delinquency",
        "Time since delinquency is too recent or unknown"
    ]
}
```
Or you can return a full SimpleXMLElement and parse to fit your needs.
```php
Hart::getCredit($customer)->xml;
```


## Change Log

#### v0.1.0

- Released