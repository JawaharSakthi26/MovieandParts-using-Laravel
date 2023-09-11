@extends('layouts.app')

@section('content')
<div class="container">
    <form method="POST" action="{{ isset($title) ? route('addmovie.update', $title->id) : route('addmovie.store') }}">
        @csrf
        @if(isset($title))
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-12">
                <!-- Title Textbox -->
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" class="form-control" id="title" value="{{ isset($title) ? $title->title_name : '' }}" required>
                    <input type="hidden" name="title_id" class="form-control" id="title_id" value="{{ isset($title) ? $title->id: '' }}">
                </div>
            </div>
        </div>

        <div id="movies-container" class="row">
            @if(isset($movies))
                @foreach($movies as $movie)
                    <div class="col-md-6 movie-section">
                        <div class="form-group">
                            <label for="movie_name_{{ $movie->id }}">Movie Name</label>
                            <input type="text" name="movie_name[]" class="form-control" id="movie_name_{{ $movie->id }}" value="{{ $movie->movie_name }}" required>
                            <input type="hidden" name="movie_id[]" class="form-control" id="movie_id_{{ $movie->id }}" value="{{ $movie->id }}" required>
                        </div>
                        <div class="parts-container">
                            @foreach($parts->where('movie_id', $movie->id) as $part)
                                <div class="part">
                                    <input type="text" name="part_name[{{ $movie->id }}][]" class="form-control mt-2" value="{{ $part->part_name }}" placeholder="Part Name" required>
                                    <input type="hidden" name="part_id[{{ $movie->id }}][]" class="form-control mt-2" value="{{ $part->id }}" placeholder="Part id" required>
                                    <button type="button" class="btn btn-danger remove-part mt-1">Remove Part</button>
                                </div>
                            @endforeach
                        </div>
                        <div class="my-3">
                            <button type="button" class="btn btn-primary add-part">Add Part</button>
                            <button type="button" class="btn btn-danger remove-movie">Remove Movie</button>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <button type="button" id="add-movie" class="btn btn-primary">Add Movie</button>
                <button type="submit" class="btn btn-success">{{ isset($title) ? 'Update' : 'Submit' }}</button>
            </div>
        </div>
    </form>
</div>

<script>
   document.addEventListener("DOMContentLoaded", function () {
    const moviesContainer = document.getElementById("movies-container");
    const addMovieButton = document.getElementById("add-movie");

    addMovieButton.addEventListener("click", function () {
        const movieSection = createMovieSection();
        moviesContainer.appendChild(movieSection);
    });

    function createMovieSection() {
        const movieCount = moviesContainer.children.length; // Current movie count

        const movieSection = document.createElement("div");
        movieSection.className = "col-md-6 movie-section";
        movieSection.innerHTML = `
            <div class="form-group">
                <label for="movie_name_${movieCount}">Movie Name</label>
                <input type="text" name="movie_name[]" class="form-control" id="movie_name_${movieCount}" required>
            </div>
            <div class="parts-container">
                <!-- Parts Will Be Appended Here -->
            </div>
            <div class="my-3">
                <button type="button" class="btn btn-primary add-part">Add Part</button>
                <button type="button" class="btn btn-danger remove-movie">Remove Movie</button>
            </div>
        `;

        const addPartButton = movieSection.querySelector(".add-part");
        const removeMovieButton = movieSection.querySelector(".remove-movie");

        addPartButton.addEventListener("click", function () {
            const partsContainer = movieSection.querySelector(".parts-container");
            const partInput = document.createElement("div");
            partInput.innerHTML = `
                <input type="text" name="part_name[${movieCount}][]" class="form-control mt-2" placeholder="Part Name" required>
                <button type="button" class="btn btn-danger remove-part mt-1">Remove Part</button>
            `;
            partsContainer.appendChild(partInput);

            const removePartButton = partInput.querySelector(".remove-part");
            removePartButton.addEventListener("click", function () {
                partsContainer.removeChild(partInput);
            });
        });
        removeMovieButton.addEventListener("click", function () {
            moviesContainer.removeChild(movieSection);
        });
        return movieSection;
    }

    // Initialize event listeners for existing movie sections
    const existingMovieSections = document.querySelectorAll(".movie-section");
    existingMovieSections.forEach(function (movieSection) {
        initializeMovieSection(movieSection);
    });

    function initializeMovieSection(movieSection) {
        const addPartButton = movieSection.querySelector(".add-part");
        const removeMovieButton = movieSection.querySelector(".remove-movie");

        addPartButton.addEventListener("click", function () {
            const partsContainer = movieSection.querySelector(".parts-container");
            const partInput = document.createElement("div");
            partInput.innerHTML = `
                <input type="text" name="part_name[]" class="form-control mt-2" placeholder="Part Name" required>
                <button type="button" class="btn btn-danger remove-part mt-1">Remove Part</button>
            `;
            partsContainer.appendChild(partInput);
            

            const removePartButton = partInput.querySelector(".remove-part");
            removePartButton.addEventListener("click", function () {
                partsContainer.removeChild(partInput);
            });
        });
        removeMovieButton.addEventListener("click", function () {
            moviesContainer.removeChild(movieSection);
        });
    }
});
</script>

@endsection
