@extends('layouts.app')

@section('style')
    <link href="{{ asset('css/createalbumform.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div id="upload-photo" class="container">
        @include('flash::message')
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title center">Upload Photo</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title center">Choose Album</h3>
                    </div>
                    <div class="box-body">
                        <el-select v-model="value" placeholder="Select">
                        <el-option
                            v-for="item in albumlist"
                            :key="item"
                            :label="item.name"
                            :value="item.id">
                        </el-option>
                        </el-select>
                    </div>
                </div>
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title center">Upload Image</h3>
                    </div>
                    <div class="box-body">
                        <div class="image-container">
                            <el-upload
                            class="avatar-uploader"
                            :action="uploadUrl"
                            :headers="headerInfo"
                            :show-file-list="false"
                            :on-success="handleAvatarSuccess"
                            :before-upload="beforeAvatarUpload">
                            <img v-if="imageBlob" :src="imageBlob" class="avatar" width=800 height=600>
                            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                            </el-upload>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    var updatephoto = new Vue({
        el: '#upload-photo',
        data: {
            imageBlob: '',
            headerInfo: {
                'Access-Control-Allow-Origin': '*',
                'Access-Control-Allow-Methods': 'GET, POST, OPTIONS, PUT, DELETE',
                'Access-Control-Allow-Headers': 'Origin, X-Requested-With, Content-Type, Accept, X-File-Name, X-File-Size, X-File-Type',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, 
            albumlist: [],
            value: '',
            loading: false,
            photo: {}
        },
        computed: {
            uploadUrl: function() {
                return "{!! url('photos/create') !!}/" + this.value;
            } 
        },
        created() {
            this.fetchAlbums();
        },
        methods: {
            fetchAlbums: function() {
                var link = "{!! url('albums/list') !!}";
                this.loading = true;
                axios.get(link)
                .then(function (response) {
                    this.albumlist = response.data.data;
                    this.loading = false;
                }.bind(this))
                .catch(function (error) {
                    this.formerrors = error;
                });
            },
            handleAvatarSuccess(res, file) {
                this.coverImageBlob = URL.createObjectURL(file.raw);
                this.$message.success('Image uploaded!');
                this.photo = res.data;
                var link = "{!! url('photos/update') !!}/" + this.photo.id;
                document.location.href = link;
            },
            beforeAvatarUpload(file) {
                const isJPG = file.type === 'image/jpeg';
                const isLt2M = file.size / 1024 / 1024 < 20;
        
                if (!isJPG) {
                    this.$message.error('Photo must be JPG format!');
                }
                if (!isLt2M) {
                    this.$message.error('Photo size can not exceed 20MB!');
                }
                if(this.value.length === 0) {
                    this.$message.warning('Choose album to upload photo first');
                }
                return isJPG && isLt2M;
            }
        }
    })
@endsection