@extends('layouts.app')

@section('style')
    <link href="{{ asset('css/albumcard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/createalbumform.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div id="update-album" class="container">
        @include('flash::message')
        @if(count($errors) > 0)
        <div class="alert alert-block alert-error fade in" id="error-block">
            <button type="button" class="close"data-dismiss="alert">×</button>
            <h4>Errors Found!</h4>
            <strong>Warning!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title center">Update Album</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {!! Form::open(['route' => ['album.update',  $id]]) !!}
              <div class="box-body">
                    <div class="form-group">
                        <label for="Name">Name</label>
                        <input class="form-control" placeholder="Enter album name" name="name" type="text" v-bind:value="album.name">
                    </div>
                    <div class="form-group">
                        <label for="Description">Description</label>
                        <textarea v-model="album.description" class="form-control" placeholder="Enter album description ..." name="description" cols="15" rows="10"></textarea>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>    
            {!! Form::close() !!}

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title center">Upload Cover Image</h3>
                </div>
                <div class="box-body">
                    <el-upload
                    class="avatar-uploader"
                    action="{!! route('album.uploadcover', $id) !!}"
                    :headers="headerInfo"
                    :show-file-list="false"
                    :on-success="handleAvatarSuccess"
                    :before-upload="beforeAvatarUpload">
                    <img v-if="album.cover_image" :src="album.cover_image" class="avatar">
                    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                </div>
            </div>

            <div v-if="album.photos.length > 0">
                    <div class="box">
                        <div class="box-header with-border">
                            <div>
                                <h3 class="box-title center">Album Photo(s)</h3>
                                <span class="description" style="margin-left: 20px">@{{ album.photos.length }} photos in album</span>
                            </div>
                            <div class="pull-right">
                                <a href="#" class="pull-right">
                                    <el-button type="success" icon="el-icon-plus"></el-button>
                                </a>
                            </div>
                        </div>
                        <el-row>
                            <div class="center">
                                <el-col class="cardbody" :span="4" v-for="photo in album.photos" :key="photo">
                                    <el-card :body-style="{ padding: '0px' }">
                                    <img v-img:group v-bind:src="photo.image" width="200" height="200" v-bind:alt="photo.caption" class="image">
                                    <div style="padding: 14px;">
                                        <span>@{{ photo.caption.length > 17 ? photo.caption.substring(0,17) + '...' : photo.caption }}</span>
                                        <div class="bottom clearfix">
                                        <time class="time">
                                            <i class="el-icon-time"></i>
                                            <span style="margin-left: 10px">@{{ photo.created_at }}</span>
                                        </time>
                                        <el-button class="button" type="danger" icon="el-icon-delete"></el-button>
                                        <el-button class="button pull-right" type="primary" icon="el-icon-edit"></el-button>
                                        </div>
                                    </div>
                                    </el-card>
                                </el-col>
                            </div>
                        </el-row>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    var updatealbum = new Vue({
        el: '#update-album',
        data: {
            album: {},
            coverImageUrl: '',
            headerInfo: {
                'Access-Control-Allow-Origin': '*',
                'Access-Control-Allow-Methods': 'GET, POST, OPTIONS, PUT, DELETE',
                'Access-Control-Allow-Headers': 'Origin, X-Requested-With, Content-Type, Accept, X-File-Name, X-File-Size, X-File-Type',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }, 
        created(){
            this.fetchAlbum({!! $id !!});
        },
        methods: {
            fetchAlbum: function(id) {
                var link = "{!! url('albums') !!}/" + id;
                console.log("firing " + link)
                axios.get(link)
                .then(function (response) {
                    this.album = response.data.data;
                }.bind(this))
                .catch(function (error) {
                    console.log(error);
                });
            },
            handleAvatarSuccess(res, file) {
                this.coverImageUrl = URL.createObjectURL(file.raw);
                this.album = res.data;
                this.$message.success('Cover image updated!');
            },
            beforeAvatarUpload(file) {
                const isJPG = file.type === 'image/jpeg';
                const isLt2M = file.size / 1024 / 1024 < 2;
        
                if (!isJPG) {
                    this.$message.error('Avatar picture must be JPG format!');
                }
                if (!isLt2M) {
                    this.$message.error('Avatar picture size can not exceed 2MB!');
                }
                return isJPG && isLt2M;
            }
        }
    })
@endsection