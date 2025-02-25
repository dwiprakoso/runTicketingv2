@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Admin Login</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="secret_key" class="form-label">Kunci Rahasia Admin</label>
                        <input type="password" class="form-control" id="secret_key" name="secret_key" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection