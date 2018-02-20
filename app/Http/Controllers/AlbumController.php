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
            'description' => 'required'
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
        return new AlbumResource(Album::find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('album.edit')->with('id', $id);
    }

    public function preview($id) {
        return view('album.preview')->with('id', $id);
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
        $rules = [
            'name' => 'required',
            'description' => 'required'
        ];

        $validator = \Validator::make($request->toArray(), $rules);
        if($validator->fails()){
            return \Redirect::route('album.update', $id)
            ->withErrors($validator)
            ->withInput();
        }

        $album = Album::find($id);

        if ($album) {
            $album->name = $request->name;
            $album->description = $request->description;
            $album->save();
        }
        
        flash('Album updated!')->success();
        return \Redirect::route('album.update', $id);
    }

    public function updateCoverImage(Request $request, $id) {
        
        
        $album = Album::find($id);

        if ($album) {
            if($request->hasFile('file'))
            {
                $file = $request->file('file');

                $destinationPath = public_path(). '/uploads/';
                $filename = $file->getClientOriginalName();
                $file->move($destinationPath, $filename);

                $album->cover_image = url('/').'/uploads/'.$filename;
            }
            $album->save();
        }

        return new AlbumResource($album);
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
        if ($album) {
            $album->delete();
        }
        return response()->json(['Success, 200']);
    }
}
