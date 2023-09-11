<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Part;
use App\Models\Title;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('app.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $titleName = $request->title;
        $existingTitle = Title::where('title_name', $titleName)->first();
        if ($existingTitle) {
            $title = $existingTitle;
        } else {
            $title = Title::create([
                'title_name' => $titleName,
            ]);
        }
        $moviesData = $request->movie_name;
        foreach ($moviesData as $k => $movieData) {
            $movie = Movie::create([
                'title_id' => $title->id,
                'movie_name' => $movieData,
            ]);
            foreach ($request->part_name[$k] as $part) {
                Part::create([
                    'movie_id' => $movie->id,
                    'part_name' => $part,
                ]);
            }
        }
    
        return redirect()->route('listmovie.index');
    }
    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Fetch the title, movies, and parts data based on the ID
        $title = Title::findOrFail($id);
        $movies = Movie::where('title_id', $id)->get();
        $parts = Part::whereIn('movie_id', $movies->pluck('id'))->get();
    
        return view('app.index', compact('title', 'movies', 'parts'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
    $title = Title::updateOrCreate(
        ['id' => $id],
        ['title_name' => $request->input('title')]
    );

    $movieIds = $request->input('movie_id');
    Movie::where('title_id', $title->id)->whereNotIn('id', $movieIds)->delete();

    foreach ($request->input('movie_name') as $index => $movieName) {
        $movieId = $request->input("movie_id.$index");
    
        $movie = Movie::updateOrCreate(
            ['id' => $movieId, 'title_id' => $title->id],
            ['movie_name' => $movieName]
        );

        $partNames = $request->input("part_name.$movieId");
        $partIds = $request->input("part_id.$movieId");
    
        if (is_array($partNames)) {
            $existingPartIds = $movie->parts()->pluck('id')->toArray();
            $partsToDelete = array_diff($existingPartIds, $partIds);
            Part::whereIn('id', $partsToDelete)->delete();
    
            foreach ($partNames as $partIndex => $partName) {
                $partId = $partIds[$partIndex] ?? null;
                if ($partId === null) {
                    Part::create([
                        'movie_id' => $movie->id,
                        'part_name' => $partName,
                    ]);
                } else {
                    $part = Part::updateOrCreate(
                        ['id' => $partId, 'movie_id' => $movie->id],
                        ['part_name' => $partName]
                    );
                }
            }
        }
    }
    
    return redirect()->route('listmovie.index');
}

    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $title = Title::find($id);
        if ($title) {
            $title->delete();
        }
        return redirect()->route('listmovie.index');
    }
}
