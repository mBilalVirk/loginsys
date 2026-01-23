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
