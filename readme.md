Extend's Web
============

This webapp was mainly developed using Laravel Framework, ReactJS and Compass.

# Development Requirements
* PHPv5.4 or later (apt-get install php5, php5-cli, php5-mcrypt)
* NodeJS
* Ruby (rvm)
* A RDBMS e.g. Mysql, Postgresql, Sqlite3
* For development it's not necessary a HTTP server because Laravel includes it's own development server

# Development Dependencies
This website's dependencies has to be installed using Composer utility:
'''
$ composer install
'''

To compile, minimize and concatenate compass and javascript files it's necessary to have installed NodeJS.

The first time you need to install gulp and compass:
'''
$ gem install compass
$ sudo npm install -g gulp
'''

To compile, minimize and concat scss and js files execute
'''
$ gulp build
'''
or
'''
$ gulp
'''

# Environment Configuration
To have a custom development environment create a 'ENVIRONMENT' file on project's root directory with the environment's name.

To create a configuration environment called 'foobar' execute
'''
$ echo foobar > ENVIRONMENT
'''
Then create a folder 'foobar' on 'app/config' with the custom configuration files such as database.php, mail.php, etc.

To check the current configuration environment execute:
'''
$ php artisan env
'''

# Install Database and initial values
Once database.php is configured (read Environment Configuration) install database and initial records by executing:
'''
$ php artisan migrate --seed
'''
