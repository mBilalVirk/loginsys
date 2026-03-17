@php
    $users = $data['users'];
@endphp

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>NAME</th>
            <th>EMAIL</th>
            <th>TOTAL NUMBER OF FRIENDS</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->acceptedFriends->count() }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
