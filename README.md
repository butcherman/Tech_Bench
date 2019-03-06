# Tech Bench
[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-blue.svg)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
[![Build Status](https://travis-ci.org/butcherman/Tech_Bench.svg?branch=master)](https://travis-ci.org/butcherman/Tech_Bench)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/butcherman/Tech_Bench/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/butcherman/Tech_Bench/?branch=master)

Tech Bench is a custom Content Management System (CMS) built to aid service technicians by allowing them to store and share information for the systems and customers they maintain.

## The Tech Bench consists of three major sections:

### System Information
Users can store files for individual system types such as:  documentation, firmware, and helpful notes.  This information can be accessed by any registered user.  This gives your company a central location to store this information rather than relying on needing access to several different manufacturers web sites.

### Customer Information
Users can create customer accounts and store customer specific information for each of these customers such as: the type of system they have, login information for that system, notes, and files speific to that customer.  This central storage location ensures that all registered users have access to the same information.

Additional features for ustomers include the ability to create a custom link that will allow files to be accessed by visitors or allow visitors to upload their own files.

### Tech Tips
While working in the field, all service technicians run across tips and tricks that they use to make their jobs easier.  The Tech Tips section allows for registered users to share these tips with all other users.  This creates a custom Knowledge Base for registered users.

# Installing Tech Bench
Tech Bench is designed to run on a dedicated Linux Web Server.
### Requirements
* Apache Web Server
* MySQL Database
* PHP 7.1.3 or higher
* Composer Dependency Manager
* Node Package Manager (NPM)
* Unzip module

It is up to the system administrator to install the Linux Operating system and the LAMP stack on the server with the latest updates and security patches.  All web configuration must be done prior to installing the Tech Bench application.

It is highly recommended to use SSL along with a valid SSL Certificate.  Not doing so will result in all web traffic be sent in clear text rather than encrypted.

It is also recommended to set the root directory of the web server to the "public" folder.  This will provide better security by not allowing direct access to the application folders.

### Dependency Management
Tech Bench is built on the Laravel platform and uses NPM and Composer to maintain dependent applications required to run the Tech Bench. 

### Installation Procedure
There are two recommended procedures for installing the Tech Bench
* Loading zip file
* Downloading directly from Github

Both options will 

#### Option 1 - Loading zip file
* Load the zip file onto the Web Server via SFTP software such as Solar Winds to a staging directory such as the users $HOME directory
* Navigate to the directory the file is loaded in.
* Run the command
```
unzip Tech_Bench-master.zip   //  Be sure to enter the correct file name
```
* Move to the "Tech Bench" directory
```
cd Tech_Bench-master   //  Again, be sure to enter the correct folder name
```
* Run the installer script as sudo user
```
sudo ./scripts/install
```

#### Option 2 - Downloading directly from Github
* In the Web Server navigate to the folder you wish to load the staging files such as the users $HOME directory
* Clone the Tech Bench repository
```
git clone https://github.com/butcherman/Tech_Bench.git
```
* Navigate to the Tech_Bench directory
```
cd Tech_Bench
```
* Run the installer script as sudo user
```
sudo ./scripts/install
```

The install script will download all necessary dependencies, create the Tech Bench Database, and load the files to the Web Root directory.

# Post Installation Instructions
Once the Tech Bench has been installed, you can navigate your web browser to the URL of the web server and login with the default credentials:
Username: admin
Password: password

Although there is a .htaccess file that will redirect all web traffic to the "public" folder inside the Tech Bench application, it is highly recommended to set the website web root to point at the public folder.

For example, if the web files are stored in the /var/www/html, the web root should point at /var/www/html/public.

# Copyright © 2016-2018 Ron Butcher

This program is free software:  you can redistribute it and/or modify it under the terms of the GNU 
General Public License as published by the Free Software Foundation, either version 3 of the License, 
or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even 
the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public
License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see
www.gnu.org/licenses.
