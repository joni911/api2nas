@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">API Key Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <p><strong>ID:</strong> {{ $apiKey->id }}</p>
                            <p><strong>Name:</strong> {{ $apiKey->name }}</p>
                            <p><strong>API Key:</strong> <code>{{ $apiKey->api_key }}</code></p>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-{{ $apiKey->is_active ? 'success' : 'danger' }}">
                                    {{ $apiKey->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                            <p><strong>Total Files:</strong> {{ $apiKey->api_data_count }}</p>
                            <p><strong>Created At:</strong> {{ $apiKey->created_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <a href="{{ route('api-management.index') }}" class="btn btn-default">Back to List</a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Uploaded Files for this API Key</h3>
                    <div class="card-tools d-flex align-items-center gap-2">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search files...">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-default" onclick="clearSearch()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap" id="dataTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>File Name</th>
                                <th>URL</th>
                                <th>IP Address</th>
                                <th>Table Name</th>
                                <th>Table ID</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @forelse($apiData as $data)
                            <tr>
                                <td>{{ $data->id }}</td>
                                <td>{{ $data->nama_file }}</td>
                                <td>
                                    <a href="{{ $data->url }}" target="_blank" class="text-primary">View</a>
                                </td>
                                <td>{{ $data->ip_address }}</td>
                                <td>{{ $data->tabel_name ?? '-' }}</td>
                                <td>{{ $data->id_tabel ?? '-' }}</td>
                                <td>{{ $data->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    <a href="{{ $data->url }}" target="_blank" class="btn btn-info btn-sm">View File</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No files uploaded for this API key</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $apiData->links() }}
                </div>
            </div>
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
