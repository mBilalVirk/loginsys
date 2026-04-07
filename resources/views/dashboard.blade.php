@extends('layouts.user')

@section('title', 'Home')

@section('head')
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script> --}}
    <style>

    </style>
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
            document.querySelector('.alert')?.remove();
        }, 4000);
    </script>
@endsection

@section('content')

    {{-- <head>
        <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    </head> --}}
    <div class="profile-page">

        <div class="profile-card">

            {{-- Banner + Avatar --}}
            <div
                style="height:72px; background:linear-gradient(135deg,#7c5cfc,#fc5c7d); border-radius:18px 18px 0 0; position:relative;">
                <div style="position:absolute; bottom:-28px; left:50%; transform:translateX(-50%);">
                    <div style="width:56px; height:56px; border-radius:16px; background:var(--surface); padding:3px;">
                        <img src="{{ auth()->user()->photo ? asset('/' . auth()->user()->photo) : asset('images/default-user.png') }}"
                            style="width:50px; height:50px; border-radius:13px; object-fit:cover; display:block;">
                    </div>
                </div>
            </div>
            {{-- end of Banner + Avatar --}}

            {{-- Name & email --}}
            <div style="padding:40px 20px 8px; text-align:center;">
                <p style="font-family:'Syne',sans-serif; font-weight:700; font-size:1rem; margin:0 0 3px;">
                    {{ auth()->user()->name }}</p>
                <p style="font-size:0.75rem; color:var(--muted); margin:0 0 16px;">{{ auth()->user()->email }}</p>
            </div>

            {{-- Nav links --}}
            <div style="border-top:1px solid var(--border); padding:8px 12px;">
                <a href="{{ route('dashboard') }}" class="pc-link"><i class="fa-solid fa-house fa-fw"></i> Home</a>
                <a href="{{ route('userMessages') }}" class="pc-link"><i class="fa-solid fa-message fa-fw"></i> Messages</a>
                <a href="{{ route('friends') }}" class="pc-link"><i class="fa-solid fa-user-group fa-fw"></i> Friends</a>
                <a href="{{ route('user.profile', auth()->user()->id) }}" class="pc-link"><i
                        class="fa-solid fa-circle-user fa-fw"></i> Profile</a>
                <a href="{{ route('passwordupdate') }}"class="pc-link"><i class="fa-solid fa-lock fa-fw"></i> Change
                    password</a>
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
        {{-- end of profile-card --}}

        <!-- UPDATE PROFILE FORM -->
        <div class="update-profile post-card">
            <h3>Create a Post</h3>

            @if ($errors->any())
                <div class="alert error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if (session('status'))
                <div class="alert success">
                    {{ session('status') }}
                </div>
            @endif
            <form action="{{ route('post') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea name="content" id="content" required style="border: 1px solid #007bff;">{{ old('content') }} </textarea>
                </div>
                <div class="form-group">
                    <label for="photo">Image (optional)</label>
                    <input type="file" name="photo" id="image" accept="image/*" style="border: 1px solid #007bff;">
                    <button type="submit" class="btn btn-primary  align-items-center gap-2">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Create Post</span>
                    </button>
                </div>
            </form>
            <hr>

            <div>
                <h2>All Post</h2>
            </div>
            <div class="">

                @foreach ($posts as $post)
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px; post-card">
                        <img src="{{ auth()->user()->photo ? asset('/' . auth()->user()->photo) : asset('images/default-user.png') }}"
                            class="mini-img">
                        <p>{{ $post->user->name }}</p>
                        <p>Posted at {{ $post->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class=" ">
                        <p
                            style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI',
             Roboto, Helvetica, Arial, sans-serif;">
                            {{ $post->content }}</p>

                        @if ($post->photo)
                            <img src="{{ asset($post->photo) }}" width="300"
                                style="margin-top:10px; border-radius:8px; ">
                        @endif
                        <div>
                        </div>
                        <hr>
                        <div class="comment-section">
                            @foreach ($post->comments as $comment)
                                <div class="comment" style="display:flex; align-items:center; gap:10px;">
                                    <p style="margin-top:-10px;margin-bottom:-5px;">{{ $comment->user->name }} :
                                        {{ $comment->comment }}</p>


                                    <i class="fa-regular fa-pen-to-square" style="margin-bottom: 5px; cursor: pointer;"
                                        data-toggle="modal" data-target="#editComment{{ $comment->id }}"></i>
                                    <!-- The Modal -->
                                    <div class="modal fade" id="editComment{{ $comment->id }}">
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
        {{-- end of update-profile post-card --}}
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
    {{-- end of profile-page --}}
    @if ($post->user->name == auth()->user()->name)
        <form action="{{ url('post/delete/' . $post->id) }}" method="post">
            @csrf
            @method('DELETE')
            <button class="delete-btn btn btn-danger mt-2"
                onclick="return confirm('Are you sure you want to delete this Post?')">
                Delete
            </button>
        </form>
    @else
        <p></p>
    @endif
    </div>
    @endforeach
    </div>
@endsection
