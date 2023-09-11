@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Title List</h1>
        <a href="{{ route('addmovie.index') }}" class="btn btn-success">Add New Movie</a>
    </div>
    <table class="table table-dark table-sm">
        <thead>
            <tr>
                <th>Title</th>
                <th>Number of Movies</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($titles as $title)
                <tr>
                    <td>{{ $title->title_name }}</td>
                    <td>{{ $title->movies->count() }}</td>
                    <td>
                        <a href="{{route('addmovie.edit',$title->id)}}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('addmovie.destroy',$title->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
</div>
@endsection
