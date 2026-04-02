@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Dashboard : Friends</h3>
        <div>
            <a class="btn btn-warning text-white" id="downloadPdf">
                <i class="fa-solid fa-file-pdf"></i> Download PDF
            </a>

            <a class="btn btn-success text-white" id="downloadExcel">
                <i class="fa-solid fa-file-excel"></i> Download Excel
            </a>
        </div>
        <script>
            $("#downloadPdf").click(function() {
                const search = $("#searchInput").val();
                const dateFrom = $("#dateFrom").val();
                const dateTo = $("#dateTo").val();
                const sort = $("#sortInput").val();

                const url =
                    `/admin/friend/friendpdf?search=${search}&date_from=${dateFrom}&date_to=${dateTo}&sort=${sort}`;
                window.open(url, '_blank'); // opens PDF in a new tab
            });

            $("#downloadExcel").click(function() {
                const search = $("#searchInput").val();
                const dateFrom = $("#dateFrom").val();
                const dateTo = $("#dateTo").val();
                const sort = $("#sortInput").val();

                const url =
                    `/admin/friend/friendexport?search=${search}&date_from=${dateFrom}&date_to=${dateTo}&sort=${sort}`;
                window.open(url, '_blank'); // opens Excel in a new tab
            });
        </script>
    </div>
    <div id="successAlert" class="alert alert-success d-none"></div>
    <div id="errorAlert" class="alert alert-danger d-none"></div>
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
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Sender</th>
                    <th>Receiver</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="friendsTable">
                {{-- @foreach ($friends as $friend)
                        <tr>
                            <td>{{ $friend->id }}</td>
                            <td>{{ $friend->sender->name }}</td>
                            <td>{{ $friend->receiver->name }}</td>
                            <td>
                                @if ($friend->status == 'accepted')
                                    <span style="background: green; color: white; padding: 2px 5px; border-radius: 3px;">Friends
                                @else
                                    <span style="background: yellow; color: white; padding: 2px 5px; border-radius: 3px;">{{ $friend->status }}</span>
                                @endif
                            </td>

                            <td>
                                <form action="{{route('admin.deleteFriend',$friend->id)}}" method="post"
                                onsubmit="return confirm('Are you sure you want to delete this friends record?')">
                                @csrf
                                @method("DELETE")
                                <button type="submit" class="btn btn-danger">
                                    Delete
                                </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach --}}
            </tbody>
        </table>
    </div>



    <script>
        $(document).ready(function() {
            loadFriendShip();

            function loadFriendShip() {
                let rows = '';
                const search = $("#searchInput").val().trim();
                const dateFrom = $("#dateFrom").val();
                const dateTo = $("#dateTo").val();
                const sort = $("#sortInput").val();

                $.ajax({
                    url: `/admin/friends`,
                    type: "GET",
                    data: {
                        search: search,
                        date_from: dateFrom,
                        date_to: dateTo,
                        sort: sort
                    },
                    success: function(data) {
                        if (data.length === 0) {
                            rows = `<tr><td colspan="5">No records found.</td></tr>`;
                        } else {
                            data.forEach(function(friend) {
                                rows += `
                        <tr>
                            <td>${friend.id}</td>
                            <td>${friend.sender.name}</td>
                            <td>${friend.receiver.name}</td>
                            <td>
                                <span style="
                                    background: ${friend.status === 'accepted' ? 'green' : 'yellow'};
                                    color: white;
                                    padding: 2px 5px;
                                    border-radius: 3px;">
                                    ${friend.status === 'accepted' ? 'Friends' : friend.status}
                                </span>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-danger" onclick="deleteFriendShip(${friend.id})">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    `;
                            });
                        }
                        $("#friendsTable").html(rows);
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
                    loadFriendShip();
                }, 400);
            });
            // Trigger search on input or change
            $(" #dateFrom, #dateTo, #sortInput").on('input change', function() {
                loadFriendShip();
            });

            function deleteFriendShip(id) {

                if (confirm("Are you sure you want to delete this Friendship?")) {
                    $.ajax({
                        url: `/admin/friend/delete/${id}`,
                        type: "DELETE",
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $(".alert-success").removeClass("d-none").html(
                                `<span>${response.res}</span>`).fadeIn();

                            loadFriendShip();
                            setTimeout(() => {
                                $(".alert-success").addClass("d-none").fadeOut();
                            }, 4000);
                        },
                        error: function(err) {
                            console.log(err.responseText);
                        }

                    });

                }

            }
        });
    </script>

@endsection
