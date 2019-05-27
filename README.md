# Tech Bench
[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-blue.svg)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
[![Documentation Status](https://readthedocs.org/projects/tech-bench/badge/?version=latest)](https://tech-bench.readthedocs.io/en/latest/?badge=latest)
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
* PHP 7.2 or higher
* Composer Dependency Manager
* Node Package Manager (NPM)
* Unzip module

It is up to the system administrator to install the Linux Operating system and the LAMP stack on the server with the latest updates and security patches.  All web configuration must be done prior to installing the Tech Bench application.

It is highly recommended to use SSL along with a valid SSL Certificate.  Not doing so will result in all web traffic be sent in clear text rather than encrypted.

It is also recommended to set the root directory of the web server to the "public" folder.  This will provide better security by not allowing direct access to the application folders.

### Dependency Management
Tech Bench is built on the Laravel platform and uses NPM and Composer to maintain dependent applications required to run the Tech Bench. 

### Installation Procedure






# Copyright © 2016-2019 Ron Butcher

This program is free software:  you can redistribute it and/or modify it under the terms of the GNU 
General Public License as published by the Free Software Foundation, either version 2 of the License, 
or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even 
the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public
License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see
www.gnu.org/licenses.
