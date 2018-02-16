<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Album;
use App\Http\Resources\AlbumResource;

class AlbumController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('album.index');
    }

    /**
     * Display the specified collection.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAlbums()
    {   
        $albums = Album::paginate(20);
        // return $albums;
        return AlbumResource::collection($albums);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('album.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'cover_image'=>'required|image'
        ];

        $validator = \Validator::make($request->toArray(), $rules);
        if($validator->fails()){
            return \Redirect::route('album.create')
            ->withErrors($validator)
            ->withInput();
        }

        $album = new Album();
        $album->name = $request->name;
        $album->description = $request->description;
        
        if($request->hasFile('cover_image'))
        {
            $file = $request->file('cover_image')->store('albums/cover');
            $album->cover_image = $file;
        }

        $album->save();
        flash('Album created!')->success();

        return \Redirect::route('album.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new AlbumResource(Album::find($id));;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $album = Album::find($id);
        $album->delete();

        $albums = Album::paginate(20);
        return AlbumResource::collection($albums);
    }
}
