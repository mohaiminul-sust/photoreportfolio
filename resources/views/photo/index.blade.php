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
                    <el-row>
                        <el-col :span=18>
                            <div>
                                <a href="{{ route('photo.index') }}">
                                    <h3 class="box-title center">Photos</h3>
                                </a>
                                <span class="description" style="margin-left: 20px">@{{ photos.meta.from }} - @{{ photos.meta.to }} of @{{ photos.meta.total }} photos</span>        
                            </div>
                        </el-col>
                        <el-col :span=4.5>
                            <el-input
                                placeholder="Search Photos"
                                v-model="searchString"
                                v-on:keyup.enter.native="searchPhotos">
                                <i slot="prefix" class="el-input__icon el-icon-search"></i>
                            </el-input>
                        </el-col>  
                    </el-row>
                </el-header>
                </div>
                <!-- /.box-header -->
                <div v-if="this.photos.count > 0" class="box-body">
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
                                <div class="parent-card">
                                    <img v-img:group v-bind:src="photo.image" v-bind:alt="photo.caption" class="image-aspect">
                                </div>
                                <div style="padding: 14px;">
                                    <span>@{{ trimmedText(photo.caption, 17) }}</span>
                                    <div class="bottom clearfix">
                                    <time class="time">
                                        <i class="el-icon-time"></i>
                                        <span style="margin-left: 10px">@{{ photo.created_date }}</span>
                                    </time>
                                    <time class="time">
                                        <i class="el-icon-menu"></i>
                                        <span style="margin-left: 10px">@{{ trimmedText(photo.album.name, 13) }}</span>
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
                </div>
                <div v-else class="text-center box-body">
                    <span class="description"> No Photos Yet. <a href="{{ route('photo.uploadimage') }}">Upload</a> one!</span>
                </div>
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
            searchString: '',
            photos: [],
            formerrors: {},
            loading: false,
            hasSearched: false
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
            searchPhotos: function() {
                let query = this.searchString;
                if (query.length <= 0) {
                    this.$message.warning('Enter something in search box to search');  
                    return;
                }
                var link = "{!! url('photos/search') !!}/?query=" + query;
                console.log("Firing : " + link);
                this.loading = true;
                axios.get(link)
                .then(function (response) {
                    this.photos = response.data;
                    this.loading = false;
                    this.hasSearched = true;
                }.bind(this))
                .catch(function (error) {
                    this.formerrors = error;
                    this.loading = false;
                }); 
            },
            editPhoto: function (photo) {
                var link = "{!! url('photos/update') !!}/" + photo.id;
                document.location.href = link;
            },
            handleCurrentPageChange: function(val) {
                var link = "{!! url('photos/all') !!}?page=" + val;
                let query = this.searchString;
                if (this.hasSearched == true) {
                    if (query.length > 0) {
                        link = "{!! url('photos/search') !!}?query=" + query + "&page=" + val;
                    }
                }
                console.log("Firing pager : " + link);
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
                    return "";
                }
                return text.length > chars ? text.substring(0, chars) + '...' : text;
            }
        }
    })
@endsection