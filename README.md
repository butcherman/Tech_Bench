# Tech_Bench

Tech Bench is a custom CMS designed to help make the job of a service technician easier by allowing
technicians a safe and secure area to share files and information for their customers.


Building Tech Bench
===================

System Requirements
-------------------
The following is required to run the Tech Bench
* PHP 5 or higher
* MySQL Database
* Apache Web Server

Dependency Management
---------------------
Tech Bench uses Composer and Bower to manage all 3rd party libraries.

Start by installin [Node.js](https://nodejs.org/en/) on your system.
Install [Composer](https://getcomposer.org/).
Install [Bower](https://bower.io/).

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
Install 'gulp'
```
$ npm install gulp
```
Run Composer to fetch all dependencies
```
$ composer install
```
All necessary files will be downloaded and moved to the necessary directories.

Website Setup
-------------
Open a browser and browse to the website.  If all is working correctly, you should be directed to the
setup page.

Fill out the setup form to create your Tech Bench instance and begin using the Tech Bench.



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
