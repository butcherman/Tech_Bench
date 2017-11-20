# Chapter 1 - Initial System Setup

Tech Bench is a custom Content Management System (CMS) built to aid service technicians store and share information for the systems and customers they maintain.

System Requirements
-------------------
The following is required to run the Tech Bench:
* PHP 7 or higher
* MySQL Database
* Apache Web Server

To run the Tech Bench in a safe and secure manner, the following recomendations should be followed:
* Install and run the Tech Bench on a dedicated web server - this gives the system administrator the most control over the system including storage size, and file upload size.
* Use SSL connection and valid SSL Certificates - this will ensure secure encrypted communication between the users browser, and the Tech Bench Server.  Be sure to forward all request on http port 80 to https port 443.
* When installing the Tech Bench files, use the "public" folder as the Web Root folder - while the web server should be configured so that direct access of the application .php files should not be accessable, this is another basic security measure to make sure that potential hackers do not have a way to gain direct access to the .php files that control the application.

### Note:  It is very important to properly secure your Apache Web Server.  This means continually updating with the latest security patches and making sure that there are no unnecessary ports/services running on the server that could cause any security breach.  

Download the latest version of the Tech Bench from Git Hub.
* [Latest Stable Release](https://github.com/butcherman/Tech_Bench/releases/latest)
* [Most Current Version](https://github.com/butcherman/Tech_Bench) - 
**Note:** If downloading most current version, you will need to run ```Composer Install``` in order to build the proper libraries, folders, and download all required dependencies.

The following folders within the WebRoot will need to have Read/Write access:
* **_files** - this is where all uploaded files will be stored.  The web server will create a folder structure within this base folder so that files are stored in a logical manner.
* **config** - the base config.ini file is stored in this location.  Once the site has been setup, this folder can and should be locked down to read only.  Please note that when updating the tech bench, it may be necessary to give the web server write access to this file once again.

After all files have been installed on the tech bench and the proper folders have been given 'write' access, browse to the URL of the web server.  Ths Tech Bench Setup page should be displayed.

##  Setup Page

The setup page consists of four sections.  You will need to gather the following informaiton for each section.

### Basic Information
This is the standard information for the site, it includes:
*  Your Company Name
*  The URL of the site (i.e. http://techbench.yourcompany.com)
*  The Global Administrator - Note:  this administrator is not a standard user, and only used as a System Administrator.  It should not be a standard name such as "Administrator" or "root" for security purposes.
*  Email address for the Global Administrator
*  Password for the Global Administrator - this should be a secure password that is not easily guessed.

### Database Information
This is the information that will be used to connect to the Tech Bench Database
*  Database Server - typically this will be "localhost" if the Datbase Server is part of the LAMP package installed on the Linux Server.  If the database is on another server, enter the IP Address, or FQDN of the Database Server.
*  Database Name - This is the name of the database that will be used to store the Tech Bench information.  If this is a databse name that has already been created, it must be empty.  If it is a database name that has not been created, the user must have sufficient access to create the database.
*  Database User - This user will be used to store records into the Tech Bench Database.  This user must have full access to the databse including the ability to create new tables for new systems and system upgrades.  **Important:  Do not use a global administrator such as "root."  This is a major security risk.**

The "Test Database" link should be used to test the database connection before moving on to the next step.

### Email Information
All information used in the section will allow the Tech Bench to send email notifications.
*  Email Host - The IP Address or FQDN of the Email Server. 
*  Email Port - The port that the email sevice should use for connection to the Email Server.
*  Email Username - The username used to authenticate the email service.
*  Email Password - The password used to authenticate the email service.

The "Test Email" link should be used to send a test email and confirm that the email service is working before moving on to the next step.

### File Information
Information about the file structure and customer information of the site is gathered in this section.
*  Encryption Key - This is a randomly generated key that will be used to encrypt customer information in the datbase.  It is very important to make a copy of this key and store it in a safe place.
*  Root File Location - By default all files are stored in the "_files" folder of the Web Root folder.  This can be changed if necessary.  If changed, the folder must have full read and write access by the Apache user account.  Failure to have read and write access will result in files not beeing able to be stored on the server.
*  Max File Size - The maximum size of a file is determined by many factors.  The maximum allowd by this entry is determined by the PHP "upload_max_filesize" in the php.ini file.  You can lower this to a smaller file size if neccessary.

After finalizing the settings, the site will create the database and build the file structure.  The site setup is now completed and you will be logged in to create new systems and system categories.
