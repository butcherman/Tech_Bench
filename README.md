# Tech Bench

[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-blue.svg)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
[![GitHub release](https://img.shields.io/github/release/Butcherman/Tech_Bench)](https://GitHub.com/Butcherman/Tech_Bench/releases/)
[![Documentation Status](https://readthedocs.org/projects/tech-bench/badge/?version=latest)](https://tech-bench.readthedocs.io/en/latest/?badge=latest)
[![GitHub issues](https://img.shields.io/github/issues/Butcherman/Tech_Bench)](https://GitHub.com/Butcherman/Tech_Bench/issues/)

## About Tech Bench

Tech Bench is a custom Content Management System (CMS) built to aid service technicians by allowing them to store and share information about their customers and equipment they install and maintain.

## The Tech Bench consists of two major sections

### Customer Information

Users can create customer accounts and store customer specific information for each of these customers such as:

* The type of equipment they have installed
* Login passwords and other information for the equipment
* Notes and files specific to that customer and their equipment.

This central storage location ensures that all field staff have access to the same information.

### Tech Tips and Documentation

While working in the field, all service technicians run across tips and tricks that they use to make their jobs easier.  The Tech Tips section allows for registered users to share these tips with all other users.  This creates a custom Knowledge Base for registered users.

The Knowledge Base can also include official documentation for the different equiopment that your company installs and maintains.

Whenever a new Tech Tip is created, an email is sent to all registered users notifying them of the tip.

## Installing Tech Bench

Tech Bench is designed to run in a cluster of Docker containers.  To download the images and get the Docker-Compose file for building the cluster, visit the Tech Bench Installer repository at <https://github.com/butcherman/Tech_Bench_Installer>

## SSL Certificates

By default the Tech Bench uses a Self Signed SSL Certificate for https requests.  It is recommended to upload a valid SSL certificate to the server.  When the Tech Bench application is created, a folder called "StorageData" is created to store all file data.  Inside this directory is a "keystore" sub directory.  Name the SSL Certificate `server.crt` and the key file to `server.key`.  Place the server.crt file in the keystore directory, and place the server.key inside the keystore\private directory overwriting the existing files.  Reboot the NGINX Docker container.

To remove a custom certificate, simply delete the existing server.crt and server.key files and reboot the NGINX Docker container.  A new self signed SSL Certificate will be created.

## Copyright © 2016-2023 Butcherman

This program is free software:  you can redistribute it and/or modify it under the terms of the GNU
General Public License as published by the Free Software Foundation, either version 2 of the License,
or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even
the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public
License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see
www.gnu.org/licenses.
