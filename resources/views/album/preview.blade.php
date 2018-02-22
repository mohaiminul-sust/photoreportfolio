@extends('layouts.app')

@section('style')
<link href="{{ asset('css/customcard.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div id="preview-album" class="container" v-cloak>
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
                        <strong><i class="fa fa-book margin-r-5"></i> Cover Image</strong>
                    </div>
                    <div class="box-body">
                        <img class="image-responsive" width=200 height=200 :src="album.cover_image" :alt="album.name">
                    </div>
                </div>
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
                            <strong><i class="fa fa-image margin-r-5"></i> Photos (@{{ photos.meta.total }})</strong>
                            <span class="description" style="margin-left: 20px">@{{ photos.meta.from }} - @{{ photos.meta.to }} of @{{ photos.meta.total }} photos</span>  
                        </div>
                        <div class="box-body">
                            <el-row>
                                <div class="block text-center">
                                    <el-pagination
                                    layout="prev, pager, next"
                                    :total="photos.meta.total"
                                    :page-size="photos.meta.per_page"
                                    :current-page.sync="photos.meta.current_page"
                                    @current-change="handlePhotosPageChange">
                                    </el-pagination>
                                </div>
                            </el-row>
                            <el-row>
                                <div class="center">
                                    <el-col class="cardbody" :span="4" v-for="photo in photos.data" :key="photo">
                                        <el-card :body-style="{ padding: '0px' }">
                                        <img v-img:group v-bind:src="photo.image" width="200" height="200" v-bind:alt="photo.caption" class="image">
                                        <div style="padding: 14px;">
                                            <span>@{{ trimmedText(photo.caption, 17) }}</span>
                                            <div class="bottom clearfix">
                                            <time class="time">
                                                <i class="el-icon-time"></i>
                                                <span style="margin-left: 10px">@{{ photo.created_date }}</span>
                                            </time>
                                            <el-button @click="viewPhoto(photo)" class="button" type="primary" icon="el-icon-view"></el-button>
                                            <el-button @click="editPhoto(photo)" class="button pull-right" type="danger" icon="el-icon-edit"></el-button>
                                            </div>
                                        </div>
                                        </el-card>
                                    </el-col>
                                </div>
                            </el-row>
                            <el-row class="box">
                                <div class="block text-center">
                                    <el-pagination
                                    layout="prev, pager, next"
                                    :total="photos.meta.total"
                                    :page-size="photos.meta.per_page"
                                    :current-page="photos.meta.current_page"
                                    @current-change="handlePhotosPageChange">
                                    </el-pagination>
                                </div>
                            </el-row>
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
            photos: {},
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
                    this.fetchPhotosByAlbum(this.album.id);
                }.bind(this))
                .catch(function (error) {
                    console.log(error);
                });
            },
            fetchPhotosByAlbum: function(id) {
                var link = "{!! url('photos/album') !!}/" + id;
                console.log("firing " + link);
                this.loading = true;
                axios.get(link)
                .then(function (response) {
                    this.photos = response.data;
                    this.loading = false;
                }.bind(this))
                .catch(function (error) {
                    console.log(error);
                });
            },
            handlePhotosPageChange: function(val) {
                var link = "{!! url('photos/album') !!}/" + this.album.id + "?page=" + val;
                axios.get(link)
                .then(function (response) {
                    this.photos = response.data
                }.bind(this))
                .catch(function (error) {
                    this.formerrors = error;
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
            viewPhoto: function(photo) {
                var link = "{!! url('photos/preview') !!}/" + photo.id;
                document.location.href = link;
            },
            editPhoto: function(photo) {
                var link = "{!! url('photos/update') !!}/" + photo.id;
                document.location.href = link;
            },
            trimmedText: function(text, chars) {
                if(text == null) {
                    return;
                }
                return text.length > chars ? text.substring(0, chars) + '...' : text;
            }
        }
    })
@endsection