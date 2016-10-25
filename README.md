SmartWork
=========

A small Smarty based framework.

Install
-------

Add the whole repository as a git submodule.
Symlink the index.php from within SmartWork to the root of the project.
Symlink migrations.php and transformToMigrations.php from the db_migrations folder to the
db_migrations folder of the project.
Create a config.php in the parent folder of the framework where you can edit the database
credentials and adjust other options (see config.default.php).

Modules
-------

As of version 1.1 a module system has been added.
The following modules are activated by default:
- Base
 - This module only contains the database entries for the translation system.
- Index
 - This module contains a basic index page to start with.
- UserSystem
 - This module contains a user system with login, registration and administration.
- Imprint
 - This module contains an imprint page fetching the information to show from the config.

Every page/class/etc. that is in the SmartWork modules can be overridden by self defined
modules or in the case of the pages normal pages also can override them.
Load order: classes of the page -> classes of page modules -> classes of SmartWork
-> classes of SmartWork modules

A module can contain classes, templates, pages and database migrations.

The usage of modules can be disallowed by setting `$GLOBALS['config']['useModules']` to
false.

Menu generation
---------------

A simple menu can be generated out of the config options. To add new entries for the menu
simply use `\SmartWork\Utility\General::addMenuPage()`. This will ensure, that the logout
and imprint buttons stay at the end of the menu.

You also can specify, on which conditions the menu entry is shown. There are four
different conditions:
- -1 -> this defines that an entry is always shown, e.g. used for the imprint
- 0 -> this entry is only shown if not logged in
- 1 -> this entry is only shown if logged in
- 2 -> and this entry is only shown if logged in and the administrator status is granted

Furthermore you can define default pages. The value defines the default page according to
the above list.
The defaults are:
- logged out: login page
- logged in: index page

The sort order of the menu entries is defined by the position value, but can be left empty
in the `addMenuPage()` call. This starts at 0 and goes up to 9999. The following values
are defined per default:
- 0: login
- 1: registration
- 2: index
- 3: user administration
- 9998: logout
- 9999: imprint

List of unallowed pages
-----------------------

There also is a possibility to define pages that can not be called directly. This is used,
for example, in the Header page. You can use those classes and templates in other classes
or templates but cannot call them with `index.php?page=Header`.

To add a page to this list, extend the array in `$GLOBALS['config']['unAllowedPages']`.
