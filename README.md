[![Build Status](https://secure.travis-ci.org/LExpress/symfony1.png?branch=master)](http://travis-ci.org/LExpress/symfony1)

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

PHP 5.2.4 and up. See prerequisites on http://symfony.com/legacy/doc/getting-started/1_4/en/02-Prerequisites

Installation
------------

See http://symfony.com/legacy/doc/getting-started/1_4/en/03-Symfony-Installation

Get the framework with all its dependencies:

    git clone https://github.com/LExpress/symfony1.git symfony1
    cd symfony1
    git submodule update --init 

Add the framework to your project with [Composer](http://getcomposer.org/doc/00-intro.md):

    composer require lexpress/symfony1 dev-master
    composer install

Add the framework to your project with submodule:
  
    git init
    git submodule add https://github.com/LExpress/symfony1.git lib/vendor/symfony
    git submodule update --init --recursive

Documentation
-------------

Read the official [symfony1 documentation](http://symfony.com/legacy)

Contributing
------------

You can let pull requests or create an issue.
