# Setup guide

## Extensions

> extension=sodium

* Clone The Repo
* Install Dependancies
```
composer install
```
* configure database
```
php artisan migrate --seed
```
* install passport
```
php artisan passport:install
```
<!-- php artisan passport:keys -->
* run
```
php artisan serve
```

## User Accounts

| Username  | Password |
| ------------- |:-------------:|
| owner      | password     |
| manager      | password     |
| cashier      | password     |

## Link

Default Link `http://localhost:8000/api/documentation`

## ER

![ER Diagram.](/er.png "ER Diagram.")




