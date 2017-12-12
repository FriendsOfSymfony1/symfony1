[![Build Status](https://secure.travis-ci.org/LExpress/symfony1.png?branch=master)](http://travis-ci.org/LExpress/symfony1)

About this version
------------------

This is a community driven fork of symfony 1, as official support has been [interrupted in November 2012](http://symfony.com/blog/symfony-1-4-end-of-maintenance-what-does-it-mean).

**Do not use it for new projects: this version is great to improve existing symfony1 applications, but [Symfony4](http://symfony.com/) is the way to go today.**

All the enhancements and BC breaks are listed in the [WHATS_NEW](https://github.com/LExpress/symfony1/blob/master/WHATS_NEW.md) file, this include:

- [DIC](https://github.com/LExpress/symfony1/wiki/ServiceContainer)
- Composer support
- PHP 7.0 support
- performance boost
- new widgets & validators
- some tickets fixed from the symfony trac
- ...

About symfony
-------------

Symfony is a complete framework designed to optimize the development of web applications by way of several key features.
For starters, it separates a web application's business rules, server logic, and presentation views.
It contains numerous tools and classes aimed at shortening the development time of a complex web application.
Additionally, it automates common tasks so that the developer can focus entirely on the specifics of an application.
The end result of these advantages means there is no need to reinvent the wheel every time a new web application is built!

Symfony was written entirely in PHP 5.
It has been thoroughly tested in various real-world projects, and is actually in use for high-demand e-business websites.
It is compatible with most of the available databases engines, including MySQL, PostgreSQL, Oracle, and Microsoft SQL Server.
It runs on *nix and Windows platforms.

Requirements
------------

PHP 5.3.4 and up. See prerequisites on http://symfony.com/legacy/doc/getting-started/1_4/en/02-Prerequisites

Installation
------------

See http://symfony.com/legacy/doc/getting-started/1_4/en/03-Symfony-Installation

Option 1: Using [Composer](http://getcomposer.org/doc/00-intro.md) as dependency management:

    composer require lexpress/symfony1 "1.5.*"
    composer install
    
Note: On windows, if your project is a few directories down from the drive root, composer can throw an error  relating to ZipArchive::extractTo(), this can be because pathnames are too long. There currently appears to be no proper solution but a workaround is to move your project to the drive root, run the commands from there, where they will run happily, and then move your project back. 

Option 2: Using Git submodules:
  
    git init # your project
    git submodule add https://github.com/LExpress/symfony1.git lib/vendor/symfony
    git submodule update --init --recursive

Documentation
-------------

Read the official [symfony1 documentation](http://symfony.com/legacy)

Contributing
------------

You can send pull requests or create an issue.
