@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Uploaded Data Details</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="id">ID:</label>
                                <input type="text" class="form-control" value="{{ $apiData->id }}" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="nama_file">File Name:</label>
                                <input type="text" class="form-control" value="{{ $apiData->nama_file }}" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="api_key_name">API Key Name:</label>
                                <input type="text" class="form-control" value="{{ $apiData->apiKey->name ?? 'N/A' }}" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="url">URL:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $apiData->url }}" readonly>
                                    <div class="input-group-append">
                                        <a href="{{ $apiData->url }}" target="_blank" class="btn btn-primary">Open</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="id_tabel">ID Tabel:</label>
                                <input type="text" class="form-control" value="{{ $apiData->id_tabel }}" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="tabel_name">Tabel Name:</label>
                                <input type="text" class="form-control" value="{{ $apiData->tabel_name }}" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="ip_address">IP Address:</label>
                                <input type="text" class="form-control" value="{{ $apiData->ip_address }}" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="created_at">Created At:</label>
                                <input type="text" class="form-control" value="{{ $apiData->created_at->format('Y-m-d H:i:s') }}" readonly>
                            </div>
                        </div>
                        
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Preview:</label>
                                <div class="text-center">
                                    @if(preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $apiData->nama_file))
                                        <img src="{{ $apiData->url }}" alt="Preview" class="img-fluid img-thumbnail" style="max-height: 300px;">
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fas fa-file"></i> File Preview Not Available
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <a href="{{ route('data-api.index') }}" class="btn btn-default">Back to List</a>
                        <form action="{{ route('data-api.destroy', $apiData->id) }}" method="POST" class="d-inline float-right">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this data?')">Delete</button>
                        </form>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
@endsection