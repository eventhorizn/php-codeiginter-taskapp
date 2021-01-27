# Code Igniter

[User Guid](https://www.codeigniter.com/user_guide/index.html)

## MVC Design

1. Views
   - app > Views
   - Where your views exist
   - It's good practice to have a folder per controller
   - Also good practice to have an html file per controller function
     - Index.html corresponds to index function on controller
1. Routes
   - app > Config > Roots.php
   - example.com/controllerClass/method/Id
1. View Layouts
   - [Documentation](https://www.codeigniter.com/user_guide/outgoing/view_layouts.html?highlight=view%20layout)
   - Alternative way to include common view code

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
