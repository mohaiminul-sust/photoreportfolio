@extends('layouts.app')

@section('style')
<link href="{{ asset('css/albumcard.css') }}" rel="stylesheet">
@endsection

@section('content')
<el-main class="container">
    @include('flash::message')
    <div id="album">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                <el-header>
                    <div>
                        <a href="{{ route('album.index') }}">
                            <h3 class="box-title center">Albums</h3>
                        </a>
                        <span class="description" style="margin-left: 20px">@{{ albums.meta.from }} - @{{ albums.meta.to }} of @{{ albums.meta.total }} albums</span>        
                    </div>
                    
                    <div class="pull-right">
                        {{--  <a href="{{ route('album.create') }}">
                            <el-button type="primary" icon="el-icon-edit"></el-button>
                        </a>  --}}
                        <span style="margin-left: 10px"></span>
                        <a href="{{ route('album.create') }}" class="pull-right">
                            <el-button type="success" icon="el-icon-plus"></el-button>
                        </a>
                    </div>
                </el-header>
                </div>
                <!-- /.box-header -->
                <el-row class="centered">
                    <el-col class="cardbody" :span="4" v-for="album in albums.data" :key="album">
                        <el-card :body-style="{ padding: '0px' }">
                        <img v-img v-bind:src="album.cover_image" width="200" height="200" v-bind:alt="album.name" class="image">
                        <div style="padding: 14px;">
                            <span>@{{ album.name }}</span>
                            <div class="bottom clearfix">
                            <time class="time">
                                <i class="el-icon-picture"></i>
                                <span style="margin-left: 10px">@{{ album.photos.length }} photos in album</span>
                            </time>
                            <el-button v-on:click="deleteAlbum(album)" class="button" type="danger" icon="el-icon-delete"></el-button>
                            <el-button v-on:click="showEditModal" class="button pull-right" type="primary" icon="el-icon-edit"></el-button>
                            </div>
                        </div>
                        </el-card>
                    </el-col>
                </el-row>
                <el-row class="box">
                    <div class="block text-center">
                        <el-pagination
                        layout="prev, pager, next"
                        :total="albums.meta.total"
                        :per_page="albums.meta.per_page"
                        :current-page="albums.meta.current_page"
                        @current-change="handleCurrentPageChange">
                        </el-pagination>
                    </div>
                </el-row>
                <!-- /.box-body -->
            </div>
        <!-- /.box -->
        </div>

        <modal v-if="presentingEditModal" @close="hideEditModal">
            <h3 slot="header">custom header</h3>
        </modal>
    </div>
</el-main>
@endsection

@section('script')
    var album = new Vue({
        el: '#album',
        data: {
            albums: [],
            formerrors: {},
            presentingEditModal: false
        }, 
        created(){
            this.fetchAlbums();
        },
        methods: {
            fetchAlbums: function() {
                var link = "{!! url('albums/all') !!}";
                axios.get(link)
                .then(function (response) {
                    console.log(response);
                    this.albums = response.data;
                }.bind(this))
                .catch(function (error) {
                    this.formerrors = error;
                });
            },
            deleteAlbum: function (album) {
                var link = "{!! url('albums/delete') !!}/" + album.id;
                console.log("firing " + link);
                axios.get(link)
                .then(function (response) {
                    this.albums = response.data
                }.bind(this))
                .catch(function (error) {
                    this.formerrors = error;
                });
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
            showEditModal: function(album) {
                this.presentingEditModal = true;
            },
            hideEditModal: function() {
                this.presentingEditModal = false;
            }
        }
    })
@endsection