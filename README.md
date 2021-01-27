# Code Igniter

[User Guid](https://www.codeigniter.com/user_guide/index.html)

## Setup

1. Install [Composer](https://getcomposer.org/)
   - php Dependency Manager
1. Install Code Igniter through Composer
   ```cmd
   composer create-project codeigniter4/appstarter project-root
   ```
1. Gotchas
   - Code Igniter doesn't work with php 8^ as of 1/27/2021
     - Use 7.4
   - php.ini
     - Need to uncomment `extensions-intl`

## MVC Design

1. Views
   - app > Views
   - Where your views exist
1. Controllers
   - app > Controllers
   - It's good practice to have a folder per controller
   - Also good practice to have an html file per controller function
     - Index.html corresponds to index function on controller
1. Routes
   - app > Config > Routes.php
   - example.com/controllerClass/method/Id
1. View Layouts
   - [Documentation](https://www.codeigniter.com/user_guide/outgoing/view_layouts.html?highlight=view%20layout)
   - Alternative way to include common view code
1. Models
   - app > Models
   - Convention is name of table + 'Model'
     - TaskModel.php

## Database Data: Models, Configuration, and Migrations

1. Configure Framework
   - app > Config > Database.php
     - This is for settings that are the same for all environments
   - .env file
     - Not typically saved to source control
1. [Migrations](https://www.codeigniter.com/user_guide/dbmgmt/migration.html?highlight=database%20migration)
   - app > Database > Migrations
   - You can do it manually, but easier to do thru command line
     ```cmd
      php spark migrate:create create_task
     ```
     - File created has two functions
     - up is for changes
     - down is for rollbacks
   - Running Migration
     ```cmd
     php spark migrate
     ```
   - Rollback Migration
     ```cmd
     php spark migrate rollback
     ```
1. Model Classes
   - The name link between a model an a DB table
     ```php
      class TaskModel extends \CodeIgniter\Model
      {
         protected $table = 'task';
      }
     ```
   - Performs your connection for you
   - Don't have to write sql either
