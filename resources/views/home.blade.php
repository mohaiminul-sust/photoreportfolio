@extends('layouts.app')

@section('content')
<div class="container">
    <div id="home">
            <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">Dashboard</div>
            
                            <div class="panel-body">
                                @if (session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif
                                Welcome, @{{ user.name }}. You are logged in!
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="row">
                    <a href="{{ route('album.index') }}">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                              <span class="info-box-icon bg-aqua"><i class="ion ion-disc"></i></span>
                  
                              <div class="info-box-content">
                                <span class="info-box-text">Albums</span>
                                <span class="info-box-number">@{{ albumsCount }}</span>
                              </div>
                              <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                    </a>
                    <a href="{{ route('photo.index') }}">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                            <span class="info-box-icon bg-red"><i class="ion ion-cube"></i></span>
                
                            <div class="info-box-content">
                                <span class="info-box-text">Photos</span>
                                <span class="info-box-number">@{{ photosCount }}</span>
                            </div>
                            <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                    </a>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                          <span class="info-box-icon bg-yellow"><i class="ion ion-quote"></i></span>
            
                          <div class="info-box-content">
                            <span class="info-box-text">Portfolio</span>
                            <a href="{{ route('home') }}" ><span class="info-box-number"><small>Manage portfolio</small></span></a>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                </div>
    </div> 
    
</div>
@endsection

@section('script')
    var home = new Vue({
        el: '#home',
        data: {
            loadingDate: new Date().toLocaleString(),
            albumsCount: {!! $albums->count() !!},
            photosCount: {!! $photos->count() !!},
            user: {!! json_encode($adminUser->toArray()) !!},
            albums: {!! json_encode($albums->toArray()) !!},
            photos: {!! json_encode($photos->toArray()) !!}
        }
    })
@endsection