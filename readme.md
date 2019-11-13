# Housing API's

Hotels APIs are 
- API that consumes third party hotels APIs and returns the filtered results in JSON format based on user queries.
- API to create a secure and refresh token and returns response in JSON format.
- API to create a local hotel and returns response in JSON format.
- API to get all locall hotels and returns response in JSON format.

## Installing

After you have all the requirements setup, clone the project and start it by running the following commands in your terminal:
 
~~~
git clone git@github.com:ramibadran/housing-api.git
cd housing-api
cp .env_prod .env (change the variable basd on your configurations) 
composer install
php artisan migrate
php artisan db:seed --class=UserTableSeeder
php artisan l5-swagger:publish
php artisan l5-swagger:generate
php artisan serve --port=8080
~~~
Use the below end points to verify your installation


- Create Token API

~~~	
	Method       : Post
	End Point    : http://127.0.0.1:8080/api/v1/token 
	Post params  : username
	Post params  : identifier
	Header Param : key ,your public key and you can get after you run seed
	encrypt the body param and send as encrypted data param,check attached sample
	
~~~

- Search Hotels API

~~~
    Method       : GET
    End Point    : http://127.0.0.1:8080/api/v1/third-part-hotels 
    Header Param : Authorization (bearer)	
~~~

- Fetch Local hotels

~~~
    Method       : GET
    End Point    : http://127.0.0.1:8080/api/v1/local-hotel
    Header Param : Authorization (bearer)	
~~~

- Create Local hotels

~~~
    Method       : POST
    End Point    : http://127.0.0.1:8080/api/v1/local-hotel
    Post params  : name
    Post params  : location
    Header Param : Authorization (bearer)	
    encrypt the body param and send as encrypted data param,check attached sample
~~~

- All post data should send as a encrypted json format like

~~~
{
    "data" : "fufNDJ9Z4VKq1JzFAc+KVhbkbA908UgDMW7IQmExOaqUCMt5KPAR5YRgYX+ueH4HtannGQ1lvqEZWVXrxgn8pg=="
}
~~~

- Response Returns as encrypted or clear based on query param ?encrypt=(0,1)
- Use the below URL to encrypt and decrypt the data 

~~~
http://127.0.0.1:8000/encrypt?api=token
http://127.0.0.1:8000/encrypt?api=create
~~~

- Documentation

~~~  
http://127.0.0.1:8080/api/documentation
~~~

### Prerequisites

- PHP >= 7.1.3
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- Ctype PHP Extension
- JSON PHP Extension
- BCMath PHP Extension
- laravel 5.2 (Follow the instructions in Laravel's documentation https://laravel.com/docs/5.7/installation)
- apache/nginx
- Web server supports PHP 7.1 or higher (apache2/nginx)
- Composer 
- Git
- htacess enabled on apache

## Running the tests
Composer test

## Deployment

N/A

## Built With

* [Laravel](https://laravel.com/docs/5.2) - The web framework used
* [Composer](https://getcomposer.org/doc/) - Dependency Management
* [Artisan](https://laravel.com/docs/5.0/artisan) - Used to generate RSS Feeds

## Authors

* **Rami Badran** 
