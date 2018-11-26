# Tech_Bench
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
* PHP 7.1.3 or higher
* Apache Web Server
* MySQL Database
* Composer Dependency Manager
* Node Package Manager (NPM)

It is up to the system administrator to install the Linux Operating system and the LAMP stack on the server with the latest updates and security patches.  All web configuration must be done prior to installing the Tech Bench application.

It is highly recommended to use SSL along with a valid SSL Certificate.  Not doing so will result in all web traffic be sent in clear text rather than encrypted.

### Dependency Management
Tech Bench is built on the Laravel platform and uses NPM and Composer to maintain dependent applications required to run the Tech Bench.
