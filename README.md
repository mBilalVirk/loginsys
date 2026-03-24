-- Active: 1769664658051@@127.0.0.1@3306@loginsys

# LoginSys & Social Media project

1. Laravel Project Initialization
2. Basic Route Configuration (`web.php`)
3. Auth Middleware Implementation
4. Blade Template Setup (Login, Register, Dashboard)
5. Manual User Registration System
6. Manual User Login System
7. User Logout Functionality
8. User Dashboard Development
9. User Profile Management
10. Profile Photo Upload Feature
11. Password Update Functionality
12. Post Model Creation
13. Post Migration Setup
14. Post Controller Development
15. Create Post Functionality
16. Edit Post Functionality
17. Delete Post Functionality
18. User–Post Relationship Implementation
19. Friendship Table Migration
20. Friend Request Send Feature
21. Friend Request Accept Feature
22. Friend Request Delete Feature
23. Unfriend User Feature
24. Friends Listing Page
25. Admin Login System
26. Admin Authentication Handling
27. Admin Dashboard Development
28. Admin User Edit Feature
29. Admin User Update Feature
30. Admin User Delete Feature
31. Role-Based Access Control (Admin/User)
32. Secure Route Protection

# 22/01/2026 Task

1. Create Search form
2. Find the user from the User Table
3. Display on the Search.blade.php

# Code friendController.php

```Php
 public function searchUser(Request $request){

                $searchUser = $request->input('searchUser');
                $users = User::where('name', 'LIKE', "%{$searchUser}%")
                             ->orWhere('email', 'LIKE', "%{$searchUser}%")
                             ->get();
                return view('user.search', compact('users'));

                // return view('user.friends', compact('searchUser'));
            }
```

4. Some bug fix in PostController You must need to check if post have file, if it have a file or photo its should be delete or unlink

```php
 public function delete($id){
        $post=Post::findOrFail($id);
        // Check before unlink file
        if($post->photo && file_exists(public_path($post->photo))){
            unlink(public_path($post->photo));
            }
            $post->delete();
         return redirect()->back()->with('status','Post has been deleted successfully');
    }
```

# 23/01/2026 Task

1. Improvement in Admin dashboard
2. add : Like dashboard.blade.php with userCount and postCount

```php
    public function countUsersPosts()
    {
        $userCount = User::count();
        $postCount = Post::count();
        return view('admin.dashboard', compact('userCount', 'postCount'));

    }
```

3. Display all User on Dashboard. users.blade.php : Route :: admin.users

```php
    public function fetch()
    {
        $user = auth()->user();
         $users = user::all();
        return view('admin.users', compact('users','user'));

    }
```

4. Display all User's Post in Dashboard. posts.blade.php : Route :: admin.posts

```php
 public function userPosts(){
        $posts = USER::with('posts')->get();
        // return $posts;
        return view('admin.posts', compact('posts'));
    }
```

5. Add something new. Bootstrap Models

```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Bootstrap Example</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        />
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <div class="container">
            <h2>Modal Example</h2>
            <!-- Button to Open the Modal -->
            <button
                type="button"
                class="btn btn-primary"
                data-toggle="modal"
                data-target="#editUser"
            >
                Open modal
            </button>

            <!-- The Modal -->
            <div class="modal" id="editUser">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Modal Heading</h4>
                            <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                            >
                                &times;
                            </button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">Modal body..</div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-danger"
                                data-dismiss="modal"
                            >
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
```

6. Add functionality in admin panel He can edit and delete the post

# 26/01/2026 Tasks

1. Update User Profile. user can edit their Post directly in Profile page. some bugs fixes.
2. Add setting.blade.php in admin penal, Where admin can update its profile details. like Name, Email, Password and Profile Photo
3. Add adminMiddleWare that only admin can use the control penal.

# 27/01/2026 Tasks

1. Add super_admin in admin Penal
2. Improved Profile page layout.
3. Create comments Table and make its relation with users and post
4. View Comments in admin Penal.

```php
public function userPosts(){
        $posts = USER::with('posts.comments')->get();
        // return $posts;
        return view('admin.posts', compact('posts'));
    }
```

# 28/01/2026 Tasks

1. Add comment section in user dashboard and profile. user can see comment and create commend
2. User can delete comments on his post.

# 29/01/2026 Tasks

