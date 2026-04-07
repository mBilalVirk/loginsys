<header style="position: sticky; top: 0; width: 100%; z-index: 100; background-color: white;">
    <nav>
        <ul>
            <!-- Logo -->
            <li>
                <a href="{{ route('dashboard') }}" id="fb">
                    <i class="fa-brands fa-forumbee"></i>
                </a>
            </li>

            <!-- Search -->
            {{-- <li>
                <div class="search-container">
                    <input type="text" placeholder="Search...">
                    <div class="search"></div>
                </div>
            </li> --}}

            <li id="space2"></li>

            <!-- Navigation -->
            <li>
                <a href="{{ route('dashboard') }}" id="home"
                    class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <abbr title="Home"> <i class="fa-solid fa-house"></i></abbr>
                </a>
            </li>

            <li>
                <a href="{{ route('friends') }}" id="group"
                    class="{{ request()->routeIs('friends') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-group"></i>
                </a>
            </li>


            <li>
                <a href="{{ route('user.profile', auth()->user()->id) }}" id="friend"
                    class="{{ request()->routeIs('user.profile') ? 'active' : '' }}">
                    <i class="fa-solid fa-user"></i>
                </a>
            </li>

            <li>
                <a href="#" id="tv">
                    <i class="fa-solid fa-video"></i>
                </a>
            </li>

            <li id="space1"></li>

            <!-- Actions -->
            <li>
                <a href="" id="btn_plus" class="{{ request()->routeIs('') ? 'active' : '' }}">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
            </li>

            <li>

                <a href="#" id="btn_msg"
                    class="{{ request()->routeIs('userMessages') ? 'active' : '' }} msgdropbtn"
                    onclick="event.preventDefault();">
                    <i class="fa-brands
                    fa-facebook-messenger"></i>
                </a>
                <div class="msg-dropdown d-none"
                    style="width: 300px;height: 300px;background-color: white; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); padding: 10px; position: absolute; top: 50px; right: 10px;">
                    <div class="">
                        <div class="" style="">
                            <h3>Chats</h3>
                        </div>

                        @foreach (auth()->user()->friends()->get() as $friends)
                            @php

                                if ($friends->user_id == auth()->id()) {
                                    $friendUser = $friends->receiver;
                                } else {
                                    $friendUser = $friends->sender;
                                }

                            @endphp

                            <a href="{{ route('chat', $friendUser->id) }}"
                                style="all:unset; text-decoration:none; display:block; position: relative; cursor: pointer;">
                                <div
                                    style="width: 100%; background-color: white; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); padding: 10px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px;">

                                    <!-- Left side: photo + name -->
                                    <div style="display: flex; align-items: center; ">
                                        <img src="{{ asset('/' . $friendUser->photo) }}" alt="Profile Picture"
                                            style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid #ddd;">
                                        <span
                                            style="font-weight: 500; font-size: 16px; color: #333;">{{ $friendUser->name }}</span>
                                    </div>

                                    <!-- Right side: message icon -->
                                    <div style="position: relative; cursor: pointer;">
                                        <i class="fa-regular fa-message" style="font-size: 20px; color: #555;"></i>
                                        <!-- Notification badge -->
                                        <span
                                            style="position: absolute; top: -5px; right: -5px; background-color: red; color: white; font-size: 12px; font-weight: bold; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                            3
                                        </span>
                                    </div>

                                </div>
                            </a>
                        @endforeach


                    </div>
                </div>
            </li>

            <li>
                <button id="btn_bell">
                    <i class="fa-regular fa-bell"></i>
                </button>
            </li>

            <li>
                <div class="dropdown">
                    <button id="btn_profile" class="dropbtn">
                        <i class="fa-solid fa-user-gear"></i>
                    </button>
                    <div class="dropdown-content">


                        <a href="{{ route('user.profile', auth()->user()->id) }}">
                            <img
                                src="{{ auth()->user()->photo ? asset('/' . auth()->user()->photo) : asset('images/default-user.png') }}">
                            <span>
                                {{ auth()->user()->name }}
                            </span>
                        </a>
                        <hr style="margin:3px;" />



                        <a href="#" class="mobile-only">
                            <span>
                                <i class="fa-solid fa-pen-to-square"></i> <span style="margin-left:4px;">Create
                                    Post</span>
                            </span>
                        </a>

                        <a href="{{ route('userMessages') }}" class="mobile-only">
                            <span>
                                <i class="fa-brands fa-facebook-messenger"></i> <span
                                    style="margin-left:4px;">Messages</span>
                            </span>
                        </a>

                        <a href="#" class="mobile-only">
                            <span>
                                <i class="fa-regular fa-bell"></i> <span style="margin-left:4px;">Notifications</span>
                            </span>
                        </a>
                        <a href="{{ route('userlogout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">

                            <span>
                                <i class="fa-solid fa-arrow-right-from-bracket"></i> <span style="margin-left:4px;">Log
                                    out</span>
                            </span>
                        </a>

                        <form action="{{ route('userlogout') }}" method="POST" id="logout-form" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </li>
        </ul>
    </nav>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropbtn = document.querySelector('.dropbtn');
        const dropdownContent = document.querySelector('.dropdown-content');

        dropbtn.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent any default action
            dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' :
                'block';
        });

        // Close when clicking outside
        document.addEventListener('click', function(event) {
            if (!dropbtn.contains(event.target) && !dropdownContent.contains(event.target)) {
                dropdownContent.style.display = 'none';
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const msgdropbtn = document.querySelector('.msgdropbtn');
        const msgDropDown = document.querySelector('.msg-dropdown');

        msgdropbtn.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent any default action
            msgDropDown.classList.toggle('d-none');
        });

        // Close when clicking outside
        document.addEventListener('click', function(event) {
            if (!msgdropbtn.contains(event.target) && !msgDropDown.contains(event.target)) {
                msgDropDown.classList.add('d-none');
            }
        });
    });
</script>
