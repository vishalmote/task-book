<b>Dependencies :</b>

-   OS : Ubuntu 18.04
-   composer version : 2.5.8
-   php version : 8.1
-   install dependent package for laravel latest which will be thrown by composer install command like ext-curl extension etc
<pre> ex : sudo apt-get install php8.1-curl</pre>

<b>Setup : </b>

-   checkout the code
<pre>git clone https://github.com/vishalmote/task-book.git</pre>
-   Go to the checkout directory
-   Install the laravel dependecies
<pre>composer install</pre>
-   You may face some php extension dependencies errors, please install dependent php-extensions
-   Create .env file from .env.example, just copy .env.example to .env
-   I have used postgres as database system, as it was pre-installed, you may need pgsql php extension for connectivity with postgres database
-   Create one database with its corresponding read/write/alter access user and password
-   Add the database credentials created above into .env
-   My local configuration is as below
<pre>
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=task_book
DB_USERNAME=postgres
DB_PASSWORD=myroot
</pre>
-   Seed the data
<pre>php artisan db:seed</pre>
-   Run the project
<pre>php artisan serve</pre>
-   Use following credential to login
    <pre>email : taskkeeper@gmail.com</pre>
    <pre>password : LaravelIs@Gr8</pre>
-   Note :
    -- Used database postgres.
    -- Code is not crossed checked with other database system.
    -- My machine was pre-installed most of the dependencies of laravel project, So you may face those dependencies error on your machine, Please install corresponding dependecies as needed
