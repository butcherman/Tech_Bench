# Tech Bench

Tech Bench is a custom Content Management System (CMS) built to aid service technicians store and share information for the systems and customers they maintain.

The Tech Bench consists of three major sections:

System Information
------------------
Users can store files for individual system types such as; documentation, firmware, and general notes.  This informaiton can be accessed by any registered user.  This gives a central location to store this information rather than relying on needing access to several different manufacturers web sites.

Customer Information
--------------------
Users can create customer accounts and store custom information for each of these customers such as; the type of system they have, login information for that system, notes and files specific for that customer.  
This central storage location ensures that all users have access to the same information.

Additional features for customers include the ability to create file links that will allow files to be accessed by visitors or allow the visitor to upload their own files.

Tech Tips
---------
While working in the field, all service technicians run across tips and tricks that make their jobs easier.  Tech Tips allow for the users to share these tips with all registered users.  It also stores these tips and any files attached to them for later searching.



Building Tech Bench
===================

System Requirements
-------------------
The following is required to run the Tech Bench:
* PHP 5 or higher
* MySQL Database
* Apache Web Server

To run the Tech Bench in a safe and secure manner, the following recomendations should be followed:
* Install and run the Tech Bench on a dedicated web server - this gives the system administrator the most control over the system including storage size, and file upload size.
* Use SSL connection and valid SSL Certificates - this will ensure secure encrypted communication between the users browser, and the Tech Bench Server
* When installing the Tech Bench files, use the "public" folder as the Web Root folder - while the web server should be configured so that direct access of the application .php files should not be accessable, this is another basic security measure to make sure that potential hackers do not have a way to gain direct access to the .php files that control the application.

Dependency Management
---------------------
Tech Bench uses Composer and Bower to manage all 3rd party libraries.

Instructions for installing these dependency managers can be found here:
*

* [Node.js](https://nodejs.org/en/)
* [Composer](https://getcomposer.org/)
* [Bower](https://bower.io/)

Build Tech Bench
----------------
Clone this repository on your system
```
$ git clone https://github.com/butcherman/Tech_Bench.git
```
Open a console and go to the project directory
```
$ cd Tech_Bench
```
Run NPM Install to ensure that all necessary NPM libraries are installed
```
$ NPM Install
```
Run Composer to fetch and install all dependencies
```
$ composer install
```
Composer will download and install all necessary dependencies with their latest versions and move necessary files into the proper folders for use.

Website Setup
-------------
Once the dependencies have been installed and the files copied to the web server, open a browser and browse to the website.  If all is working correctly, you should be directed to the setup page.

Fill out the setup forms to create your Tech Bench instance and begin using the Tech Bench.  For more information, visit the docs folder.



Copyright © 2016-2017 Ron Butcher
---------------------------------

This program is free software:  you can redistribute it and/or modify it under the terms of the GNU 
General Public License as published by the Free Software Foundation, either version 3 of the License, 
or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even 
the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public
License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see
www.gnu.org/licenses.
