@extends('layouts.user')

@section('title', 'Profile')
@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openImage(imgElement) {
            var modal = document.getElementById('imageModal');
            var modalImg = document.getElementById('modalImg');

            modal.style.display = "flex";
            modalImg.src = imgElement.src;
        }

        function closeImage() {
            document.getElementById('imageModal').style.display = "none";
        }


        document.querySelector('.edit-icon').addEventListener('click', function() {
            document.getElementById('photoUpload').click();
        });

        function postUpdate() {
            // Implement post update functionality here
            getElementById('content').style.display = "none";

            alert('Post update functionality to be implemented.');
        }
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 500);
            }
        }, 3000);
    </script>
@endsection

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert"
                style="position: fixed; top: 1rem; right: 1rem; z-index: 1000; min-width: 300px;" setTimeout>
                {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif


        <!-- <div class="container"> -->
        <!-- Profile Section -->
        <div class="profile-card">

            {{-- Banner + Avatar --}}
            <div
                style="height:72px; background:linear-gradient(135deg,#7c5cfc,#fc5c7d); border-radius:18px 18px 0 0; position:relative;">
                <div style="position:absolute; bottom:-28px; left:50%; transform:translateX(-50%);">
                    <div style="width:56px; height:56px; border-radius:16px; background:var(--surface); padding:3px;">
                        <img src="{{ auth()->user()->photo ? asset(auth()->user()->photo) : asset('images/default-user.png') }}"
                            style="width:50px; height:50px; border-radius:13px; object-fit:cover; display:block;"
                            class="profile-avatar">
                    </div>
                    <span class="fa-solid fa-pen-to-square" onclick="document.getElementById('photoUpload').click()"
                        style="position:absolute; bottom:2px; right:2px; font-size:16px; cursor:pointer; background:white; border-radius:50%; width:20px; height:20px; display:flex; align-items:center; justify-content:center;">
                    </span>
                </div>
            </div>
            {{-- end of Banner + Avatar --}}

            {{-- Photo upload form (hidden) --}}
            <form action="{{ route('user.updatePhoto') }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <input type="file" id="photoUpload" name="photo" style="display:none;" onchange="this.form.submit()">
            </form>

            {{-- Name & email --}}
            <div style="padding:40px 20px 8px; text-align:center;">
                <p id="nameDisplay"
                    style="font-family:'Syne',sans-serif; font-weight:700; font-size:1rem; margin:0 0 3px; cursor:pointer;"
                    onclick="document.getElementById('nameDisplay').style.display='none'; document.getElementById('nameForm').style.display='block'; document.getElementById('nameupdate').focus();">
                    {{ auth()->user()->name }}
                </p>
                <form id="nameForm" action="{{ route('user.updateName') }}" method="post" style="display:none; margin:0;">
                    @csrf
                    @method('POST')
                    <input type="text" id="nameupdate" name="name" value="{{ auth()->user()->name }}"
                        style="text-align:center; font-weight:700; font-size:1rem; border:2px solid #007bff; border-radius:6px; padding:8px; width:100%; box-sizing:border-box;"
                        onchange="this.form.submit()"
                        onblur="document.getElementById('nameDisplay').style.display='block'; document.getElementById('nameForm').style.display='none';">
                </form>
                <p style="font-size:0.75rem; color:var(--muted); margin:0 0 16px;">{{ auth()->user()->email }}</p>
            </div>

            {{-- Profile Info (Gender & DOB) --}}
            <div style="padding:0 20px 16px; text-align:center; font-size:0.85rem; color:var(--muted);">
                <div style="display:flex; justify-content:center; gap:16px; flex-wrap:wrap;">
                    <div>
                        <p style="margin:0 0 4px; font-weight:600; color:#333;">Gender</p>
                        <p id="genderDisplay" style="margin:0; cursor:pointer;"
                            onclick="document.getElementById('genderDisplay').style.display='none'; document.getElementById('genderForm').style.display='block'; document.getElementById('genderupdate').focus();">
                            {{ auth()->user()->gender ?? 'Not set' }}
                        </p>
                        <form id="genderForm" action="{{ route('user.updateGender') }}" method="post"
                            style="display:none; margin:0;">
                            @csrf
                            @method('POST')
                            <select id="genderupdate" name="gender" onchange="this.form.submit()"
                                onblur="document.getElementById('genderDisplay').style.display='block'; document.getElementById('genderForm').style.display='none';"
                                style="border:2px solid #007bff; border-radius:4px; padding:6px; font-size:0.85rem;">
                                <option value="">Select Gender</option>
                                <option value="male" {{ auth()->user()->gender === 'male' ? 'selected' : '' }}>Male
                                </option>
                                <option value="female" {{ auth()->user()->gender === 'female' ? 'selected' : '' }}>Female
                                </option>
                                <option value="other" {{ auth()->user()->gender === 'other' ? 'selected' : '' }}>Other
                                </option>
                            </select>
                        </form>
                    </div>
                    <div>
                        <p style="margin:0 0 4px; font-weight:600; color:#333;">DOB</p>
                        <p id="dobDisplay" style="margin:0; cursor:pointer;"
                            onclick="document.getElementById('dobDisplay').style.display='none'; document.getElementById('dobForm').style.display='block'; document.getElementById('dobupdate').focus();">
                            {{ auth()->user()->dob ? \Carbon\Carbon::parse(auth()->user()->dob)->format('M d, Y') : 'Not set' }}
                        </p>
                        <form id="dobForm" action="{{ route('user.updateDOB') }}" method="post"
                            style="display:none; margin:0;">
                            @csrf
                            @method('POST')
                            <input type="date" id="dobupdate" name="dob" value="{{ auth()->user()->dob }}"
                                onchange="this.form.submit()"
                                onblur="document.getElementById('dobDisplay').style.display='block'; document.getElementById('dobForm').style.display='none';"
                                style="border:2px solid #007bff; border-radius:4px; padding:6px; font-size:0.85rem;">
                        </form>
                    </div>
                </div>
            </div>

            {{-- Nav links --}}
            <div style="border-top:1px solid var(--border); padding:8px 12px;">
                <a href="{{ route('dashboard') }}" class="pc-link"><i class="fa-solid fa-house fa-fw"></i> Home</a>
                <a href="{{ route('userMessages') }}" class="pc-link"><i class="fa-solid fa-message fa-fw"></i>
                    Messages</a>
                <a href="{{ route('friends') }}" class="pc-link"><i class="fa-solid fa-user-group fa-fw"></i> Friends</a>
                <a href="{{ route('user.profile', auth()->user()->id) }}" class="pc-link"><i
                        class="fa-solid fa-circle-user fa-fw"></i> Profile</a>
                <a href="{{ route('passwordupdate') }}" class="pc-link"><i class="fa-solid fa-lock fa-fw"></i> Change
                    Password</a>
            </div>

            {{-- Logout --}}
            <div style="padding:8px 12px 12px;">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="pc-logout"><i class="fa-solid fa-right-from-bracket fa-fw"></i>
                        Logout</button>
                </form>
            </div>

        </div>

        <!-- Posts Section -->

        <div class="posts-section post-card">
            <div class="form">
                <h3>Create a Post</h3>
                <form action="{{ route('post') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label for="content">Content</label>
                        <textarea name="content" id="content" required style="border: 1px solid #007bff;">{{ old('content') }} </textarea>
                    </div>
                    <div class="form-group">
                        <label for="photo">Image (optional)</label>
                        <input type="file" name="photo" id="image" accept="image/*"
                            style="border: 1px solid #007bff;">
                        <button type="submit" class="btn btn-primary  align-items-center gap-2 mt-3">
                            <i class="fa-solid fa-pen-to-square"></i>
                            <span>Create Post</span>
                        </button>
                    </div>
                </form>
            </div>

            <hr>
            <div class="posts">
                @forelse($posts as $post)
                    <div class="post-card" style="position:relative;">
                        <form action="{{ url('post/delete/' . $post->id) }}" method="post" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this Post?')"
                                style="border:none; background:none; cursor:pointer; position:absolute; top:0.5rem; right:0.5rem; margin-right:10px;display:block;">
                                <i class="fa-solid fa-circle-xmark" style="width:100%;color:red"></i>
                            </button>
                        </form>


                        <span class="editPost-icon material-symbols-outlined" data-toggle="modal"
                            data-target="#editPost{{ $post->id }}"
                            style="margin-right: 10px; cursor: pointer;position: absolute;top:1rem; right:3rem;">
                            edit
                        </span>

                        {{-- Post Image --}}
                        <br>
                        @if ($post->photo)
                            <img src="{{ asset($post->photo) }}" class="post-image" width="300"
                                style="margin-top:10px; border-radius:8px;" onclick="openImage(this)">
                        @endif

                        {{-- Post Content --}}
                        <!-- <textarea rows="3" disabled name="content">{{ $post->content }}</textarea> -->
                        <p>{{ $post->content }}</p>

                        <span>{{ $post->created_at->diffForHumans() }}</span>
                        <hr>
                        <div class="comment-section">
                            @foreach ($post->comments as $comment)
                                <div class="comment" style="display:flex; align-items:center; gap:10px;">
                                    <p style="margin-top:-10px;margin-bottom:-5px;">{{ $comment->user->name }} :
                                        {{ $comment->comment }}</p>


                                    <i class="fa-regular fa-pen-to-square" style="margin-bottom: 5px; cursor: pointer;"
                                        data-toggle="modal" data-target="#editComment{{ $comment->id }}"></i>
                                    <!-- The Modal -->
                                    <div class="modal" id="editComment{{ $comment->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Edit Comment</h4>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        &times;
                                                    </button>
                                                </div>

                                                <!-- Modal body -->
                                                <div class="modal-body">
                                                    <form action="{{ route('commentUpdate', $comment->id) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('POST')
                                                        <div class="form-group">
                                                            <label for="comment">Comment</label>
                                                            <textarea name="comment" id="comment" class="form-control" rows="3" required>{{ $comment->comment }}</textarea>
                                                            <input type="submit" value="Update Comment"
                                                                class="btn btn-success mt-2" />
                                                    </form>
                                                </div>
                                                <!-- Modal footer -->
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal"
                                                        style="margin-right: 10px; background-color: blue;">
                                                        Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Comment to comment -->
                                <i class="fas fa-reply" style="margin-bottom: 5px; cursor: pointer;" data-toggle="modal"
                                    data-target="#commentToComment{{ $comment->id }}"></i>
                                <!-- The Modal -->
                                <div class="modal" id="commentToComment{{ $comment->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Write Comment</h4>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    &times;
                                                </button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                <form action="{{ route('giveComment') }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('POST')
                                                    <div class="form-group">
                                                        <label for="comment">Comment:</label>
                                                        <input type="text" id='comment' name="comment"
                                                            placeholder="Write your comment here..." style="width:100%;"
                                                            required>
                                                        <input type="text" name="post_id" id="post_id"
                                                            value="{{ $post->id }}" hidden>
                                                        <input type="text" name="parent_id" id="parent_id"
                                                            value="{{ $comment->id }}" hidden>
                                                        <input type="submit" value="Comment"
                                                            class="btn btn-success mt-2" />
                                                </form>
                                            </div>
                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal"
                                                    style="margin-right: 10px; background-color: blue;">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <!-- end of Comment to comment -->




                        <form action="{{ route('user.deleteComment', $comment->id) }}" method="post"
                            onsubmit="return confirm('Are you sure you want to delete this comment?');"
                            style="all:unset; display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                style="all:unset; background:none; border:none;cursor:pointer; color:black; font-size:16px; margin-left:5px;">

                                <i class="fa-solid fa-delete-left" style="margin-bottom: 5px; cursor: pointer;"></i>
                            </button>
                        </form>

                    </div>
                    <!-- display comment to comment -->
                    @foreach ($comment->commentWithComment as $reply)
                        <div class="comment" style="display:flex; align-items:center; gap:10px;">
                            <p style="margin-top:-10px;margin-bottom:-5px; margin-left:20px;">
                                {{ $reply->user->name }} : {{ $reply->comment }}
                            </p>

                            <i class="fa-regular fa-pen-to-square" style="margin-bottom: 5px; cursor: pointer;"
                                data-toggle="modal" data-target="#editComment{{ $reply->id }}"></i>
                            <!-- The Modal -->
                            <div class="modal" id="editComment{{ $reply->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Edit Comment</h4>
                                            <button type="button" class="close" data-dismiss="modal">
                                                &times;
                                            </button>
                                        </div>

                                        <!-- Modal body -->
                                        <div class="modal-body">
                                            <form action="{{ route('commentUpdate', $reply->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('POST')
                                                <div class="form-group">
                                                    <label for="comment">Comment</label>
                                                    <textarea name="comment" id="comment" class="form-control" rows="3" required>{{ $reply->comment }}</textarea>
                                                    <input type="submit" value="Update Comment"
                                                        class="btn btn-success mt-2" />
                                            </form>
                                        </div>
                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal"
                                                style="margin-right: 10px; background-color: blue;">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Comment to comment -->
                        <i class="fas fa-reply" style="margin-bottom: 5px; cursor: pointer;" data-toggle="modal"
                            data-target="#commentToComment{{ $reply->id }}"></i>
                        <!-- The Modal -->
                        <div class="modal" id="commentToComment{{ $reply->id }}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h4 class="modal-title">Write Comment</h4>
                                        <button type="button" class="close" data-dismiss="modal">
                                            &times;
                                        </button>
                                    </div>

                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        <form action="{{ route('giveComment') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('POST')
                                            <div class="form-group">
                                                <label for="comment">Comment:</label>
                                                <input type="text" id='comment' name="comment"
                                                    placeholder="Write your comment here..." style="width:100%;" required>
                                                <input type="text" name="post_id" id="post_id"
                                                    value="{{ $post->id }}" hidden>
                                                <input type="text" name="parent_id" id="parent_id"
                                                    value="{{ $comment->id }}" hidden>
                                                <input type="submit" value="Comment" class="btn btn-success mt-2" />
                                        </form>
                                    </div>
                                    <!-- Modal footer -->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal"
                                            style="margin-right: 10px; background-color: blue;">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
            </div>
            <!-- end of Comment to comment -->




            <form action="{{ route('user.deleteComment', $reply->id) }}" method="post"
                onsubmit="return confirm('Are you sure you want to delete this comment?');"
                style="all:unset; display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit"
                    style="all:unset; background:none; border:none;cursor:pointer; color:black; font-size:16px; margin-left:5px;">

                    <i class="fa-solid fa-delete-left" style="margin-bottom: 5px; cursor: pointer;"></i>
                </button>
            </form>
        </div>
        @endforeach
        <!-- end display comment to comment -->
        @endforeach
        <hr>
        <form action="{{ route('giveComment') }}" method="POST">
            @csrf
            <label for="comment">Give comments to this post:</label>
            <div class="form-group" style="display:flex;">
                <input type="text" id='comment' name="comment" placeholder="Write your comment here..."
                    style="width:100%;" required>
                <input type="text" name="post_id" id="post_id" value="{{ $post->id }}" hidden>
                <button type="submit" class="submit-arrow">
                    <i class="fa-solid fa-comment"></i>
                </button>

            </div>
        </form>
    </div>
    <!-- <div class="container">    -->
    <div class="post-actions">
        <!-- The Modal -->
        <div class="modal" id="editPost{{ $post->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Post</h4>
                        <button type="button" class="close" data-dismiss="modal">
                            &times;
                        </button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <form action="{{ route('editpost', $post->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <div class="form-group">
                                <label for="content">Content</label>
                                <textarea name="content" id="content" class="form-control" rows="3" required>{{ $post->content }}</textarea>
                                <label for="photo">Image</label>
                                <input type="file" name="photo" id="photo" class="form-control"
                                    accept="image/*" />
                                <input type="submit" value="Update Post" class="btn btn-success mt-2" />
                        </form>

                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"
                            style="margin-right: 10px; background-color: blue;">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End of model -->
    <!-- <div class="post-actions">
                                                                                                                                    <form action="{{ url('post/delete/' . $post->id) }}" method="post">
                                                                                                                                        @csrf
                                                                                                                                        @method('DELETE')
                                                                                                                                        <button class="delete-btn" style="margin-right: 10px; background-color: red;"
                                                                                                                                                    onclick="return confirm('Are you sure you want to delete this Post?')">
                                                                                                                                                    Delete
                                                                                                                                        </button>
                                                                                                                                    </form>
                                                                                                                                </div> -->
    </div>
    </div>
@empty
    <p>No posts yet.</p>
    @endforelse


    <!-- </div> -->
    <!-- Fullscreen Image Modal -->
    <div id="imageModal" onclick="closeImage()">
        <span id="closeBtn">&times;</span>
        <img id="modalImg" src="">
    </div>


@endsection
