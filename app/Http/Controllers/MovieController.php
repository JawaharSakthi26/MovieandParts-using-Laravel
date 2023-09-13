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
        // dd($request);
        // $titleName = $request->title_name;
        // $existingTitle = Title::where('title_name', $titleName)->first();

        // if ($existingTitle) {
        //     $title = $existingTitle;
        // } else {
        //     $title = Title::create([
        //         'title_name' => $titleName,
        //     ]);
        // }
        $title = Title::create([
            'title_name' => $request->title_name,
        ]);
        $moviesData = $request->movies;
        foreach ($moviesData as $movieData) {
            $movie = $title->movies()->create([
                'title_id' => $title->id, 
                'movie_name' => $movieData['name'],
            ]);

            if (isset($movieData['parts']) && is_array($movieData['parts']['partName']) && count($movieData['parts']['partName']) > 0) {
                foreach ($movieData['parts']['partName'] as $partName) {
                    $movie->parts()->create([
                        'movie_id' => $movie->id,
                        'part_name' => $partName,
                    ]);
                }
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
        $title = Title::with('movies.parts')->findOrFail($id);
        $data = [
            'title' => $title,
            'movies' => $title->movies,
        ];
        return view('app.index', compact('data'));
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $title = Title::findorFail($id);
        $title->update([
            'title_name' => $request->title_name,
        ]);

        $updatedMovieIds = [];
        $updatedPartIds = [];

        foreach ($request->input('movies') as $movieData) {
            if (isset($movieData['id'])) {
                $movie = Movie::findOrFail($movieData['id']);
                $movie->update([
                    'movie_name' => $movieData['name'],
                ]);
                $updatedMovieIds[] = $movie->id;
            } else {
                $movie = Movie::create([
                    'title_id' => $title->id,
                    'movie_name' => $movieData['name'],
                ]);
                $updatedMovieIds[] = $movie->id;
            }
            if (isset($movieData['parts'])) {
                foreach ($movieData['parts']['partName'] as $key => $partName) {
                    $partData = [
                        'movie_id' => $movie->id,
                        'part_name' => $partName,
                    ];

                    if (isset($movieData['parts']['partId'][$key])) {
                        $partData['id'] = $movieData['parts']['partId'][$key];
                        $part = Part::findOrFail($partData['id']);
                        $part->update($partData);
                        $updatedPartIds[] = $part->id;
                    } else {
                        $part =  Part::create($partData);
                        $updatedPartIds[] = $part->id;
                    }
                }
            }
        }
 
        $title->movies()->whereNotIn('id', $updatedMovieIds)->delete();

        $title->movies->each(function ($movie) use ($updatedPartIds) {
            $movie->parts()->whereNotIn('id', $updatedPartIds)->delete();
        });
 
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
