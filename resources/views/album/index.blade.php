@extends('layouts.app')

@section('style')
<link href="{{ asset('css/albumcard.css') }}" rel="stylesheet">
@endsection

@section('content')
<el-main class="container">
    <div id="album">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                <el-header>
                    <h3 class="box-title center">Albums</h3>
                    <el-button class="pull-right" type="success" icon="el-icon-plus"></el-button>
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="height: 300px;">
                            
                        </div>
                    </div>
                </el-header>
                </div>
                <!-- /.box-header -->
                <el-row class="centered">
                    <el-col class="cardbody" :span="4" v-for="album in albums" :key="album">
                        <el-card :body-style="{ padding: '0px' }">
                        <img v-bind:src="album.cover_image" width="200" height="200" class="image">
                        <div style="padding: 14px;">
                            <span>@{{ album.name }}</span>
                            <div class="bottom clearfix">
                            <time class="time">
                                <i class="el-icon-time"></i>
                                <span style="margin-left: 10px">@{{ album.created_at }}</span>
                            </time>
                            <el-button class="button pull-right" type="primary" icon="el-icon-edit"></el-button>
                            </div>
                        </div>
                        </el-card>
                    </el-col>
                </el-row>
                <!-- /.box-body -->
            </div>
        <!-- /.box -->
        </div>
    </div>
</el-main>
@endsection

@section('script')
    var album = new Vue({
        el: '#album',
        data: {
            albums: {!! json_encode($albums->toArray()) !!}
        },
        methods: {
            showAlbumDetails: function (event) {
              // `this` inside methods points to the Vue instance
                
            }
        }
    })
@endsection