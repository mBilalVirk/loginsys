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
                    <i class="fa-solid fa-house"></i>
                </a>
            </li>

            <li>
                <a href="{{ route('friends') }}" id="group"
                    class="{{ request()->routeIs('friends') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-group"></i>
                </a>
            </li>

            <li>
                <a href="#" id="tv">
                    <i class="fa-solid fa-video"></i>
                </a>
            </li>

            <li>
                <a href="{{ route('user.profile', auth()->user()->id) }}" id="friend"
                    class="{{ request()->routeIs('user.profile') ? 'active' : '' }}">
                    <i class="fa-solid fa-user"></i>
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
                <a href="{{ route('userMessages') }}" id="btn_plus"
                    class="{{ request()->routeIs('userMessages') ? 'active' : '' }}">
                    <i class="fa-brands fa-facebook-messenger"></i>
                </a>
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
                        <a href="{{ route('user.profile', auth()->user()->id) }}">Profile</a>
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit"
                                style="width: 100%; text-align: left; background: none; border: none; padding: 12px 16px; cursor: pointer;">Sign
                                Out</button>
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
</script>
