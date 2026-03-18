@extends('layouts.admin')

@section('title', 'Admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 absolute">
        <h1 class="h3">Dashboard : Search</h1>
        <div class="alert alert-success d-none"></div>
        <div class="alert alert-danger d-none"></div>
    </div>

    <form class="container mt-3" style="max-width:700px;" id="searchForm">
        <div class="input-group">

            <!-- Dropdown -->
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false" id="categoryBtn">
                    Categories
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item category-option" href="#" data-value="admins">Admins</a></li>
                    <li><a class="dropdown-item category-option" href="#" data-value="users">Users</a></li>
                    <li><a class="dropdown-item category-option" href="#" data-value="posts">Posts</a></li>
                </ul>
            </div>

            <!-- Hidden input -->
            <input type="hidden" name="category" id="categoryInput" value="">

            <!-- Search input -->
            <input type="search" class="form-control" name="search" placeholder="Search...">

            <!-- Search button -->
            <button class="btn btn-primary" type="submit">Search</button>

        </div>
    </form>

    <div class="container mt-3">
        <h4>Search Result:</h4>
        <div id="searchResult" class="row g-3 gap-3">
            <!-- Cards will be appended here dynamically -->
        </div>
    </div>

    <script>
        document.querySelectorAll(".category-option").forEach(item => {
            item.addEventListener("click", function(e) {
                e.preventDefault();

                let value = this.dataset.value;
                let text = this.textContent;

                document.getElementById("categoryInput").value = value;
                document.getElementById("categoryBtn").textContent = text;
            });
        });

        $("#searchForm").on("submit", function(e) {
            e.preventDefault();

            let category = $("#categoryInput").val();
            let search = $(this).find("input[name='search']").val();
            console.log(category);
            console.log(search);

            $.ajax({
                url: "{{ route('admin.searchData') }}",
                method: "GET",
                data: {
                    category,
                    search
                },
                success: function(response) {
                    $("#searchResult").empty();

                    if (response.success) {
                        if (response.data.length > 0) {
                            response.data.forEach(item => {
                                let card = '';

                                // Post card
                                if (item.content && item.user) {
                                    card = `
<div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100">
        <img
            src="${item.photo ? '/' + item.photo : 'https://via.placeholder.com/300x150'}"
            class="card-img-top"
            alt="Post Image"
            style="height:150px; object-fit:cover;"
        />
        <div class="card-body p-2">
            <p class="card-text mb-1"><strong>Post:</strong> ${item.content.substring(0, 100)}${item.content.length > 100 ? '...' : ''}</p>
            <p class="card-text mb-1"><strong>Author:</strong> ${item.user.name}</p>
            <p class="card-text"><small class="text-muted">${new Date(item.created_at).toLocaleString()}</small></p>
        </div>
    </div>
</div>`;
                                }
                                // User/Admin card
                                else if (item.name && item.email) {
                                    card = `
<div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100">
        <div class="card-body p-2">
            <p class="card-text mb-1"><strong>Name:</strong> ${item.name}</p>
            <p class="card-text mb-1"><strong>Email:</strong> ${item.email}</p>
            ${item.role ? `<p class="card-text"><strong>Role:</strong> ${item.role}</p>` : ''}
        </div>
    </div>
</div>`;
                                }
                                // Fallback
                                else {
                                    card = `
<div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100">
        <div class="card-body p-2">
            <pre>${JSON.stringify(item, null, 2)}</pre>
        </div>
    </div>
</div>`;
                                }

                                $("#searchResult").append(card);
                            });
                        } else {
                            $("#searchResult").html("<p>No results found.</p>");
                        }
                    }
                },
                error: function(err) {
                    console.log("fetch data not success!");
                    console.log(err.responseText);
                }
            });
        });
    </script>

@endsection
