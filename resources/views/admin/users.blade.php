@extends('layouts.admin')

@section('title', 'Users')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Dashboard : Users</h1>
        <div>
            <a class="btn btn-warning flex text-white" id="downloadPdf">
                <i class="fa-solid fa-file-pdf"></i> Download PDF
            </a>
            <a class="btn btn-success flex text-white" id="downloadExcel">
                <i class="fa-solid fa-file-excel"></i> Download Excel
            </a>
        </div>
    </div>
    <script>
        $("#downloadPdf").click(function() {
            const search = $("#searchInput").val();
            const dateFrom = $("#dateFrom").val();
            const dateTo = $("#dateTo").val();
            const sort = $("#sortInput").val();

            const url =
                `/admin/generatepdf?search=${search}&date_from=${dateFrom}&date_to=${dateTo}&sort=${sort}`;
            window.open(url, '_blank'); // opens PDF in a new tab
        });

        $("#downloadExcel").click(function() {
            const search = $("#searchInput").val();
            const dateFrom = $("#dateFrom").val();
            const dateTo = $("#dateTo").val();
            const sort = $("#sortInput").val();

            const url =
                `/admin/exportexcel?search=${search}&date_from=${dateFrom}&date_to=${dateTo}&sort=${sort}`;
            window.open(url, '_blank'); // opens Excel in a new tab
        });
    </script>

    <div class="container mt-4">

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="alert alert-success d-none"></div>
        <div class="alert alert-danger d-none"></div>
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
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Photo</th>
                        <th>DOB</th>
                        <th>Gender</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="usersTable"></tbody>
            </table>

            <nav>
                <ul class="pagination justify-content-center" id="paginationLinks"></ul>
            </nav>

            {{-- Edit Modal --}}
            <div class="modal fade" id="editUser" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h4 class="modal-title">Edit User</h4>
                            {{-- ✅ Bootstrap 5 close button --}}
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <form enctype="multipart/form-data" id="editForm">
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="editName">Name:</label>
                                    <input type="text" class="form-control" id="editName" name="name" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="editEmail">Email</label>
                                    <input type="email" class="form-control" id="editEmail" name="email">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="editEmail">DOB</label>
                                    <input type="date" class="form-control" id="editDob" name="dob">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Gender</label>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="genderMale"
                                            value="male">
                                        <label class="form-check-label" for="genderMale">
                                            Male
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="genderFemale"
                                            value="female">
                                        <label class="form-check-label" for="genderFemale">
                                            Female
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="photo">Photo</label>
                                    <input type="file" class="form-control" name="photo" id="photo">
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Update User</button>
                            </form>
                        </div>

                        <div class="modal-footer">
                            {{-- ✅ Bootstrap 5 data-bs-dismiss --}}
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
            loadUsers();
        });

        function loadUsers(page = 1) {
            let row = '';
            const search = $("#searchInput").val();
            const dateFrom = $("#dateFrom").val();
            const dateTo = $("#dateTo").val();
            const sort = $("#sortInput").val();

            $.ajax({
                url: `{{ route('admin.users') }}?page=` + page,
                type: "GET",
                data: {
                    page: page,
                    search: search,
                    date_from: dateFrom,
                    date_to: dateTo,
                    sort: sort
                },
                success: function(response) {
                    console.log(response.data)
                    response.data.forEach(function(user) {
                        row += `<tr>
                            <td>${user.id}</td>
                            <td>${user.name}</td>
                            <td>${user.email}</td>
                            <td><img src="/${user.photo}" width='50' height='50' class="rounded-circle object-fit-cover"/></td>
                            <td>${user.dob}</td>
                            <td>${user.gender}</td>
                            <td>
                                <button class="btn btn-primary"
                                    onclick="editUser(${user.id},'${user.name}','${user.email}','${user.dob || ''}','${user.gender || ''}')">
                                    <i class="fa-solid fa-pen-to-square"></i> 
                                </button>
                                <button class="btn btn-danger"
                                    onclick="deleteUser(${user.id})">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>


                        </tr>`;
                    });
                    $("#usersTable").html(row);
                    buildPagination(response);
                },
                error: function(err) {
                    console.log(err.responseText);
                }
            });
        }
        let debounce;

        $("#searchInput").on("input", function() {
            clearTimeout(debounce);
            debounce = setTimeout(() => {
                loadUsers();
            }, 400);
        });
        $("#dateFrom, #dateTo, #sortInput").on("change", function() {
            loadUsers();
        });

        function deleteUser(id) {
            $.ajax({
                url: `/admin/delete/${id}`,
                type: "DELETE",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    $(".alert-success").removeClass("d-none");
                    $(".alert-success").html(`<span>${data.res}</span>`);
                    setTimeout(() => $(".alert-success").addClass("d-none"), 4000);
                    loadUsers();
                },
                error: function(err) {
                    console.log(err.responseText);
                    $(".alert-danger").removeClass("d-none");
                    $(".alert-danger").html(`<span>${err.responseText}</span>`);
                    setTimeout(() => $(".alert-danger").addClass("d-none"), 4000);
                }
            });
        }

        function editUser(id, name, email, dob, gender) {
            $("#editName").val(name);
            $("#editEmail").val(email);
            $("#editDob").val(dob);
            $("#genderMale, #genderFemale").prop('checked', false);
            if (gender === 'male') {
                $("#genderMale").prop('checked', true);
            } else {
                $("#genderFemale").prop('checked', true);
            }
            let modalEl = document.getElementById('editUser');
            let modal = new bootstrap.Modal(modalEl);
            modal.show();

            // ✅ .off('submit') prevents stacking events on multiple clicks
            $("#editForm").off('submit').on('submit', function(e) {
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
                        loadUsers();
                        setTimeout(() => $(".alert-success").addClass("d-none"), 4000);
                    },
                    error: function(err) {
                        console.log(err.responseText);
                        $(".alert-danger").removeClass("d-none");
                        $(".alert-danger").html(`<span>${err.responseText}</span>`);
                        setTimeout(() => $(".alert-danger").addClass("d-none"), 4000);
                    }
                });
            });
        }

        function buildPagination(response) {
            let pagination = '';

            pagination += `
                <li class="page-item ${response.current_page == 1 ? 'disabled' : ''}">
                    <a class="page-link" href="javascript:void(0)"
                        onclick="loadUsers(${response.current_page - 1})">Previous</a>
                </li>`;

            for (let i = 1; i <= response.last_page; i++) {
                pagination += `
                    <li class="page-item ${i == response.current_page ? 'active' : ''}">
                        <a class="page-link" href="javascript:void(0)"
                            onclick="loadUsers(${i})">${i}</a>
                    </li>`;
            }

            pagination += `
                <li class="page-item ${response.current_page == response.last_page ? 'disabled' : ''}">
                    <a class="page-link" href="javascript:void(0)"
                        onclick="loadUsers(${response.current_page + 1})">Next</a>
                </li>`;

            $("#paginationLinks").html(pagination);
        }
    </script>

@endsection
