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
1. [Seeders](https://codeigniter4.github.io/userguide/dbmgmt/seeds.html?highlight=seeder)
   ```cmd
     php spark make:seeder UserSeeder
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
   - [Documentation](https://codeigniter.com/user_guide/helpers/url_helper.html)
   ```php
   <a href="<?= site_url("/tasks/show/" . $task['id']) ?>">
      <?= $task['description'] ?>
   </a>
   ```
   - Makes your site more portable in the case your url's change
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

1. A big benefit of using the form helper is when including csrf protection (Filters.php)

   ```php
   public $aliases = [
   	'csrf'     => \CodeIgniter\Filters\CSRF::class,
   	'toolbar'  => \CodeIgniter\Filters\DebugToolbar::class,
   	'honeypot' => \CodeIgniter\Filters\Honeypot::class,
   	'login'	   => \App\Filters\LoginFilter::class,
   	'guest'	   => \App\Filters\GuestFilter::class
   ];

   // Always applied before every request
   public $globals = [
   	'before' => [
   		//'honeypot'
   		'csrf',
   	],
   	'after'  => [
   		'toolbar',
   		//'honeypot'
   	],
   ];
   ```

   - This is included automatically in the form (hidden field w/ the csrf token)

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
1. Can use session to save redirect urls

   - For exampmle, a user has a bookmark, but needs to log in to see the page
   - We want to make them log in, then route them to the page they requested

   ```php
   class LoginFilter implements FilterInterface
   {
      public function before(RequestInterface $request, $arguments = null)
      {
         if (!service('auth')->isLoggedIn()) {
               session()->set('redirect_url', current_url());

            return redirect()->to('/login')
                        ->with('info', 'Please login first');
         }
      }

      public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
      {
         // Do something here
      }
   }
   ```

   - Then in the Login controller

   ```php
   $redirect_url = session('redirect_url') ?? '/';

   unset($_SESSION['redirect_url']);

   return redirect()->to($redirect_url)
                     ->with('info', 'Login successful');
   ```

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

## Services

- Really just a factory that creates instances of a class
- app > Config > Services.php

1. Declaration

   ```php
   class Services extends CoreServices
   {
         // getShared = singleton
         public static function auth($getShared = true)
         {
            if ($getShared)
            {
               return static::getSharedInstance('auth');
            }

            return new Authentication;
         }
   }
   ```

1. Use

   ```php
   $auth = $auth = service('auth');
   ```

## Controller Filters

[Documentation](https://codeigniter4.github.io/userguide/incoming/filters.html)

1. Using

   ```php
   <?php namespace App\Filters;

   use CodeIgniter\HTTP\RequestInterface;
   use CodeIgniter\HTTP\ResponseInterface;
   use CodeIgniter\Filters\FilterInterface;

   class LoginFilter implements FilterInterface
   {
      public function before(RequestInterface $request, $arguments = null)
      {
         if (!service('auth')->isLoggedIn()) {
            return redirect()->to('/login')
                        ->with('info', 'Please login first');
         }
      }

      public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
      {
         // Do something here
      }
   ```

1. Configuring (app/Config/Filters.php)

   ```php
   public $aliases = [
   	'csrf'     => \CodeIgniter\Filters\CSRF::class,
   	'toolbar'  => \CodeIgniter\Filters\DebugToolbar::class,
   	'honeypot' => \CodeIgniter\Filters\Honeypot::class,
   	'login'	   => \App\Filters\LoginFilter::class
   ];

   public $filters = [
   	'login' => ['before' => ['tasks/*', 'tasks']]
   ];
   ```

1. Route Filters
   ```php
   $routes->get('/signup', 'Signup::new', ['filter' => 'guest']);
   $routes->get('/login', 'Login::new', ['filter' => 'guest']);
   ```

## Account Activation

### Generate Random Activation Token

1. randomkeygen.com
   - This will be what is used to generate a random key
   - Used in the `hash_hmac` function

```php
$token =  bin2hex(random_bytes(16));

$hash = hash_hmac('sha256', $token, 'key_from_randomkeygen');
```

1. [Email](https://codeigniter4.github.io/userguide/libraries/email.html)
   - Need an email server to send the emails
     - Use gmail
   - app > Confic > Email.php
   - Changing the fromEmail and fromName (but you can see all the options)
   - Lookup the gmail notes for using it as the smtp server
   - We are putting settings in the .env file for local storage
1. So, with everything above set up, the idea is that when a user registers

   - We generate the above hash, and save it to the User table
   - We send an email w/ that hash as part of url
   - When the user clicks on that link we handle it on our end
     - Lookup user by hash
   - Signup controller > activate

   ```php
   public function activate($token)
   {
        $model = new UserModel();

        $model->activateByToken($token);

        return view('Signup/activated');
   }
   ```

## Cookies

1. We have functionality to store a user's login when closing and opening the browser
1. To get this functionality working, we are storing a user and a token in a separate table
   - remembered_login
1. In order for the token to login to happen automatically, we need to store the token in a cookie

   - [Documentation](https://codeigniter.com/user_guide/helpers/cookie_helper.html)

   ```php
   private function rememberLogin($user_id)
   {
        $model = new RememberedLoginModel();

        list($token, $expiry) = $model->rememberUserLogin($user_id);

        $response = service('response');

        $response->setCookie('remember_me', $token, $expiry);
   }
   ```

   - The controller that is responsible for calling a function that set's a cookie, needs to redirect with cookies
     ```php
     return redirect()->to($redirect_url)
               ->with('info', 'Login successful')
               ->withCookies();
     ```

1. Retreiving Cookies
   ```php
   $token = service('request')->getCookie('remember_me');
   ```
1. Something to note. We must make sure we delete this row when logging out manually
   - Otherwise, the cookie keeps us logged in
   - We delete the row, and delete the cookie
   - Similar to when setting the cookie, include `withCookies()` which will delete the cookie from the browser
1. Last but not least, we need to cleanup the remembered_login table

   - We only delete rows when a user manually logs out
   - We need a way to clean up expired rows
   - [Custom Commands](https://codeigniter.com/user_guide/cli/cli_commands.html)
     - app > Commands
   - File name **must** match class name to work

   ```php
   <?php namespace App\Commands;

   use App\Models\RememberedLoginModel;
   use CodeIgniter\CLI\BaseCommand;
   use CodeIgniter\CLI\CLI;

   class DeleteExpiredRememberedLogins extends BaseCommand
   {
      protected $group       = 'Auth';
      protected $name        = 'auth:cleanup';
      protected $description = 'Clears expired remembered login records.';

      public function run(array $params)
      {
         $model = new RememberedLoginModel();

         $rows = $model->deleteExpired();

         echo "$rows rows deleted.\n";
      }
   }
   ```

## AJAX, Javascript, AutoComplete

1. We are going to create and autocomplete search for tasks
   - [Pixabay autoComplete](https://goodies.pixabay.com/javascript/auto-complete/demo.html)

## Styling

1. We are using a css framerwork called [Bulma](https://bulma.io/)

# Deployment

1. Digital Ocean
1. Create private ssh key
   - Through putty
1. Set up Ubunutu Droplet
   - Hook to ssh key you created
1. Reset Droplet Password
   - Emails you the randomly generated pwd
1. Log in to box, reset root pwd
1. DO has templates, but I opted to build mine from scratch
   - [Initial Server Setup](https://www.digitalocean.com/community/tutorials/initial-server-setup-with-ubuntu-20-04)
   - [LAMP Setup](https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-20-04)
   - [Setup Apache Virtual Hosts](https://www.digitalocean.com/community/tutorials/how-to-set-up-apache-virtual-hosts-on-ubuntu-18-04)
     - Allows for multiple domiains
   - [Secure Apache](https://www.digitalocean.com/community/tutorials/how-to-secure-apache-with-let-s-encrypt-on-ubuntu-20-04)
1. Make sure your php.ini matches local
   - Install php intl extension
1. Domains are somewhat tricky
   - I bought a base domain from google
   - Moved hostnames to DO (allows me to manage DNS there)
   - Add records for each subdomain
   - [Add Domain](https://www.digitalocean.com/docs/networking/dns/how-to/add-domains/)
   - [Add Subdomain](https://www.digitalocean.com/docs/networking/dns/how-to/add-subdomain/)
   - [DNS Checker](https://dnschecker.org/#AAAA/taskapp.garyhake.dev)
1. Finally, making sure a CodeIgniter app runs
   - [Ubuntu CodeIgniter](https://www.howtoforge.com/tutorial/ubuntu-codeigniter/)
1. Extensions I had to install

   ```bash
   sudo apt install php7.4-mbstring

   sudo apt-get install php-gd
   ```

   ```bash
   sudo chown -R www-data:www-data /var/www/taskapp.garyhake.dev/codeigniter/writable
   ```
