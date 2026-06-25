# Broomees Backend Assignment

## Tech Stack

- Laravel 12
- PHP 8.2
- MySQL

## Features

- Token Authentication
- User CRUD
- Mutual Relationships
- Hobby Management
- Reputation Bussiness Logic
- Optimistic Locking
- Rate Limiting

## Installation

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate --seed (for creating hobbies and use get hobbies api for getting hobby id)

php artisan serve

## Run Tests

php artisan test

## API Documentation

[https://documenter.getpostman.com/view/52634124/2sBXwwooJJ](https://documenter.getpostman.com/view/52634124/2sBXwwooJJ)
