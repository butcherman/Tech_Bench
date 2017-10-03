# Tech Bench

Tech Bench is a custom Content Management System (CMS) built to aid service technicians store and share information for the systems and customers they maintain.

## The Tech Bench consists of three major sections:

System Information
------------------
Users can store files for individual system types such as; documentation, firmware, and helpful notes.  This informaiton can be accessed by any registered user.  This gives your company a central location to store this information rather than relying on needing access to several different manufacturers web sites.

Customer Information
--------------------
Users can create customer accounts and store custom information for each of these customers such as; the type of system they have, login information for that system, notes and files specific for that customer.  
This central storage location ensures that all users have access to the same information.

Additional features for customers include the ability to create file links that will allow files to be accessed by visitors or allow the visitor to upload their own files.

All system specific information for customers is encrypted in the database as an extra security precaution.

Tech Tips
---------
While working in the field, all service technicians run across tips and tricks that make their jobs easier.  Tech Tips allow for the users to share these tips with all registered users.  It also stores these tips and any files attached to them for later searching.




## Additional Optional Sections:

File Links
-----------
It can be difficult at times to get large file to or from the customer.  Email typically has limitations on the size of the files, and 3rd party apps such as Dropbox or OneDrive can force people to setup accounts before they can be used.  

File Links allows users to create a unique file link and upload files to that link.  They can pass that link on to a customer and the customer will be able to download the files attached, or if allowed, upload files for the user to get access to.

These file links have an expiration date, and once that date has passed the link is no longer active to non registered users.  Links are also random MD5 hashes so that they cannot be guessed by individuals that have not been given the full link.

Company Forms
--------------
This section is a simple place that all internal company files can be loaded to.  All registered users will have access to these files.

My Files
---------
This section allws the users to store files specific to them.  These files cannot be acceessed or shared with any other users.

### Note:  Each of the three above sections can be disabled




Building Tech Bench
===================

System Requirements
-------------------
The following is required to run the Tech Bench:
* PHP 7 or higher
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
