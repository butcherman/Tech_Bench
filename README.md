# Tech Bench

[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-blue.svg)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
[![GitHub Release](https://img.shields.io/github/release/Butcherman/Tech_Bench)](https://GitHub.com/Butcherman/Tech_Bench/releases/)
[![GitHub Issues](https://img.shields.io/github/issues/Butcherman/Tech_Bench)](https://GitHub.com/Butcherman/Tech_Bench/issues/)

## About Tech Bench

Tech Bench is a custom Content Management System (CMS) designed specifically for service technicians working in the field.
This application allows technicians to securely store and share important customer information such as contact names, equipment
installed, equipment backups, and notes about the customer. The advantage to using this application is to allow any technician -
even those unfamiliar with the customer - quick and centralized access to all of this information.

## Tech Bench consists of two major sections

### Customers

Customer accounts can be created as stand alone or with multiple attached sites. Customer specific information can then
be stored and shared for quick centralized access. Information includes:

- Equipment Types Installed
- Equipment Specific Information (such as IP Addresses, login information, etc.)
- Contacts, including onsite and offsite contacts
- Notes for the customer, their specific site, or specific to the installed equipment
- Files such as backups, site maps, etc.

### Tech Tips (Knowledge Base) and Documentation

While working in the field, all service technicians run across tips and tricks that they use to make their jobs easier.
Tech Tips allows for users to create and share these tips with coworkers. This creates a custom Knowledge Base for all
registered users.

An optional Public Knowledge Base is also available to give your customers an easy place to find information such as Quick
Reference Guides, or custom documentation that has been put together for customers.

## Additional Features

### File Links

Sometimes customers need access to files, or need to provide technicians with files that may be too large to email. File
Links allows users to create a custom URL to deliver files, or have files uploaded to. Each File Link has an expiration
dates and is only available for a limited time.

## Installation and Setup of Tech Bench

Tech Bench is a Docker based application.  Follow the instructions in the [Installation Guide](INSTALLATION.md) for
detailed instructions to setup the Tech Bench.

## Upgrading Tech Bench

Upgrading to the latest version can be done though a bash script.  Follow the instructions in the
[Upgrade Guide](UPGRADING.md) for more information.

## Backing Up Tech Bench

In order to backup the Tech Bench to an off-server location, you will need to use
a package such as [Samba](https://www.samba.org/) to mount a network shared drive.
This package needs to be installed on the dedicated server.

Durning the installation process, you will create a folder called ***backupData***
in the same directory as the Docker Compose file.  See the [Installation Guide](INSTALLATION.md)
for more information.

This ***backupData*** folder can be mounted to a network share to store backups
off-server.

## Copyright © 2016-2025 Ron Butcher

This program is free software: you can redistribute it and/or modify it under the terms of the GNU
General Public License as published by the Free Software Foundation, either version 2 of the License,
or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even
the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public
License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see
<www.gnu.org/licenses>.