1. Add Soft Delete Functionality. User can Delete Post etc. eg. [click here](https://medium.com/@emad-mohamed/softdelete-on-laravel-a8545ec87253)
2. Admin Can see all Deleted item in Recycle bin.
3. Add Restore and Permanently Delete users, Post, Comments and Admins
4. There is bug in using SoftDelete. Relationship not working properly. you need to define relationship as given below... Optional : don't do this. in relation it give you all deleted data.

```PHP
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed(); //You Must used ->withTrashed() with belongsTo
    }
```

# 02/02/2026 Tasks

1. change the some query in userController to solve the error to fetching the deleting user post data.
2. improve login

# 03/02/2026 Tasks

1. Messages System. User can send messages to their friend
2. And received messages from the friends

# 04/02/2026 Tasks

1. User can delete and update its messages
2. Search page improving.

# 09/02/2026 Tasks

1. trying to add comment to comment

# 10/02/2026 Tasks

1. add comment to comment in post

# 11/02/2026 Tasks

1. Trying Ajax in laravel blade

# 13/02/2026 Tasks

1. fetch data with ajax

```JS
<script>

    $(document).ready(function(){
       loadAdmins();
        $("#admin-form").submit(function(e){
            e.preventDefault();
            const form = $('#admin-form')[0];
            const data = new FormData(form);

            $("#form-submit").prop("disabled", true);
            $.ajax({
                url:"{{ route('admin.createNewAdmin') }}",
                type:"POST",
                data:data,
                processData:false,
                contentType:false,
                success:function(data){
                    $("successAlert").removeClass('d-none').text(data.res);
                    $("#form-submit").prop("disabled", false);
                    $('#admin-form').get(0).reset();
                    loadAdmins();
                },
                error:function(e){
                    $("#errorAlert").removeClass('d-none').text(e.responseText);
                    $('#admin-form').get(0).reset();
                }
            });

        });


   });
function loadAdmins(){
         $.ajax({
                url: "{{ route('admin.admins') }}",
                type: "GET",
                success: function(data){
                    console.log(data);
                   let rows = '';
                   data.forEach(function(admin){

                        rows +=`<tr>
                                    <td>${admin.id}</td>
                                    <td>${admin.name}</td>
                                    <td>${admin.email}</td>
                                    <td><img src='/${admin.photo}' width='60' height='60' class="rounded-circle object-fit-cover"/>
    </td>
                                    <td>
                                        <button class="btn btn-primary">Edit</button>
                                    </td>
                                    <td>
                                        <button class="btn btn-danger">Delete</button>

                                    </td>
                                </tr>
                                `;

                   });
                   $("#adminTableBody").html(rows);
                },
                error: function(err){
                    console.log("fetch data not success!");
                    console.log(err.responseText);
                }
                });

    }
</script>

```

# 17/02/2026

```html
<!DOCTYPE html>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script>
            $(document).ready(function () {
                $("#flip").on("click", function () {
                    $("#panel").stop(true, false).slideToggle("slow");
                });
            });
        </script>
        <style>
            #panel,
            #flip {
                padding: 5px;
                text-align: center;
                background-color: #e5eecc;
                border: solid 1px #c3c3c3;
            }

            #panel {
                padding: 50px;
                display: none;
            }
        </style>
    </head>
    <body>
        <div id="flip">Click to slide down panel</div>
        <div id="panel">Hello world!</div>
    </body>
</html>
```

# 18/02/2026

1. Pagination using laravel + Ajax

# 19/02/2026

1. Message Update With Ajax

# 23/02/2026

1. build chatting app using laravel websockets. 'reverb'
2. User can send update and delete msg in realtime.

3. Api in project

```PHP
php artisan install:api

```

2. create folder app/Http/Api
3. add UserController
4. create route api.php in routes folder
5. create form in vite using tailwind templet

# 25/02/2026

1. Trying to used front-end library like react or Vite
2. create api using sanctum
3. create login, logout, and register api

# 26/02/2026

1. create front-end login, register to understand how work api with front-end.
2. Register a new user. using Front-end vite.
3. try to understand primereact front-end library.
4. After Successful register a user using `vite@latest` app
5. Now User can register and login

# 03/03/2026

1. Create createPost component, and show post component
2. Create Messenger,

# 04/03/2026

1. want to create realtime chatting with laravel + vite@react
2.

# 05/03/2026

1. Create real time chat-app using api and vite

# 09/03/2026

1. add admin panel
2. Use front-end `Vite@latest`
3. login and admin Panel
4. try to create CRUD operations
5. admin can view post/ users/

# 10/03/2026

1. admin can add admin and delete admin
2. admin can view users and post

# 11/03/2026

1. Search in admin panel

# 12/03/2026

1. Search in admin panel front-end vite@latest
2. pdf generator.
3. ```
   https://github.com/barryvdh/laravel-dompdf
   ```
4. ```cmd
       php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
   ```
5. Generate PFD controller. Generate data what kind of pdf data are you want to generate
6. Create view where you can design the output the pdf. in `.blade.php`

```php

    $users = User::get();
    $data = [
        'title'=> 'Users',
        'date' => date('y/m/d'),
        'users'=> $users
    ];
    $pdf = Pdf::loadView('generatepdf', $data); //generate the pdf
    return $pdf->download('user.pdf'); //download the pdf
    return $pdf->stream('user.pdf'); // preview the pdf on browser
```

6.  | Function                    | What it does                                                                |
    | --------------------------- | --------------------------------------------------------------------------- |
    | `stream('filename.pdf')`    | Shows the PDF in the browser directly. Users can view or save it.           |
    | `download('filename.pdf')`  | Forces download of the PDF to the user’s computer.                          |
    | `output()`                  | Returns the raw PDF as a string (useful if you want to attach it to email). |
    | `save('path/filename.pdf')` | Saves the PDF to the server filesystem.                                     |

```php
$pdf = PDF::loadView('invoice', $data)
          ->setPaper('A4', 'landscape') //you can formate the document like landscape portrait also paper size
          ->setWarnings(false);
```

7. Trying to add chart in laravel

```cmd
composer require consoletvs/charts
php artisan make:chart UsersChart
```

8. Show the Admin and user Ration in chart. Use `app/chart/UserChart.php`

```php
$this->labels(['Admins', 'Users']); // X-axis labels

        $admins = User::where('role', 'admin')->count();
        $users = User::where('role', 'user')->count();

        $this->dataset('User Roles', 'bar', [$admins, $users])
            ->backgroundColor(['#f87979', '#7acbf9']);
```

9. import `use App\Charts\UsersChart;` in UserController
10. Create a variable and assign the chart `$chart = new UsersChart();`
11. Return the the variable to the view
12. In view past this code

```php
<div style="width: 600px; margin: auto;">
        {!! $chart->container() !!}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {!! $chart->script() !!}
```

# 16/03/2026

1. Complete some coursera courses.

# 17/03/2026

1. Adding pdf in different Views.
2. Generate Excel. first check `C:\xampp\php\php.ini` uncomment `extension=gd and extension=zip`.
3. Install this: `https://docs.laravel-excel.com/3.1/getting-started/installation.html`.
4. Run this: ` php artisan make:export UserExport`.
5. You can export/import excel using different method. Know we try to do with from view.
6. `UserExport` Need Some changes. Like change impliments must be `Fromview`.
7. Here is code of `UserExport`.

```php
<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UserExport implements FromView, ShouldAutoSize
{
    use Exportable;

    private $data;

    public function __construct()
    {
        $users = User::all(); // fetch all users
        $this->data = [
            'title' => 'Users',
            'date' => date('Y/m/d'),
            'users' => $users
        ];
    }

    public function view(): View
    {
        return view('exportexcel', [
            'data' => $this->data // pass to view
        ]);
    }
}
```

8. Controller Code.

```php
 public function ExportExcel(){
        return Excel::download(new UserExport, 'user.xlsx');
    }
```

# 18/03/2026

1. Add pdf and csv,xlsx for different tables

# 24/03/2026

1. Try to use Fortify-Laravel: `https://laravel.com/docs/13.x/fortify`.
2. Installation & Configuration:

`cmd
composer require laravel/fortify
php artisan fortify:install
php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"
php artisan migrate
`

3. This creates:

`config/fortify.php`

`app/Actions/Fortify/` (action classes)

`database/migrations/` (adds 2FA columns to users table)

4. Register the Service Provider

```php
// Laravel 11 - bootstrap/providers.php
return [
    App\Providers\AppServiceProvider::class,
    App\Providers\FortifyServiceProvider::class, // 👈 add this
];
```

5. Configure config/fortify.php for API

```php
return [
    'guard' => 'sanctum', // use sanctum for API token auth or web

    'middleware' => ['api'], // 👈 change from 'web' to 'api'

    'prefix' => 'api', // 👈 all fortify routes prefixed with /api

    'features' => [
        Features::registration(),
        Features::resetPasswords(),
        Features::emailVerification(),
        Features::updateProfileInformation(),
        Features::updatePasswords(),
        // Features::twoFactorAuthentication(), // enable if needed
    ],
];
```

5. Try to reset password using api and `https://mailtrap.io/`.
6.
