# Basic CRUD API
This is a basic CRUD application

## Installation

1. Download the Zip file and extract it.
2. Import the database using MySQL console or phpMyAdmin.
3. Make sure you change `AllowOverride None` to `AllowOverride All` in your `httpd.conf` file for URL rewriting.
4. Run `composer install` to install all dependencies.
5. Run the server.

# What it does?

The project provides 4 end points as per given assignment.

1. `/employee/new` - For creating new employee. Method to be used `POST`. Fields to be provided: `name`, `email`, `contact`, `designation`.
2. `/employee/{id}` - For getting employee details. Method to be used `GET`.
3. `/employee/{id}` - For updating employee's details. Method to be used `PUT`. Fields to be provided: `name`, `email`, `contact`, `designation`.
4. `/employee/{id}` - For deleting an employee. Method to be used `DELETE`.

## Dependencies

* `slim/slim: ^3.0`
