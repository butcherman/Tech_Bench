Server Requirements
===================

Tech Bench is designed to run on a dedicated Linux Web Server.

Requirements
------------
* Apache Web Server
* MySQL Database
* PHP 7.2 or higher
* Composer Dependency Manager
* Node Package Manager (NPM)
* Unzip module

It is up to the system administrator to install the Linux Operating system and the LAMP stack on the server with the latest updates and security patches.  All web configuration must be done prior to installing the Tech Bench application.

It is highly recommended to use SSL along with a valid SSL Certificate.  Not doing so will result in all web traffic be sent in clear text rather than encrypted.

It is also recommended to set the root directory of the web server to the "public" folder of this application.  This will provide better security by not allowing direct access to the application folders.

Dependency Management
---------------------
Tech Bench is built on the Laravel platform and uses NPM and Composer to maintain dependent applications required to run the Tech Bench. 
