<table class="table">
    <thead>
        <tr>
            <th>Nazwa</th>
            <th>Data</th>
            <th>Godzina</th>
        </tr>
    </thead>
    <tbody>
        @foreach($events as $event)
        <tr>
            <td>{{ $event->name }}</td>
            <td>{{ \Carbon\Carbon::parse($event->date)->format('d-m-Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($event->start_hour)->format('H:i') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-2">
    {{ $events->links('pagination::bootstrap-4') }}
</div>
