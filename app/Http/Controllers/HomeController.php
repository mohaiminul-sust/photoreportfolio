<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JavaScript;
use Auth;
use App\Album as Album;
use App\Photo as Photo;
use Carbon\Carbon;

class HomeController extends Controller
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
        return view('home')
        ->with('adminUser', Auth::user())
        ->with('albumsCount', Album::count())
        ->with('photosCount', Photo::count());
    }

    public function albumTimeline() {
        return view('timeline.album');
    }

    public function photoTimeline() {
        return view('timeline.photo');
    }

    public function getTimelineAlbums() {
        $albums = Album::orderBy('created_at','desc')->get()->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('l jS F Y'); 
        });

        return $albums;
    }

    public function getTimelinePhotos() {
        $photos = Photo::orderBy('created_at', 'desc')->get()->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('l jS F Y'); //toFormattedDateString();
        });

        return $photos;
    }
}