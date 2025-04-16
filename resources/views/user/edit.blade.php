@extends('layouts.template')
@section('content')
<div class="col-lg-12">
    <div class="card mb-2 p-5">
        <form class="row g-3" method="POST" action="{{ route('user.update', $users['id']) }}">
            @csrf
            @method('PATCH')
            @if (Session::get('success'))
                <div class="alert alert-success"> {{ Session::get('success') }}</div>
            @endif
            @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            <div class="col-md-6">
                <label for="name" class="form-label">Nama</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $users['name'] }}">
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $users['email'] }}">
            </div>
            <div class="col-md-6 mt-5">
                <label for="role" class="form-label">Role</label>
                <select id="role" name="role" class="form-control">
                    <option selected>Pilih</option>
                    <option value="admin" {{ $users['role'] == 'admin' ? 'selected' : ''}}>Admin</option>
                    <option value="employee" {{ $users['role'] == 'employee' ? 'selected' : ''}}>Petugas</option>
                </select>
            </div>
            <div class="col-md-6 mt-5">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
            </div>
            <div class="col-12 mt-3">
                <button type="submit" class="px-5 btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection
