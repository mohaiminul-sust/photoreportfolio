@extends('layouts.app')

@section('style')
<link href="{{ asset('css/customcard.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div id="preview-album" class="container">
        @include('flash::message')
        <div v-loading="loading" class="box box-primary">
            <div class="box-header with-border user-block">
                    <img v-img class="img-circle img-bordered-sm" :src="album.cover_image" :alt="album.name">
                    <span class="username">Album : @{{ album.name }}</span>
                    <span class="description">Created @{{ album.created_ago }} at @{{ album.created_date }}</span>
                    <div class="pull-right">
                        <el-button @click="deleteAlbum" type="danger" icon="el-icon-delete"></el-button>
                        <el-button @click="editAlbum" class="pull-right" type="success" icon="el-icon-edit"></el-button>
                    </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="box">
                    <div class="box-header">
                        <strong><i class="fa fa-book margin-r-5"></i> Description</strong>
                    </div>
                    <div class="box-body">
                        <span class="description">@{{ album.description }}</span>
                    </div>
                </div>
                <div v-if="album.photos.length > 0">
                    <div class="box">
                        <div class="box-header">
                            <strong><i class="fa fa-image margin-r-5"></i> Photos</strong>
                            <p class="description">@{{ album.photos.length }} photos in album</p>
                        </div>
                        <div class="box-body">
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
    </div>
@endsection

@section('script')
    var previewalbum = new Vue({
        el: '#preview-album',
        data: {
            album: {},
            loading: true
        }, 
        created(){
            this.fetchAlbum({!! $id !!});
        },
        methods: {
            fetchAlbum: function(id) {
                var link = "{!! url('albums') !!}/" + id;
                console.log("firing " + link);
                this.loading = true;
                axios.get(link)
                .then(function (response) {
                    this.album = response.data.data;
                    this.loading = false;
                }.bind(this))
                .catch(function (error) {
                    console.log(error);
                });
            },
            deleteAlbum: function () {
                var link = "{!! url('albums/delete') !!}/" + this.album.id;
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
            editAlbum: function() {
                var link = "{!! url('albums/update') !!}/" + this.album.id;
                document.location.href = link;
            },
        }
    })
@endsection