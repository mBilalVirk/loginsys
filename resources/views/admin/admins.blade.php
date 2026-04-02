@extends('layouts.admin')
@section('title', 'Admin')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Dashboard : Admins</h1>
        <div class="alert alert-success d-none"></div>
        <div class="alert alert-danger d-none"></div>
        <div>
            <a class="btn btn-warning text-white" id="downloadPdf">
                <i class="fa-solid fa-file-pdf"></i> Download PDF
            </a>
            <a class="btn btn-success text-white" id="downloadExcel">
                <i class="fa-solid fa-file-excel"></i> Export Excel
            </a>
            @if (auth()->user()->role == 'super_admin')
                {{-- ✅ Bootstrap 5 --}}
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAdmin">
                    Add New Admin
                </button>
            @else
                <p></p>
            @endif

            <script>
                $("#downloadPdf").click(function() {
                    const search = $("#searchInput").val();
                    const dateFrom = $("#dateFrom").val();
                    const dateTo = $("#dateTo").val();
                    const sort = $("#sortInput").val();

                    const url =
                        `/admin/adminpdf?search=${search}&date_from=${dateFrom}&date_to=${dateTo}&sort=${sort}`;
                    window.open(url, '_blank'); // opens PDF in a new tab
                });

                $("#downloadExcel").click(function() {
                    const search = $("#searchInput").val();
                    const dateFrom = $("#dateFrom").val();
                    const dateTo = $("#dateTo").val();
                    const sort = $("#sortInput").val();

                    const url =
                        `/admin/adminexport?search=${search}&date_from=${dateFrom}&date_to=${dateTo}&sort=${sort}`;
                    window.open(url, '_blank'); // opens Excel in a new tab
                });
            </script>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Add Admin Modal --}}
        <div class="modal fade" id="addAdmin" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add New Admin</h4>
                        {{-- ✅ Bootstrap 5 --}}
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="successAlert" class="alert alert-success d-none"></div>
                        <div id="errorAlert" class="alert alert-danger d-none"></div>

                        <form enctype="multipart/form-data" id="admin-form">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" name="name" required />
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required />
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required />
                            </div>
                            <div class="form-group">
                                <label for="confirm-password">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm-password"
                                    name="password_confirmation" required />
                            </div>
                            <div class="form-group">
                                <label for="photo">Photo</label>
                                <input type="file" class="form-control" name="photo" id="photo" />
                            </div>
                            <button type="submit" class="btn btn-primary mt-3" id="form-submit">
                                Add Admin
                            </button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        {{-- ✅ Bootstrap 5 --}}
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="model-btn">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        {{-- Search form --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" id="searchInput" class="form-control" placeholder="Search name or email...">
            </div>

            <div class="col-md-3">
                <input type="date" id="dateFrom" class="form-control">
            </div>

            <div class="col-md-3">
                <input type="date" id="dateTo" class="form-control">
            </div>

            <div class="col-md-2">
                <select id="sortInput" class="form-control">
                    <option value="newest">Newest</option>
                    <option value="oldest">Oldest</option>
                    <option value="az">A → Z</option>
                    <option value="za">Z → A</option>
                </select>
            </div>
        </div>
        {{-- End of Search form --}}
        <hr />
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Photo</th>
                        <th colspan="2">Action</th>
                    </tr>
                </thead>
                <tbody id="adminTableBody"></tbody>
            </table>

            {{-- Edit Admin Modal --}}
            <div class="modal fade" id="editUser" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Admin</h4>
                            {{-- ✅ Bootstrap 5 --}}
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form enctype="multipart/form-data" id="editAdmin">
                                @csrf
                                <div class="form-group">
                                    <label for="editName">Name:</label>
                                    <input type="text" class="form-control" id="editName" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="editEmail">Email</label>
                                    <input type="email" class="form-control" id="editEmail" name="email">
                                </div>
                                <div class="form-group">
                                    <label for="photo">Photo</label>
                                    <input type="file" class="form-control" name="photo" id="editPhoto">
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Update Admin</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            {{-- ✅ Bootstrap 5 --}}
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                                id="editClose">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function() {
            loadAdmins();

            $("#admin-form").submit(function(e) {
                e.preventDefault();
                const form = $('#admin-form')[0];
                const data = new FormData(form);

                $("#form-submit").prop("disabled", true);
                $.ajax({
                    url: "{{ route('admin.createNewAdmin') }}",
                    type: "POST",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        $("#successAlert").removeClass('d-none').text(data.res);
                        $("#form-submit").prop("disabled", false);
                        $('#admin-form').get(0).reset();
                        // ✅ Bootstrap 5 modal close
                        bootstrap.Modal.getInstance(document.getElementById('addAdmin')).hide();
                        loadAdmins();
                    },
                    error: function(e) {
                        const err = e.responseJSON.errors;
                        $.each(err, function(key, value) {
                            $("#errorAlert").removeClass('d-none').text(value);
                            $('#admin-form').get(0).reset();
                            setTimeout(() => $("#errorAlert").addClass('d-none'), 4000);
                            $("#form-submit").prop("disabled", false);
                        });
                    }
                });
            });
        });

        function loadAdmins() {
            let rows = '';
            const search = $("#searchInput").val().trim();
            const dateFrom = $("#dateFrom").val();
            const dateTo = $("#dateTo").val();
            const sort = $("#sortInput").val();
            $.ajax({


                url: "{{ route('admin.admins') }}",
                type: "GET",
                data: {
                    search: search,
                    date_from: dateFrom,
                    date_to: dateTo,
                    sort: sort
                },

                success: function(data) {

                    data.forEach(function(admin) {
                        rows += `<tr>
                            <td>${admin.id}</td>
                            <td>${admin.name}</td>
                            <td>${admin.email}</td>
                            <td><img src='/${admin.photo}' width='60' height='60' class="rounded-circle object-fit-cover"/></td>
                            <td>
                                <button class="btn btn-primary"
                                    onclick="editAdmin(${admin.id},'${admin.name}','${admin.email}')">
                                    Edit
                                </button>
                            </td>
                            <td>
                                <button class="btn btn-danger" onclick="deleteAdmin(${admin.id})">Delete</button>
                            </td>
                        </tr>`;
                    });
                    $("#adminTableBody").html(rows);
                },
                error: function(err) {
                    console.log("fetch data not success!");
                    console.log(err.responseText);
                }
            });
        }
        let debounce;

        $("#searchInput").on("input", function() {
            clearTimeout(debounce);
            debounce = setTimeout(() => {
                loadAdmins();
            }, 400);
        });
        // Trigger search on input or change
        $(" #dateFrom, #dateTo, #sortInput").on('input change', function() {
            loadAdmins();
        });

        function editAdmin(id, name, email) {
            $("#editName").val(name);
            $("#editEmail").val(email);

            let modalEl = document.getElementById('editUser');
            let modal = new bootstrap.Modal(modalEl);
            modal.show();

            // ✅ .off('submit') prevents stacking events on multiple clicks
            $("#editAdmin").off('submit').on('submit', function(e) {
                e.preventDefault();
                const data = new FormData(this);
                $.ajax({
                    url: `/admin/update/${id}`,
                    type: "POST",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        $(".alert-success").removeClass("d-none");
                        $(".alert-success").html(`<span>${data.res}</span>`);
                        modal.hide();
                        loadAdmins();
                        setTimeout(() => $(".alert-success").addClass("d-none"), 4000);
                    },
                    error: function(err) {
                        console.log(err.responseText);
                    }
                });
            });
        }

        function deleteAdmin(id) {
            if (confirm("Are you sure you want to delete this Admin?")) {
                $.ajax({
                    url: `/admin/delete/${id}`,
                    type: "DELETE",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $(".alert-success").removeClass("d-none").html(`<span>${response.res}</span>`);
                        loadAdmins();
                        setTimeout(() => $(".alert-success").addClass("d-none"), 4000);
                    },
                    error: function(err) {
                        console.log(err.responseText);
                    }
                });
            }
        }
    </script>

@endsection
