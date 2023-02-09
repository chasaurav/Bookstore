# Bookstore

## API documentation
- ### Public Routes
    - `/`: Landing page for bookstore.
    - `/search`: Search the bookstore model for books.
- ### Admin Routes
    - `/books/`: Dashboard Home page.
    - `/books/create`: Display the create new book page.
    - `/books/store`: Add new book in the database.
    - `/books/datatable`: Fetch data to display a list of books in the dashboard.
    - `/books/:book/edit`: Display the edit book page.
    - `/books/:book/update`: Update an existing book.
    - `/books/delete`: Delete existing book.

## Setup
- Clone the repository
- cd into the repository directory
- Create a new database
- Copy the sample `.env` file and change the values for the below keys:
    - DB_DATABASE=bookstore
    - DB_USERNAME=root
    - DB_PASSWORD=pass
- Add following variables to your `.env` file and change the values for the below keys (if needed):
    - SCOUT_DRIVER=elasticsearch
    - ELASTICSEARCH_INDEX=books
    - ELASTICSEARCH_HOST=http://localhost:9200
- Run `composer install` & `npm install`
- Run `php artisan migrate --seed`
- Run `php artisan key:generate`
- Run `php artisan scout:import "App\Models\Book"`
- Run `php artisan serve`
- Open a new terminal tab in the same location and run `npm run dev`

- Open `localhost:8000` in your browser to load the application

## Sample Admin Login Credential
- Email: admin@bookstore.com
- Passwword: 123456

#### Demo Site
[Click here](http://3.109.152.152/) to visit the demo application hosted in AWS EC2 instance.