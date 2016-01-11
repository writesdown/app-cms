WritesDown Change Log
=====================
(January 11, 2016)
------------------
* Bug fixes delete menu item
* Update travis.yml and readme.md

(January 5, 2016)
-----------------
* Reformatting and refactoring code
* Minor bug fixes

(December 23, 2015)
-------------------
* (ENH) Refactoring widget
* MediaUploadHandler incorrect crop size

(November 29, 2015)
-------------------
* (ENH) Move directory web to public
* Bug fixes
(September 13, 2015)
--------------------
* (ENH) Update widgets and theme
* (ENH) Add Nav widget in themes writesdown for item activation
* WidgetController set enableCsrfValidation to false in function beforeAction to avoid error 400 on ajax
* Activated widgets only show on single space

(September 12, 2015)
--------------------
* Add and update new alias: themes, modules, and widgets
* Separating themes from frontend directory
* Delete common/db and move migrations to console/migrations 
* Add new migrations files (m000000_000020_module.php and m000000_000021_widget.php) in migrations directory
* Add new model (Module and Widget) and their crud action
* Add new modules in its directory
* Add new widgets
* Widget is underdevelopment
* Bug fix on media upload
* Update composer.json, README.md, CHANGELOG.md

0.1.1 (September 11, 2015)
--------------------------
* Update backend left sidebar
* Update PostType &amp; Option model 

0.1.0 (September 1, 2015)
------------------------
* Add new alias: themes &amp; modules
* Add toolbar to frontend
* Update environments based on alias
* Bug fix on delete menu
* Update composer.json
