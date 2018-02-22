@extends('layouts.app')

@section('style')
<link href="{{ asset('css/customcard.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    @include('flash::message')
    <div id="photos" v-cloak>
        <div v-loading="loading" class="col-xs-12">
            <div class="box">
                <div class="box-header">
                <el-header>
                    <div>
                        <a href="{{ route('photo.index') }}">
                            <h3 class="box-title center">Photos</h3>
                        </a>
                        <span class="description" style="margin-left: 20px">@{{ photos.meta.from }} - @{{ photos.meta.to }} of @{{ photos.meta.total }} photos</span>        
                    </div>
                </el-header>
                </div>
                <!-- /.box-header -->
                <el-row>
                    <div class="block text-center">
                        <el-pagination
                        layout="prev, pager, next"
                        :total="photos.meta.total"
                        :page-size="photos.meta.per_page"
                        :current-page.sync="photos.meta.current_page"
                        @current-change="handleCurrentPageChange">
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
                                <time class="time">
                                    <i class="el-icon-menu"></i>
                                    <span style="margin-left: 10px">@{{ trimmedText(photo.album.name, 17) }}</span>
                                </time>
                                <hr>
                                <el-button v-on:click="editPhoto(photo)" class="button pull-right" type="danger" icon="el-icon-edit"></el-button>
                                <el-button v-on:click="showPhoto(photo)" class="button" type="primary" icon="el-icon-view"></el-button>
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
    var photos = new Vue({
        el: '#photos',
        data: {
            photos: [],
            formerrors: {},
            loading: true
        }, 
        created(){
            this.fetchPhotos();
        },
        methods: {
            fetchPhotos: function() {
                var link = "{!! url('photos/all') !!}";
                this.loading = true;
                axios.get(link)
                .then(function (response) {
                    this.photos = response.data;
                    this.loading = false;
                }.bind(this))
                .catch(function (error) {
                    this.formerrors = error;
                });
            },
            editPhoto: function (photo) {
                var link = "{!! url('photos/update') !!}/" + photo.id;
                document.location.href = link;
            },
            handleCurrentPageChange: function(val) {
                var link = "{!! url('photos/all') !!}?page=" + val;
                axios.get(link)
                .then(function (response) {
                    this.photos = response.data
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
                if(text == null) {
                    return;
                }
                return text.length > chars ? text.substring(0, chars) + '...' : text;
            }
        }
    })
@endsection