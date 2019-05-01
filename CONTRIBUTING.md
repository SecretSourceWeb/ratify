# Contributing to Ratify #

Ratify is intended to be a completely pluggable plugin. Adding tests
should consist of:

1. Create a new class that extends `RatTestBase` in App/Models
2. Define a `runtest` method that returns a structured, associative array.
3. Update the translations file

At this point you should be able to see the results of your test by 
viewing the report in the WordPress admin.

If you working on the Ratify core, you'll also need to learn how to use
composer, npm, Laravel Homestead, Laravel mix, and phpunit (for testing).
We use Laravel Homestead for all of our development and testing because
it provides a uniform, reliable, full-fledged PHP development environment
that runs the same on everyone's computer, regardless of the host system
you are running (thank you Taylor + whoever!)

## Unit Testing and Code Linting

This plugin also supports unit tests. Configuring the plugin for unit
testing can be a bit of a pain so we've tried to outline the steps here:

### Install Test Scaffolding ###

The scaffolding is installed using [wp-cli](https://wp-cli.org). If you 
are unfamiliar with wp-cli, do yourself a favor and learn it. It is an 
amazing WordPress admin tool that we can't live without at this point.

To install the scaffolding, go to your WordPress root and execute:

`wp scaffold plugin-tests PLUGIN_NAME` (where PLUGIN_NAME is ratify-plugin, 
the name of the php file that is included and the name of the plugin
folder - this is not totally kosher and should probably be reviewed)

This will create the folders and files necessary to start testing the
plugin. You then need to set up your testing environment. This is 
accomplished by running the set up file (`bin/wp-install-wp-tests.sh`).

At this point you can test the testing framework by running `phpunit`
from the plugin root. If everything is configured properly, the sample
test should execute and return Ok. See below if things don't seem to 
work as expected.

Assuming you've got everything configured properly and you can execute
your tests, it's time for you to start writing your own tests. See
see [this link - mostly aimed at core WordPress development](https://make.wordpress.org/core/handbook/testing/automated-testing/writing-phpunit-tests/)
for more information on how to write tests. Make sure you put your tests
in the `tests` folder.

### Issues Installing PHPUnit ###

First, make sure [phpunit is installed](https://phpunit.de/manual/6.5/en/installation.html)

1. Subversion is required, so install it first `apt install subversion`
2. Works best with phpunit 6 `composer require --dev phpunit/phpunit ^6`
3. You need to run bin/install-wp-tests.sh. It requires database credentials. The script is a bit crusty (brittle). If the script fails, you'll need to delete all references to WordPress in /tmp, so, `rm -Rf /tmp/wordpress*`
4. Also, be sure to run `composer install` before running the tests (duh!)
5. `phpcs` can be a real pain to get configured properly. Essentially, the main issue is that we tend to run composer and such inside Homestead while our code editor lives outside Homestead. Thus, any references to commands or config files may not have the correct paths associated with them. 12factor.net specifieds that paths should **always** be path relative (to make things more portable) but that's in a perfect world and we're working in, well, WordPress-world.

### Issues Installing and Configuring phpcs

The beauty of `phpcs` is it's integration with vscode. You can see right 
in your editor where the problems are (and fix them, hopefully). However, 
getting phpcs up and running is not an easy task. Fortunately, thanks in
part to wprig (who showed us how to do this), phpcs is not installed and
configured automatically. No end user interaction required!

If you install the [phpcs extension]() of vscode, it should pick up the
repository configuration automatically. If not, here are the steps to 
install phpcs globally.

1. Install phpcs globally (normally ~/.composer/vendor/bin/phpcs)
2. Install the [WordPress coding standards](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards) in a global location somewhere on your system, maybe ~/Sites/wpcs
3. Tell phpcs to use the coding standards with 
	`phpcs --config-set installed_paths (comma-delimited set of paths to your coding standards)`. 
	For example, since I have coding standards in ~/Sites/wpcs and in 
	~/.composer/vendor/phpcompatibility/phpcompatibility-wp, the command 
	for me is 
`phpcs --config-set installed_paths \
	'/Users/tedsr/Sites/wordpress/wpcs,\
	/Users/tedsr/.composer/vendor/phpcompatibility/php-compatibility,\
	/Users/tedsr/.composer/vendor/phpcompatibility/phpcompatibility-paragonie,\
	/Users/tedsr/.composer/vendor/phpcompatibility/phpcompatibility-wp'`.
4. Use the `phpcs.xml.dist` file found in this repository (copy it to your own project)

## References ##

- [How to Set Up Unit Testing for WordPress Plugins](https://premium.wpmudev.org/blog/unit-testing-wordpress-plugins-phpunit/)
- [Installing PHPUnit](https://phpunit.de/manual/6.5/en/installation.html)