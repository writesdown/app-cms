WritesDown CMS Based on Yii2
============================

WritesDown CMS is CMS which is developed based on [Yii2 Application Advanced](https://github.com/yiisoft/yii2-app-advanced/). 
The application consists of three tiers: front end, back end, and console.

Application frontend is consumed by visitor of the website and search engine depends on application settings.

Backend application, more complex application, has roles consists of Super Administrator, Administrator, Editor, Author, 
Contributor, and Subscriber.

Feature
-------

* Developed using Yii2
* Admin-LTE version 2 for admin
* Custom taxonomies
* Custom post types
* TinyMCE as editor comes with media browser
* Support comments
* Menu builder
* Built in feed generator
* Built in sitemap
* In font icon, using FontAwesome and Glyphicon by Bootstrap
* Bulk-action each index action
* User management
* Support resize and crop for images

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
    web/                 contains the entry script and Web resources
    web/                 contains frontend widgets
common
    components/          contains shared components
    config/              contains shared configurations
    db/                  contains db.sql, db.mwb, database structure, migations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
environments/            contains environment-based overrides
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
tests                    contains various tests for the advanced application
    codeception/         contains tests developed with Codeception PHP Testing Framework
```

Installation
------------
Like Yii2 Application Advanced, WritesDown CMS also use composer for installation, just type the following 
to the command line.

```
composer global require "fxp/composer-asset-plugin:1.0.0"
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
yii migrate --migrationPath=@common/db/migrations
```

Go to the admin panel http://hostname/admin (without slash on the end) and change the username and password. 
The default username and password is superadmin/superadmin.

Done, Enjoy!

Demo
----
Please visit [http://democms.writesdown.com/](http://democms.writesdown.com/) for the demo.

