# Library Management System (LMS) with PHP & MYSQL
LMS, a simple MVC based web application system with php

# Functionalities
The pages and functionalities of the lms are listed with user type

## Non Registered user
* Index page
* A page to view available books
* Registration page to create an account
* A page to display about us, contact us
* A page to view book details

## Registered user
* Login page to login
* Page to display all available books
* Page to search and display search result for books
* Page to view books issued to them
* Page to view/edit/update his profile
* To make request to lend books
* Page to view books requested to them

## Admin user
* Login page
* Page to view/edit/update his profile
* Pages to manage(insert/update/delete) book categories
* Pages to manage(insert/update/delete) book authors
* Pages to manage(insert/update/delete) books
* Pages to add issued book and to manage them
* Pages add new admin & new librarian and to delete the users
* A page to generate the analytics for top user, books, authors, categories
* A page for content management
* A page to manage application settings such as lend days, fine amount, etc.,

## Librarian user
* Login page
* Page to view/edit/update his profile
* Pages to manage(insert/update/delete) book categories
* Pages to manage(insert/update/delete) book authors
* Pages to manage(insert/update/delete) books
* Pages to add issued book and to manage them
* A page to view all available users

## More functionalities
* Maintaining the issued books
* Allowing the user to search for the book 
* Providing the current status of the book (available or issued) to the all the users
* Maintains the user details, book details, issued books details
* Providing the analytics report as csv file to the user
* Automatically managing the fine amount based on settings
* Managing the book counts

# Framework functionality
* Entire framework is mvc based one
* Loader class: A class to autoload the class and instantite them and added to base controller class
* Utility static class provides various common functions 
* File uploader traits used to handle the file uploading related actions
* Fields an iterator class used to store form fields
* Log class to manange logs
* Both Mysqli & Pdo connections are available
* Custom session handler file and database based are available
* Database connection is established based on singleton approch
* The model and service class objects are injected to the controller class
* Database, abstract class, a query builder class and a template to all other db drivers
* Exporter with pdf and csv export option with strategy approch
* Env class to parse env file and to load config values
* Datatables are used for pagination
* Captcha will be created for login and registration page
* chart.js will offer chart creation functionality