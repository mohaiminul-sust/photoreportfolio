<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Photo;
use App\Http\Resources\AlbumResource;
use App\Http\Resources\PhotoResource;

class PhotoController extends Controller
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

    public function index() {
        return view('photo.index');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPhotos()
    {
        $photos = Photo::paginate(50);
        return PhotoResource::collection($photos);
    }

    /**
     * Display the specified collection.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        $photo = Photo::find($id);
        return new PhotoResource($photo);
    }

    public function preview($id){
        return view('photo.preview')->with('id', $id);
    }

    public function create() {
        return view('photo.create');
    }

    public function store(Request $request) {
        return $request;
    }

    public function edit($id) {
        return $id;
    }

    public function update(Request $request, $id) {
        return $request;
    }
    
    public function destroy($id) {
        
    }
}
