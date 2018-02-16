@extends('layouts.app')

@section('style')
    <link href="{{ asset('css/createalbumform.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div id="create-album" class="container">
        @include('flash::message')
        @if(count($errors) > 0)
        <div class="alert alert-block alert-error fade in" id="error-block">
            <button type="button" class="close"data-dismiss="alert">×</button>
            <h4>Errors Found!</h4>
            <strong>Warning!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Create Album</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {!! Form::open(['route' => 'album.store', 'files' => true]) !!}
              <div class="box-body">
                <div class="form-group">
                    {!! Form::label('Name') !!}
                    {!! Form::text('name', '', ['class'=>'form-control', 'placeholder'=>'Enter album name']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('Description') !!}
                    {!! Form::textarea('description', '', ['class'=>'form-control', 'placeholder'=>'Enter album description ...']) !!}
                </div>
                <div class="form-group">
                    <label for="coverimage">Cover Image</label>
                    {!! Form::file('cover_image', ['multiple' => false, 'accept'=>'image/*']) !!}
                </div>
                <div class="form-group">
                    <label for="albumphotos">Album Photo(s)</label>
                    {!! Form::file('album_photos[]', ['multiple' => true, 'accept'=>'image/*']) !!}
                </div>
              </div>
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            {!! Form::close() !!}
          </div>
    </div>
@endsection

@section('script')
    $('#flash-overlay-modal').modal();
    $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
@endsection