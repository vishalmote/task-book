<b>Pre-Assumptions</b>

-   All the tasks and notes will be visible to all the users, data visibility is not user based
-   No update and Delete functionality is needed for tasks and notes
-   Storing attachments in storage system rather than as blob in database
-   No Paginated records in retrieve task list
-   No display/download option for uploaded media
database

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
-   My local pgsql configuration is as below
<pre>
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=task_book
DB_USERNAME=postgres
DB_PASSWORD=myroot
</pre>
-   Mysql configuration is as below
<pre>
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=task_book
DB_USERNAME=root
DB_PASSWORD=myroot
</pre>
-   Migrate tables
<pre>php artisan migrate</pre>
-   Install passport package keys and create one client
<pre>php artisan passport:install</pre>
-   Generate the passport keys if not generated
<pre>php artisan passport:keys</pre>
-   Seed the data
<pre>php artisan db:seed</pre>
-   Create Symlink of storage into public
<pre>php artisan storage:link</pre>
-   Run the project
<pre>php artisan serve</pre>
-   Use following credential to login
    <pre>email : taskkeeper@gmail.com</pre>
    <pre>password : LaravelIs@Gr8</pre>
-   Note :
*   Used database postgres.
*   Code is not crossed checked with other database system.
*   My machine was pre-installed most of the dependencies of laravel project, So you may face those dependencies error on your machine, Please install corresponding dependecies as needed
*   More improvisation can be done by putting response formatter at one place, multi-lingual messages, Better Frontend framework the javascript/jquery with laravel blade, etc
