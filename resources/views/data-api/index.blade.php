@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Uploaded Data Management</h3>
                    <div class="card-tools d-flex align-items-center gap-2">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search data...">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-default" onclick="clearSearch()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap" id="dataTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>File Name</th>
                                <th>API Key</th>
                                <th>URL</th>
                                <th>IP Address</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @forelse($apiDatas as $data)
                            <tr>
                                <td>{{ $data->id }}</td>
                                <td>{{ $data->nama_file }}</td>
                                <td>{{ $data->apiKey->name ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ $data->url }}" target="_blank" title="View File">View</a>
                                </td>
                                <td>{{ $data->ip_address }}</td>
                                <td>{{ $data->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('data-api.show', $data->id) }}" class="btn btn-info btn-sm">View</a>
                                        <form action="{{ route('data-api.destroy', $data->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this data?')">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No uploaded data found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    {{ $apiDatas->links() }}
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#tableBody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

function clearSearch() {
    document.getElementById('searchInput').value = '';
    const rows = document.querySelectorAll('#tableBody tr');
    rows.forEach(row => row.style.display = '');
}
</script>
@endsection