@extends('layouts.app')

@section('style')
<link href="{{ asset('css/customcard.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    @include('flash::message')
    <div id="album" v-cloak>
        <div class="col-xs-12">
            <div v-loading="loading" class="box">
                <div class="box-header">
                <el-header>
                    <div>
                        <a href="{{ route('album.index') }}">
                            <h3 class="box-title center">Albums</h3>
                        </a>
                        <span class="description" style="margin-left: 20px">@{{ albums.meta.from }} - @{{ albums.meta.to }} of @{{ albums.meta.total }} albums</span>        
                    </div>
                    
                    <div class="pull-right">
                        <a href="{{ route('album.create') }}" class="pull-right">
                            <el-button type="success" icon="el-icon-plus"></el-button>
                        </a>
                    </div>
                </el-header>
                </div>
                <!-- /.box-header -->
                <el-row>
                    <div class="block text-center">
                        <el-pagination
                        layout="prev, pager, next"
                        :total="albums.meta.total"
                        :page-size="albums.meta.per_page"
                        :current-page.sync="albums.meta.current_page"
                        @current-change="handleCurrentPageChange">
                        </el-pagination>
                    </div>
                </el-row>
                <el-row>
                    <div class="center">
                        <el-col class="cardbody" :span="4" v-for="album in albums.data" :key="album">
                            <el-card :body-style="{ padding: '0px' }">
                            <img v-bind:src="album.cover_image" width="200" height="200" v-bind:alt="album.name" class="image">
                            <div style="padding: 14px;">
                                <span>@{{ trimmedText(album.name, 17) }}</span>
                                <div class="bottom clearfix">
                                <time class="time">
                                    <i class="el-icon-picture"></i>
                                    <span style="margin-left: 10px">@{{ album.photos.length }} photos in album</span>
                                </time>
                                <el-button v-on:click="editAlbum(album)" class="button pull-right" type="danger" icon="el-icon-edit"></el-button>
                                <el-button v-on:click="showAlbum(album)" class="button" type="primary" icon="el-icon-view"></el-button>
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
                        :total="albums.meta.total"
                        :page-size="albums.meta.per_page"
                        :current-page="albums.meta.current_page"
                        @current-change="handleCurrentPageChange">
                        </el-pagination>
                    </div>
                </el-row>
                <!-- /.box-body -->
            </div>
        <!-- /.box -->
        </div>
    </div>
</div>
@endsection

@section('script')
    var album = new Vue({
        el: '#album',
        data: {
            albums: [],
            formerrors: {},
            presentingEditModal: false,
            loading: true
        }, 
        created(){
            this.fetchAlbums();
        },
        methods: {
            fetchAlbums: function() {
                var link = "{!! url('albums/all') !!}";
                this.loading = true;
                axios.get(link)
                .then(function (response) {
                    this.albums = response.data;
                    this.loading = false;
                }.bind(this))
                .catch(function (error) {
                    this.formerrors = error;
                });
            },
            editAlbum: function (album) {
                var link = "{!! url('albums/update') !!}/" + album.id;
                document.location.href = link;
            },
            handleCurrentPageChange: function(val) {
                var link = "{!! url('albums/all') !!}?page=" + val;
                axios.get(link)
                .then(function (response) {
                    this.albums = response.data
                }.bind(this))
                .catch(function (error) {
                    this.formerrors = error;
                });
            },
            showAlbum: function(album) {
                var link = "{!! url('albums/preview') !!}/" + album.id;
                document.location.href = link;
            },
            trimmedText: function(text, chars) {
                return text.length > chars ? text.substring(0, chars) + '...' : text;
            }
        }
    })
@endsection