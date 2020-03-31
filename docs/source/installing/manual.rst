Manual Installation
===================

Preparing the server
--------------------

Before the installation can begin, the following requirements must be met:

Server Requirements
^^^^^^^^^^^^^^^^^^^

* Web Server is installed and running.
* Rewrite Module is enabled (if running Apache, to allow .htaccess rewrite)
* MySQL Database Server is installed and running

   **Note:**  For security purposes, it is best practice to have the `public` folder of the Tech Bench application files be the Web Document Root

PHP Requirements
^^^^^^^^^^^^^^^^

* PHP 7.2 or higher
* PHP-XML Module
* PHP-DOM Module
* PHP-ZIP Module
* PHP-GD Module

Additional Software Requirements for Dependency Management
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

* Coposer
* Node.js
* NPM
* Supervisor (Linux Distributions Only)

Setting up files
----------------

* Download the latest build of :ref: `Tech Bench<https://github.com/butcherman/Tech_Bench/releases>`
* Unzip files and place all files in the Web Root folder
* Be sure to copy the .htaccess files and .env.example files as well
* Rename the .env.example to .env
* Open the .env file and edit the following entries:

  * APP_URL=  This entry should contain the full URL of the Tech Bench Application (example:  `https://techbench.mycompany.com`)
  * DB_DATABASE=  This entry should contain the name of the database to be used for the Tech Bench.  Note:  you must create this database
  * DB_USERNAME=  The username that will be used by the tech bench to read and write to the database
  * DB_PASSWORD  The password for the database user

   **Note:** The Database user must have full permissions to the database with the grant option as well.  The user will also need select permissions from the `information_schema` virtual database.

* Save the modifications and exit

Downloading Dependencies
------------------------

From a command prompt, navigate to the Web Document Root folder and enter the following commands:

* Download all Composer dependencies

.. code-block:: shell

   composer install --no-dev --no-interation --optimize-autoloader

* Download all NPM dependencies

.. code-block:: shell

   npm install --only=production

* Create a new Application Encryption key

.. code-block:: shell

   php artisan key:generate --force

* Create the virtual link for the public storage folder

.. code-block:: shell

   php artisan storage:link

* Create the Javascript file

.. code-block:: shell

   php artisan ziggy:generate

**Note:** The APP_URL field must be correct before running this command.  Failure to do so will result in the application not running correctly!

* Compile the minimized Javascript and CSS Files

.. code-block:: shell

   npm run production --force

* Build the Tech Bench Database

.. code-block:: shell

   php artisan migrate --force

Post Installation Instructions
------------------------------

Supervisor Configuration
^^^^^^^^^^^^^^^^^^^^^^^^

In order for email notifications to be sent properly, the Supervisor Service must be configured to run the `work:queue` command

In the Supervisor directory (default `/etc/supervisord.d/`) create a new worker file `tech-bench-worker.conf`

   **Note:** On CentOS distributions, name the file with the `.inf` extension

Add the following to the `tech-bench-worker.conf` file:

.. code-block:: shell

   [program:tech-bench-worker]
      process_name=%(program_name)s_%(process_num)02d
      command=php /var/www/html/artisan queue:work --sleep=3 --tries=3  #  Note:  Modify to Web Root of your server
      autostart=true
      autorestart=true
      user=nginx
      numprocs=8
      redirect_stderr=true
      stdout_logfile=/var/www/html/storage/logs/worker.log  #  Note:  Modify to Web Root of your server

Scheduled Tasks
^^^^^^^^^^^^^^^

In order to complete the scheduled tasks provided by the Tech Bench, a cron job must also be added.

In the Cron directory (default `/etc/cron.d/`) create a new cron file `tech-bench-jobs`

Add the following to the `tech-bench-jobs` file

.. code-block:: shell

   * * * * * cd /var/wwww/html && php artisan schedule:run >> /dev/null 2>&1  #  Scheduled task will check for a scheduled job every minute.  Modigy to Web Root of your server

Accessing Tech Bench
--------------------

You can now visit the web page for the Tech Bench application by browsing to the URL noted in the .env file under APP_URL

Default login is:

   Username:  admin
   Password:  password
