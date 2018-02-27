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
        $photos = Photo::orderBy('created_at','desc')->paginate(25);
        return PhotoResource::collection($photos);
    }

    public function searchPhotos(Request $request) {
        $query = strtolower($request->input('query'));
        $photos = Photo::SearchByKeyword($query)->orderBy('created_at','desc')->paginate(25);
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

    public function getPhotosByAlbum($id) {
        $photos = Photo::where('album_id', $id)->orderBy('created_at','desc')->paginate(15);
        return PhotoResource::collection($photos);
    }

    public function uploadimage() {
        return view('photo.upload');
    }

    public function uploadImageByAlbum($id) {
        return view('photo.uploadbyalbum')->with('id', $id);    
    }

    public function store(Request $request, $id) {
        if($request->hasFile('file'))
        {
            $file = $request->file('file');
            $destinationPath = public_path().'/uploads/albums/'.$id.'/photos/';
            $filename = $file->getClientOriginalName();
            $file->move($destinationPath, $filename);

            $photo = new Photo();
            $photo->image = url('/').'/uploads/albums/'.$id.'/photos/'.$filename;
            $photo->album_id = $id;
            $photo->save();

            return new PhotoResource($photo);
        }
    }

    public function preview($id){
        return view('photo.preview')->with('id', $id);
    }

    public function edit($id) {
        return view('photo.edit')->with('id', $id);
    }

    public function update(Request $request, $id) {
        $rules = [
            'caption' => 'required'
        ];

        $validator = \Validator::make($request->toArray(), $rules);
        if($validator->fails()){
            return \Redirect::route('photo.update', $id)
            ->withErrors($validator)
            ->withInput();
        }

        $photo = Photo::find($id);

        if ($photo) {
            $photo->caption = $request->caption;
            if ($request->has('notes')) {
                $photo->notes = $request->notes;
            }
            $photo->save();
        }
        
        flash('Photo updated!')->success();
        return redirect()->route('photo.update', $id);
    }
    
    public function destroy($id) {
        $photo = Photo::find($id);
        if ($photo) {
            $photo->delete();
        }
        return response()->json(['Success, 200']);
    }

    public function updateImage(Request $request, $id) {
        $photo = Photo::find($id);

        if ($photo) {
            if($request->hasFile('file'))
            {
                $file = $request->file('file');

                $destinationPath = public_path().'/uploads/albums/'.$id.'/photos/';
                $filename = $file->getClientOriginalName();
                $file->move($destinationPath, $filename);

                $photo->image = url('/').'/uploads/albums/'.$id.'/photos/'.$filename;
                $photo->save();
            }
        }

        return new PhotoResource($photo);
    }
}
