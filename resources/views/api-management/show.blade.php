@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">API Key Details</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="id">ID:</label>
                                <input type="text" class="form-control" value="{{ $apiKey->id }}" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" value="{{ $apiKey->name }}" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="api_key">API Key:</label>
                                <input type="text" class="form-control" value="{{ $apiKey->api_key }}" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="is_active">Status:</label>
                                <input type="text" class="form-control" value="{{ $apiKey->is_active ? 'Active' : 'Inactive' }}" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="created_at">Created At:</label>
                                <input type="text" class="form-control" value="{{ $apiKey->created_at->format('Y-m-d H:i:s') }}" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="updated_at">Updated At:</label>
                                <input type="text" class="form-control" value="{{ $apiKey->updated_at->format('Y-m-d H:i:s') }}" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <a href="{{ route('api-management.index') }}" class="btn btn-default">Back to List</a>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
@endsection