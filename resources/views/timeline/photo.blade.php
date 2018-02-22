@extends('layouts.app')

@section('content')
<div class="container">
    @include('flash::message')
    <div id="photo-time" v-cloak>
        <div v-loading="loading" class="box">
            <div class="box-header">
                <el-header>
                    <div>
                        <a href="{{ route('timeline.photo') }}">
                            <h3 class="box-title center">Photo Timeline</h3>
                        </a>        
                    </div>
                </el-header>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <ul class="timeline" v-for="(value, key) in photosGrouped">
                    <li class="time-label">
                        <span class="bg-red">
                            @{{ key }}
                        </span>
                    </li>
                    <!-- /.timeline-label -->
                    
                    <!-- timeline item -->
                    <li v-for="photo in value">
                        <!-- timeline icon -->
                        <i class="fa fa-angle-right bg-blue"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="el-icon-time"></i> Created @{{ photo.created_at }}</span>
                            <div class="timeline-body">
                                <div class="post">
                                    <div class="user-block">
                                        <img class="img-circle img-bordered-sm" :src="photo.image" :alt="photo.name">
                                        <span class="username">
                                            <a @click="showPhoto(photo)" href="#">@{{ trimmedText(photo.caption, 40) }}</a>
                                        </span>
                                        <span class="description"> Updated @{{ photo.updated_at  }}</span>
                                    </div>
                                    <!-- /.user-block -->
                                    <p>
                                        @{{ trimmedText(photo.notes, 350) }}
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
        el: '#photo-time',
        data: {
            photosGrouped: {},
            formerrors: {},
            loading: true
        },
        created(){
            this.fetchPhotos();
        },
        methods: {
            fetchPhotos: function() {
                var link = "{!! url('timeline/photos') !!}";
                this.loading = true;
                axios.get(link)
                .then(function (response) {
                    this.photosGrouped = response.data;
                    this.loading = false;
                }.bind(this))
                .catch(function (error) {
                    this.formerrors = error;
                });
            },
            showPhoto: function(photo) {
                var link = "{!! url('photos/preview') !!}/" + photo.id;
                document.location.href = link;
            },
            trimmedText: function(text, chars) {
                return text.length > chars ? text.substring(0, chars) + '...' : text;
            }
        }
    })
@endsection