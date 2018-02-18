@extends('layouts.app')

@section('style')
<link href="{{ asset('css/albumcard.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div id="preview-album" class="container">
        @include('flash::message')
        <div class="box box-primary">
            <div class="box-header with-border">
                <el-header>
                    <h2 class="box-title center">Preview '@{{ album.name }}'</h2>
                    <div class="pull-right">
                        <el-button @click="deleteAlbum(album)" type="danger" icon="el-icon-delete"></el-button>
                        <el-button @click="editAlbum(album)" class="pull-right" type="success" icon="el-icon-edit"></el-button>
                    </div>
                </el-header>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="post">
                    <div class="user-block">
                        <img v-img class="img-circle img-bordered-sm" :src="album.cover_image" :alt="album.name">
                        <span class="username">Created at @{{ album.created_date }}</span>
                        <span class="description">@{{ album.description }}</span>
                    </div>
                    <!-- /.user-block -->
                <div v-if="album.photos.length > 0">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Album photos</h3>
                            <p class="description">@{{ album.photos.length }} photos in album</p>
                        </div>
                        <el-carousel :interval="4000" type="card" height="400px">
                            <el-carousel-item v-for="photo in album.photos" :key="photo">
                                <img v-img:group :src="photo.image" :alt="photo.caption">
                            </el-carousel-item>
                        </el-carousel>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    var previewalbum = new Vue({
        el: '#preview-album',
        data: {
            album: {}
        }, 
        created(){
            this.fetchAlbum({!! $id !!});
        },
        methods: {
            fetchAlbum: function(id) {
                var link = "{!! url('albums') !!}/" + id;
                console.log("firing " + link)
                axios.get(link)
                .then(function (response) {
                    console.log(response);
                    this.album = response.data.data;
                }.bind(this))
                .catch(function (error) {
                    console.log(error);
                });
            },
            deleteAlbum: function (album) {
                var link = "{!! url('albums/delete') !!}/" + album.id;
                console.log("firing " + link);
                axios.get(link)
                .then(function (response) {
                    var redirect = "{!! url('albums') !!}";
                    document.location.href = redirect;
                }.bind(this))
                .catch(function (error) {
                    this.formerrors = error;
                });
            },
            editAlbum: function(album) {
                var link = "{!! url('albums/update') !!}/" + album.id;
                document.location.href = link;
            },
        }
    })
@endsection