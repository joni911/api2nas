@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">API Keys Management</h3>
                    <div class="card-tools">
                        <a href="{{ route('api-management.create') }}" class="btn btn-primary">Create New API Key</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>API Key</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($apiKeys as $key)
                            <tr>
                                <td>{{ $key->id }}</td>
                                <td>{{ $key->name }}</td>
                                <td>{{ substr($key->api_key, 0, 10) }}...</td>
                                <td>
                                    <span class="badge bg-{{ $key->is_active ? 'success' : 'danger' }}">
                                        {{ $key->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ $key->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('api-management.show', $key->id) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('api-management.edit', $key->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('api-management.destroy', $key->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this API key?')">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No API keys found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    {{ $apiKeys->links() }}
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
@endsection