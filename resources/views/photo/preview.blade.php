@extends('layouts.app')

@section('style')
    <link href="{{ asset('css/formtags.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div id="preview-photo" class="container" v-cloak>
        <div class="box box-primary">
            <div class="box-header with-border user-block">
                <img v-img class="img-circle img-bordered-sm" :src="photo.album.cover_image" :alt="photo.album.name">
                <span class="username">Photo : from album <button @click="showAlbum" class="btn-sm btn-primary">@{{ photo.album.name }}</button></span>
                <span class="description">Created @{{ photo.created_ago }} at @{{ photo.created_date }}</span>
                <span class="description">Updated at @{{ photo.updated_date }}</span>
                <div class="pull-right">
                    <el-button @click="deletePhoto" type="danger" icon="el-icon-delete"></el-button>
                    <el-button @click="editPhoto" class="pull-right" type="success" icon="el-icon-edit"></el-button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="box">
                    <div class="box-header">
                        <strong><i class="fa fa-book margin-r-5"></i> Photo</strong>
                    </div>
                    <div class="box-body">
                        <img v-img class="image-responsive" width=800 height=600 :src="photo.image" :alt="photo.caption" id="photoimg" ref="photoimg">
                    </div>
                </div>
                <div class="box">
                    <div class="box-header">
                        <strong><i class="fa fa-book margin-r-5"></i> Caption</strong>
                    </div>
                    <div class="box-body">
                        <span class="description">@{{ photo.caption }}</span>
                    </div>
                </div>
                <div class="box">
                    <div class="box-header">
                        <strong><i class="fa fa-book margin-r-5"></i> Tags</strong>
                    </div>
                    <div class="box-body">
                        <el-tag v-for="tag in photo.tags" :key="tag.id">@{{ tag.tag }}</el-tag>
                    </div>
                </div>
                <div class="box">
                    <div class="box-header">
                        <strong><i class="fa fa-book margin-r-5"></i> Story</strong>
                    </div>
                    <div class="box-body">
                        <span class="description">@{{ photo.notes }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    var previewphoto = new Vue({
        el: '#preview-photo',
        data: {
            photo: {},
            exif: {}
        }, 
        created() {
            this.fetchPhoto({!! $id !!});
        },
        methods: {
            fetchPhoto: function(id) {
                var link = "{!! url('photos') !!}/" + id;
                console.log("firing " + link)
                axios.get(link)
                .then(function (response) {
                    this.photo = response.data.data;
                }.bind(this))
                .catch(function (error) {
                    console.log(error);
                });
            },
            deletePhoto: function () {
                var link = "{!! url('photos/delete') !!}/" + this.photo.id;
                console.log("firing " + link);
                axios.get(link)
                .then(function (response) {
                    var redirect = "{!! url('photos') !!}";
                    document.location.href = redirect;
                }.bind(this))
                .catch(function (error) {
                    this.formerrors = error;
                });
            },
            editPhoto: function() {
                var link = "{!! url('photos/update') !!}/" + this.photo.id;
                document.location.href = link;
            },
            showAlbum: function() {
                var link = "{!! url('albums/preview') !!}/" + this.photo.album.id;
                document.location.href = link;
            }
        }
    })
@endsection