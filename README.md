WritesDown CMS Based on Yii2
============================

WritesDown is Content Management System which is developed using [Yii2 Application Advanced](https://github.com/yiisoft/yii2-app-advanced/).
The application consists of three tiers: front end, back end, and console.

Application frontend is consumed by visitor of the website and search engine depends on application settings.

Backend application, more complex application, has roles consists of Super Administrator, Administrator, Editor, Author, 
Contributor, and Subscriber.

[![Yii2](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg)](http://www.yiiframework.com/)
[![Total Downloads](https://poser.pugx.org/writesdown/app-cms/downloads)](https://packagist.org/packages/writesdown/app-cms)
[![Build Status](https://travis-ci.org/writesdown/app-cms.svg?branch=master)](https://travis-ci.org/writesdown/app-cms)
[![Dependency Status](https://www.versioneye.com/user/projects/568b0e86eb4f47003c001066/badge.svg)](https://www.versioneye.com/user/projects/568b0e86eb4f47003c001066)
[![Code Climate](https://codeclimate.com/github/writesdown/app-cms/badges/gpa.svg)](https://codeclimate.com/github/writesdown/app-cms)

Feature
-------

* Developed using Yii2 Framework
* Admin-LTE version 2 for admin
* Custom taxonomies
* Custom post types
* TinyMCE as editor comes with media browser
* Support comments
* Menu builder
* Module management
* Feed generator as module
* Sitemap generator as module
* In font icon, using FontAwesome and Glyphicon by Bootstrap
* Bulk-action each index action
* User management
* Support resize and crop for images
* Widget under development

Directory Structure
-------------------
```
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    views/               contains view files for the Web application    
    widgets/             contains widgets for backend
common
    components/          contains shared components
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
    temp/                temporary directory
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
docs                     contains documentations
environments/            contains environment-based overrides
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    views/               contains view files for the Web application
    widgets/             contains frontend widgets
modules                  contains modules
public                   contains the frontend entry script and Web resources    
    admin/               contains the backend entry script and Web resources        
tests                    contains various tests for the advanced application
    codeception/         contains tests developed with Codeception PHP Testing Framework
themes                   contains themes 
vendor/                  contains dependent 3rd-party packages
widgets                  contains widgets
```

Installation
------------
Like Yii2 Application Advanced, WritesDown CMS also use composer for installation, just type the following 
to the command line.

```
composer global require "fxp/composer-asset-plugin:~1.1.1"
composer create-project --prefer-dist --stability=dev writesdown/app-cms writesdown
```

The first command installs the composer asset plugin which allows managing bower and npm package dependencies through Composer. 
You only need to run this command once for all. 
The second command installs the advanced application in a directory named writesdown.
You can choose a different directory name if you want.

Getting Started
---------------
Once the application has been downloaded, navigate towards the application directory and perform initialization on the command line.

```
cd path\of\applicaton
php init
```

Create a new database for the application, then edit the configuration on "common\config\main-local.php".

```php
'db' => [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=writesdown',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'tablePrefix' => 'wd_'
],
```
    
The "writesdown" above is the database that has been built up, please edit it to your database. 
After that, write the following code to initialize the database.

```
yii migrate
```

Go to the admin panel http://host/backend/web/ and change the username and password. 
The default username and password is superadmin/superadmin.

Done, Enjoy!

Demo
----
Please visit [http://demo.writesdown.com/](http://demo.writesdown.com/) for the demo.
