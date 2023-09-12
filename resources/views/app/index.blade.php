@extends('layouts.app')
@section('content')
<div class="container mt-5">
    @if (isset($data))
        <h1>Edit Movie</h1>
        <form method="POST" action="{{ route('addmovie.update', $data['title']->id) }}">
        @method('PUT')
        <script>
            var data = @json($data);
            console.log(data['movies']);
        </script>
    @else
        <h1>Add Movies</h1>
        <form method="POST" action="{{ route('addmovie.store') }}">
    @endif
    
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Title:</label>
            <input type="text" id="title" name="title_name" class="form-control" value="{{ isset($data['title']) ? $data['title']->title_name : '' }}" required>
            <input type="hidden" id="title_id" name="title_id" class="form-control" value="{{ isset($data['title']) ? $data['title']->id : '' }}" required>
        </div>
        <div id="movies-container">

        </div>
        <button type="button" id="add-movie" class="btn btn-primary mb-3">Add Movie</button>
        <button type="submit" class="btn btn-success mb-3">Submit</button>
    </form>
</div>

<script>
    let movieCount = 0;
    $(document).ready(function() {
        // jQuery code for adding/removing movie parts
        $('#add-movie').click(function() {
            addMovieAndParts({ movie_name: '', parts: [] });
        });
        $('#movies-container').on('click', '.add-part', function() {
            const movieId = $(this).closest('.movie').attr('id').split('-')[1];
            addPart(movieId);
        });
        $('#movies-container').on('click', '.remove-movie', function() {
            $(this).closest('.movie').remove();
        });
        $('#movies-container').on('click', '.remove-part', function() {
            $(this).closest('.part').remove();
        });
    });

    function addMovieAndParts(movieData) {
        movieCount++;
        const movieId = movieCount; 
        const movieField = `
            <div class="movie mb-3 p-3 border" id="movie-${movieId}">
                <label for="movie-name-${movieId}" class="form-label">Movie Name:</label>
                <input type="text" id="movie-name-${movieId}" name="movies[${movieId}][name]" value="${movieData.movie_name || ''}" class="form-control" required>
                <input type="hidden" id="movie-id-${movieId}" name="movies[${movieId}][id]" value="${movieData.id || ''}" class="form-control" required>
                <button type="button" class="add-part btn btn-primary mt-2" data-movieId="${movieId}">Add Part</button>
                <button type="button" class="remove-movie btn btn-danger mt-2">Remove Movie</button>
                <div class="parts-container mt-2" id="parts-container-${movieId}"></div>
            </div>
        `;
        $('#movies-container').append(movieField);

        if (movieData.parts && movieData.parts.length > 0) {
            movieData.parts.forEach(function(partData) {
                addPart(movieId, partData);
            });
        }
    }

    function addPart(movieId, partData) {
        console.log(movieId);
        const partField = `
            <div class="part mb-2">
                <label for="part-${movieId}-name" class="form-label">Part Name:</label>
                <input type="text" id="part-${movieId}-name" name="movies[${movieId}][parts][partName][]" value="${partData ? partData.part_name : ''}" class="form-control" required>
                <input type="hidden" id="part-${movieId}-id" name="movies[${movieId}][parts][partId][]" value="${partData ? partData.id : ''}" class="form-control" required>
                <button type="button" class="remove-part btn btn-danger mt-2">Remove Part</button>
            </div>
        `;
        $(`#parts-container-${movieId}`).append(partField);
    }

    @if (isset($data))
        @foreach ($data['movies'] as $movieData)
            addMovieAndParts(@json($movieData));
        @endforeach
    @endif
    
</script>
@endsection