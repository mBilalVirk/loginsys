@php
    $friends = $data['friends'];
@endphp
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Sender</th>
            <th>Receiver</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($friends as $friend)
            <tr>
                <td>{{ $friend->id }}</td>
                <td>{{ $friend->sender->name }}</td>
                <td>{{ $friend->receiver->name }}</td>
                <td>{{ $friend->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
