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

## Use Virtual Host To Access Framework

1. In the httpd.conf file
   - This is the Apache Config file
   ```
   # Virtual Hosts
   Include etc/extra/httpd-vhosts.conf
   ```
1. Open the httpd-vhosts.conf

   - This exists inside the xampp folder
   - xampp > apache > conf > extra

   ```conf
   <VirtualHost *:81>
    ServerName taskapp.localhost

    DocumentRoot "C:\Users\gary.hake\source\personal\php-codeiginter-taskapp\public"

    <Directory "C:\Users\gary.hake\source\personal\php-codeiginter-taskapp\public">
        Require all granted
        AllowOverride All
    </Directory>
   </VirtualHost>
   ```

1. Since I'm not using the default port 80, you have to reference the url like so:
   - http://taskapp.localhost:81
   - Otherwise you won't need the :port

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
1. Allowed Fields (insertion)

   ```php
   class TaskModel extends \CodeIgniter\Model
   {
      protected $table = 'task';

      protected $allowedFields = ['description'];
   }
   ```

   - Protects against mass insertion

1. Validation Rules

   ```php
   class TaskModel extends \CodeIgniter\Model
   {
      protected $table = 'task';

      protected $allowedFields = ['description'];

      protected $validationRules = [
         'description' => 'required'
      ];

      protected $validationMessages = [
        'description' => [
            'required' => 'Please enter a description'
        ]
      ];
   }
   ```

   - Can add your own validation messages

1. Model Events (hashing a password)

   ```php
    protected $beforeInsert = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password_hash'] =
                password_hash($data['data']['password'], PASSWORD_DEFAULT);

            unset($data['data']['password']);
        }

        return $data;
    }
   ```

## Links Between Pages

1. You can use anchor tags to link directly to your page
   ```php
   <a href="/tasks/show/<?= $task['id'] ?>">
      <?= $task['description'] ?>
   </a>
   ```
1. You should use the `site_url` function instead
   ```php
   <a href="<?= site_url("/tasks/show/" . $task['id']) ?>">
      <?= $task['description'] ?>
   </a>
   ```
1. This generates the url for you

   - CAVEAT: This will add your index page to the url
     - http://taskapp.localhost/index/tasks/show/1
   - To Fix
     - app > Config > App.php
     ```php
     public $indexPage = '';
     ```
     - http://taskapp.localhost/tasks/shows/1

## Form Helper

[Form Helper Documentation](https://www.codeigniter.com/user_guide/helpers/form_helper.html?highlight=form%20helper)

1. You have to include the form helper in each controller that uses it
1. In this course, we added it to the base controller Helper array
   ```php
   protected $helpers = ["form"];
   ```
1. Using the form helper

   ```php
   <?= form_open("/tasks/create") ?>

   </form>
   ```

## Retrieving Input

1. Base php
   ```php
   $something = isset($_POST['foo']) ? $_POST['foo'] : NULL;
   ```
1. CodeIgniter request
   ```php
   $something = $request->getVar('foo');
   ```

## Displaying Validation Errors/Redirect

1. Above under the model section, we show how to define validation rules:

   ```php
   class TaskModel extends \CodeIgniter\Model
   {
      protected $table = 'task';

      protected $allowedFields = ['description'];

      protected $validationRules = [
         'description' => 'required'
      ];

      protected $validationMessages = [
        'description' => [
            'required' => 'Please enter a description'
        ]
      ];
   }
   ```

1. Showing Errors: Controller

   ```php
   public function create()
   {
   	$model = new \App\Models\TaskModel;

   	$result = $model->insert([
   		'description' => $this->request->getPost("description")
   	]);

   	if ($result === false) {
   		return redirect()->back()
   						 ->with('errors', $model->errors());
   	} else {
         return redirect()->to("/tasks/show/$result");
   	}
   }
   ```

   - Use the redirect() function, and pass it model errors

1. Showing Errors: View
   ```php
   <?php if(session()->has('errors')): ?>
    <ul>
        <?php foreach(session('errors') as $error): ?>
            <li><?= $error ?></li>
        <?php endforeach ?>
    </ul>
   <?php endif ?>
   ```
   - Use session and errors key that we set in the controller
1. Say you're editing a form and you put in incorrect values and hit save
   - If you redirect 'back' (edit page), you need to know the bad value (not the one that's saved there)
   - Use 'old' in front end
     ```php
     <input type="text" name="description" id="description"
               value="<?= old('description', esc($task['description'])) ?>">
     ```
   - You also need to chain the '->withInput()' method on the controller redirect return
1. More complicated validation
   ```php
   protected $validationRules = [
        'name' => 'required',
        'email' => 'required|valid_email|is_unique[user.email]',
        'password' => 'required|min_length[6]',
        'password_confirmation' => 'required|matches[password]'
    ];
   ```

## Flash Messages

1. The Error message above is a type of flash message
1. 'with' is how you define a flash message

   ```php
   public function create()
   {
   	$model = new \App\Models\TaskModel;

   	$result = $model->insert([
   		'description' => $this->request->getPost("description")
   	]);

   	if ($result === false) {
   		return redirect()->back()
   						 ->with('errors', $model->errors())
   						 ->with('warning', 'Invalid data');
   	} else {
   		return redirect()->to("/tasks/show/$result")
   						 ->with('info', 'Task created successfully');
   	}
   }
   ```

   - Display them the same as w/ the error messages above

1. Flash messages only stay in session for the current request
   - Refresh or page change and they are gone

## Entities

[Documentation](https://www.codeigniter.com/user_guide/models/entities.html?highlight=entities)

- Represent a single database row
- Allows you to pass objects to your view instead of array pairs
- Old
  ```php
  public function new()
  {
  	return view('Tasks/new', [
  		'task' => ['description' => '']
  	]);
  }
  ```
- New

  ```php
  public function new()
  {
  	$task = new Task;

  	return view('Tasks/new', [
  		'task' => $task
  	]);
  }
  ```

## Session Library

[Documentation](https://www.codeigniter.com/userguide3/libraries/sessions.html)

```php
$session = session();
$session->regenerate(); // protect against session fixation attacks
$session->set('user_id', $user->id);
```

1. By default, session persists for 2 hours
   - To change: app > Config > app.php
     ```php
     public $sessionExpiration        = 7200;
     ```
   - Set to 0

## Helper Functions

1. app > Helpers
1. To define

   ```php
   <?php

   if (!function_exists('current_user')) {
      function current_user()
      {
         if (!session()->has('user_id')) {
               return null;
         }

         $model = new \App\Models\UserModel;

         return $model->find(session()->get('user_id'));
      }
   }
   ```

1. To use

   ```php
   public function index()
   {
   	helper('auth');

   	return view("Home/index");
   }
   ```

1. Do the above only if you need the helper in one controller
   - For all, put in BaseController
