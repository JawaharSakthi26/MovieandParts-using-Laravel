@extends('layouts.app')
@section('content')
<div class="container mt-5">
    @if (isset($data))
        <h1>Edit Movie</h1>
        <form method="POST" action="{{ route('addmovie.update', $data['title']->id) }}" id="movie-form">
        @method('PUT')
    @else
        <h1>Add Movies</h1>
        <form method="POST" action="{{ route('addmovie.store') }}" id="movie-form">
    @endif
    
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Title:</label>
            <input type="text" id="title" name="title_name" class="form-control" value="{{ isset($data['title']) ? $data['title']->title_name : '' }}" required>
            <span id="title-error" class="text-danger"></span>
        </div>
        <div id="movies-container">

        </div>
        <button type="button" id="add-movie" class="btn btn-primary mb-3">Add Movie</button>
        <button type="submit" class="btn btn-success mb-3" id="submit-btn">Submit</button>
    </form>
</div>

<script>
    @if(isset($data))
        var movieData = @json($data['movies']);
        $(document).ready(function() {
            movieData.forEach(function(movie) {
                addMovieAndParts(movie);
            });
        });
    @endif

    let movieCount = 0;
    
    function addMovieAndParts(movieData) {
        movieCount++;
        const movieId = movieCount; 
        console.log(movieId);
        const movieField = `
            <div class="movie mb-3 p-3 border" id="movie-${movieId}">
                <label for="movie-name-${movieId}" class="form-label">Movie Name:</label>
                <input type="text" id="movie-name-${movieId}" name="movies[${movieId}][name]" value="${movieData ? movieData.movie_name : ''}" class="form-control" required>
                <input type="hidden" id="movie-id-${movieId}" name="movies[${movieId}][id]" value="${movieData ? movieData.id : ''}" class="form-control" required>
                <button type="button" class="add-part btn btn-primary mt-2" data-movieId="${movieId}">Add Part</button>
                <button type="button" class="remove-movie btn btn-danger mt-2">Remove Movie</button>
                <div class="error-message text-danger" id="movie-error-${movieId}"></div>
                <div class="parts-container mt-2" id="parts-container-${movieId}"></div>
                
            </div>
        `;
        $('#movies-container').append(movieField);
        
        // Add validation rules for the movie name
        $(`#movie-name-${movieId}`).rules('add', {
            required: true,
            messages: {
                required: 'Movie name is required.'
            }
        });
        validateParts(movieId);

        @if (isset($data))
        if (movieData.parts && movieData.parts.length > 0) {
            movieData.parts.forEach(function(partData) {
                addPart(movieId, partData);
            });
        }
        @endif
    }

    function addPart(movieId, partData) {
        console.log(movieId);
        const partField = `
            <div class="part mb-2">
                <label for="part-${movieId}-name" class="form-label">Part Name:</label>
                <input type="text" id="part-${movieId}-name" name="movies[${movieId}][parts][partName][]" value="${partData ? partData.part_name : ''}" class="form-control" required>
                <input type="hidden" id="part-${movieId}-id" name="movies[${movieId}][parts][partId][]" value="${partData ? partData.id : ''}" class="form-control" required>
                <button type="button" class="remove-part btn btn-danger mt-2" data-movieId=${movieId}>Remove Part</button>
                <div class="error-message text-danger" id="movie-error-${movieId}"></div>
            </div>
        `;
        $(`#parts-container-${movieId}`).append(partField);
        
        // Add validation rule for the part name
        $(`#part-${movieId}-name`).rules('add', {
            required: true,
            messages: {
                required: 'Part name is required.'
            }
        });
        validateParts(movieId);
    }

    $('#submit-btn').click(function(){
        validateParts(); 
    })

    $('#add-movie').click(function() {
        addMovieAndParts();
    });

    $('#movies-container').on('click', '.add-part', function() {
        const movieId = $(this).closest('.movie').attr('id').split('-')[1];
        addPart(movieId);
        validateParts();
    });

    $('#movies-container').on('click', '.remove-movie', function() {
        $(this).parent().remove();
    });

    $('#movies-container').on('click', '.remove-part', function() {
        $(this).parent().remove();
        validateParts();
    });

    $('#movie-form').validate({
        rules: {
            'title_name': {
                required: true
            }
        },
        messages: {
            'title_name': {
                required: 'Title field is required.'
            }
        },

        submitHandler: function(form) {
            const movieContainers = $('.movie');
            let allMoviesValid = true;

            movieContainers.each(function() {
                const movieId = $(this).attr('id').split('-')[1];
                const partCount = $(`#parts-container-${movieId} .part`).length;

                if (partCount === 0) {
                    allMoviesValid = false;
                    $(`#movie-error-${movieId}`).html('At least one part is required for this movie.').show();
                } else {
                    $(`#movie-error-${movieId}`).html('').hide();
                }
            });

            if (allMoviesValid) {
                form.submit();
            }
        }
    });

    function validateParts(movieId) {
        if (movieId) {
            const partCount = $(`#parts-container-${movieId} .part`).length;
            if (partCount === 0) {
                $(`#movie-error-${movieId}`).html('At least one part is required for this movie.').show();
            } else {
                $(`#movie-error-${movieId}`).html('').hide();
            }
        } else {
            const movieContainers = $('.movie');
            if (movieContainers.length === 0) {
                $('#movies-container').append('<div class="text-danger error">Select at least one movie.</div>');
            } else {
                $('#movies-container .error').remove();
            }
        }
    }
</script>
@endsection