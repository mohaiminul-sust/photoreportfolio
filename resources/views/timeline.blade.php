@extends('layouts.app')

@section('style')
<link href="{{ asset('css/albumcard.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    @include('flash::message')
    <div id="timepage">
        <div class="box">
            <div class="box-header">
                <el-header>
                    <div>
                        <a href="{{ route('timeline') }}">
                            <h3 class="box-title center">Timeline</h3>
                        </a>
                        <span class="description" style="margin-left: 20px">
                                <el-select v-model="value" placeholder="Select">
                                    <el-option
                                        v-for="item in options"
                                        :key="item.value"
                                        :label="item.label"
                                        :value="item.value">
                                    </el-option>
                                </el-select>
                        </span>        
                    </div>
                    <div class="pull-right">
                        <el-button @click="loadData" type="success" icon="el-icon-refresh"></el-button>
                    </div>
                </el-header>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <ul class="timeline" v-for="album in albums">
                    <li class="time-label">
                        <span class="bg-red">
                            @{{ album.time_string_created }}
                        </span>
                    </li>
                    <!-- /.timeline-label -->
                    
                    <!-- timeline item -->
                    <li>
                        <!-- timeline icon -->
                        <i class="fa fa-angle-right bg-blue"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="el-icon-picture-outline"></i> @{{ album.photos.length }} photos</span>
                            <div class="timeline-body">
                                <div class="post">
                                    <div class="user-block">
                                        <img class="img-circle img-bordered-sm" :src="album.cover_image" :alt="album.name">
                                        <span class="username">
                                            <a href="#">@{{ album.name }}</a>
                                        </span>
                                        <span class="description"> created @{{ album.created_ago  }}</span>
                                    </div>
                                    <!-- /.user-block -->
                                    <p>
                                        @{{ album.description }}
                                    </p>
                                </div>
                            </div>
                            <div class="timeline-footer">
                                <el-button @click="showAlbum(album)" type="primary" icon="el-icon-more"></el-button>
                            </div>
                        </div>
                    </li>
                    <!-- END timeline item -->
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    var timepage = new Vue({
        el: '#timepage',
        data: {
            albums: [],
            photos: [],
            formerrors: {},
            options: [{
                value: 'albums',
                label: 'Albums'
            }, {
                value: 'photos',
                label: 'Photos'
            }],
            value: 'albums'
        }, 
        created(){
            this.loadData();
        },
        methods: {
            fetchAlbums: function() {
                var link = "{!! url('timeline/albums') !!}";
                axios.get(link)
                .then(function (response) {
                    this.albums = response.data.data;
                }.bind(this))
                .catch(function (error) {
                    this.formerrors = error;
                }); 
            },
            fetchPhotos: function() {
                var link = "{!! url('timeline/photos') !!}";
                axios.get(link)
                .then(function (response) {
                    this.photos = response.data.data;
                }.bind(this))
                .catch(function (error) {
                    this.formerrors = error;
                });
            },            
            loadData: function() {
                this.fetchAlbums();
                this.fetchPhotos();
            },
            showAlbum: function(album) {
                var link = "{!! url('albums/preview') !!}/" + album.id;
                document.location.href = link;
            }
        }
    })
@endsection