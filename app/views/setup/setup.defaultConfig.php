;;;;;;;;;;;;;;;;;;;;;;
;  About Config.ini  ;
;;;;;;;;;;;;;;;;;;;;;;
;
;  This is the Configuration file for the Tech Bench that is responsible for the configuration 
;  settings for the application.
;
;  Config File Version:  2.1
;  Updated: 7-14-2017
;
;  Primary system information
[core]
;  Base URL is the website that this site is configured for.  All links that are sent via email or
;  any other means from the Tech Bench use this URL.
;  Entire URL string including http must be listed.  Also include "/" at the end to allow application
;  to append additional links when necessary.
baseURL = "<?= $_SESSION['setupData']['siteURL']; ?>/"
;   Logo is the location of the Tech Bench logo
logo = "TechBenchLogo.png"
;   Title is the tab title displayed in the browser
title = "Tech Bench"
;
;  The database setion contains all credentials required for the MYSQL connection
[database]
;  The default host for the MySQL database is localhost.  If the database is to be stored to another 
;  server, please note it location.  At this time, only the default MySQL port is allowed.
host = "<?= $_SESSION['setupData']['dbServer']; ?>"
;  Database user and password.  It is not recommended to use the master "root" user.  Create a new 
;  user and give it full access to only the database being used for the Tech Bench.
dbUser = "<?= $_SESSION['setupData']['dbUser']; ?>"
dbPass = "<?= $_SESSION['setupData']['dbPass']; ?>"
;  Default database name is 'tech_bench'.  This can be modified if necessary.
dbName = "<?= $_SESSION['setupData']['dbName']; ?>"
;
;  Endryption key for database encryption. 
;  Only customer system information is encrypted.  This is the area of the customer records that 
;  can contain sensitive information such as IP Addresses and Login credentials.
[encryption]
customerKey = <?= substr(md5(uniqid(rand(), true)), 0, 20); ?>
;
;  Email settings for all emails that are sent via the Tech Bench.
;  Tech Bench uses the PHPMailer Plugin in order to properly send emails.
[email]
;  Username for the email account
emUser = "<?= $_SESSION['setupData']['emUser']; ?>"
;  Password for the email account
emPass = "<?= $_SESSION['setupData']['emPass']; ?>"
;  Email host.  If SSL is required, be sure to note in the hostname.
;  Example:  ssl://smtp.gmail.com
emHost = "<?= $_SESSION['setupData']['emHost']; ?>"
;  Connection port required by SMTP server
emPort = "<?= $_SESSION['setupData']['emPort']; ?>"
;  Email address that should show up in the "From" field of the email.
;  Note:  This will also be the "reply to" email address as well
emFrom = "<?= $_SESSION['setupData']['emAddr']; ?>"
;  Name shown in the "from" field of the email
emName = "Tech Bench"
;
;  File paths to collect all files.
;  Be sure that the folder's are all writeable by the web server user.
[upload_paths]
;  Upload Root is the base folder that all files will go into
uploadRoot = "<?= $_SESSION['setupData']['fileLocation']; ?>/"
;  Slash depends on the server type.  "\" for Windows, and "/" for Linux
slash = /
;  Default path is the path that is chosen if the Tech Bench cannot find a location to 
;  upload the file to.
default = default/
;  CustPath is where all customer files are uploaded to.  Sub folders will be created for each customer.
custPath = cust_files/
;  SysPath is for all system specific files.  Sub folders will be created for each system type
sysPath = sys_files/
;  TipPath is for all files attached to Tech Tips.  Sub folders will be created for each Tip.
tipPath = tech_tips/
;  FormPath is for all company forms.
formPath = company_forms/
;  UploadPath is for File Links created by users for customers to upload files to.
;  Sub folders will be created for each upload link.
uploadPath = uploads/
;
;  Max upload is the maximum size a file or group of files can be in order to be safely uploaded.
;  Be sure to make this value smaller than the settings in the PHP.ini file otherwise the files 
;  will not be uploaded.
maxUpload = "<?= $_SESSION['setupData']['maxFileValue']; ?>"