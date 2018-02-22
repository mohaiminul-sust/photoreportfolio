@extends('layouts.app')

@section('content')
<div class="container">
    @include('flash::message')
    <div id="album-time" v-cloak>
        <div v-loading="loading" class="box">
            <div class="box-header">
                <el-header>
                    <div>
                        <a href="{{ route('timeline.album') }}">
                            <h3 class="box-title center">Album Timeline</h3>
                        </a>        
                    </div>
                </el-header>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <ul class="timeline" v-for="(value, key) in albumsGrouped">
                    <li class="time-label">
                        <span class="bg-red">
                            @{{ key }}
                        </span>
                    </li>
                    <!-- /.timeline-label -->
                    
                    <!-- timeline item -->
                    <li v-for="album in value">
                        <!-- timeline icon -->
                        <i class="fa fa-angle-right bg-blue"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="el-icon-time"></i> Created @{{ album.created_at }}</span>
                            <div class="timeline-body">
                                <div class="post">
                                    <div class="user-block">
                                        <img class="img-circle img-bordered-sm" :src="album.cover_image" :alt="album.name">
                                        <span class="username">
                                            <a @click="showAlbum(album)" href="#">@{{ album.name }}</a>
                                        </span>
                                        <span class="description"> Updated @{{ album.updated_at  }}</span>
                                    </div>
                                    <!-- /.user-block -->
                                    <p>
                                        @{{ album.description }}
                                    </p>
                                </div>
                            </div>
                            <div class="timeline-footer">
                                
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
        el: '#album-time',
        data: {
            albumsGrouped: {},
            formerrors: {},
            loading: true
        }, 
        created(){
            this.fetchAlbums();
        },
        methods: {
            fetchAlbums: function() {
                var link = "{!! url('timeline/albums') !!}";
                this.loading = true;
                axios.get(link)
                .then(function (response) {
                    this.albumsGrouped = response.data;
                    this.loading = false;
                }.bind(this))
                .catch(function (error) {
                    this.formerrors = error;
                }); 
            },
            showAlbum: function(album) {
                var link = "{!! url('albums/preview') !!}/" + album.id;
                document.location.href = link;
            }
        }
    })
@endsection