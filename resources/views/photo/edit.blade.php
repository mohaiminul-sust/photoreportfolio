@extends('layouts.app')

@section('style')
    <link href="{{ asset('css/createalbumform.css') }}" rel="stylesheet">
    <link href="{{ asset('css/formtags.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div id="update-photo" class="container" v-cloak>
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
              <h3 class="box-title center">Update Photo</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {!! Form::open(['route' => ['photo.update',  $id]]) !!}
              <div class="box-body">
                    <div class="form-group">
                        <label for="Caption">Caption</label>
                        <input class="form-control" placeholder="Enter photo caption" name="caption" type="text" v-model="photo.caption">
                    </div>
                    <div class="form-group">
                        <label for="Notes">Story</label>
                        <textarea v-model="photo.notes" class="form-control" placeholder="Enter photo story ..." name="notes" cols="15" rows="10"></textarea>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>    
            {!! Form::close() !!}
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title center">Tags</h3>
                </div>
                <div class="box-body">
                    <el-tag
                    :key="tag"
                    v-for="tag in photo.tags"
                    closable
                    :disable-transitions="false"
                    @close="handleClose(tag)">
                    @{{ tag.tag }}
                    </el-tag>
                    <el-input
                    class="input-new-tag"
                    v-if="tagInputVisible"
                    v-model="tagInputValue"
                    ref="saveTagInput"
                    size="mini"
                    @keyup.enter.native="handleInputConfirm"
                    @blur="handleInputConfirm">
                    </el-input>
                    <el-button v-else class="button-new-tag" size="small" @click="showTagInput">+ New Tag</el-button>
                </div>
            </div>

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title center">Update Image</h3>
                </div>
                <div class="box-body">
                    <div class="image-container">
                        <el-upload
                        class="avatar-uploader"
                        action="{!! route('photo.updateimage', $id) !!}"
                        :headers="headerInfo"
                        :show-file-list="false"
                        :on-success="handleAvatarSuccess"
                        :before-upload="beforeAvatarUpload">
                        <img v-if="photo.image" :src="photo.image" class="avatar">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                        </el-upload>
                        <div class="image-centered-text">Click to upload</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    var updatephoto = new Vue({
        el: '#update-photo',
        data: {
            photo: {},
            imageBlob: '',
            headerInfo: {
                'Access-Control-Allow-Origin': '*',
                'Access-Control-Allow-Methods': 'GET, POST, OPTIONS, PUT, DELETE',
                'Access-Control-Allow-Headers': 'Origin, X-Requested-With, Content-Type, Accept, X-File-Name, X-File-Size, X-File-Type',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            tagInputVisible: false,
            tagInputValue: ''
        }, 
        created(){
            this.fetchPhoto({!! $id !!});
        },
        methods: {
            fetchPhoto: function(id) {
                var link = "{!! url('photos') !!}/" + id;
                console.log("firing " + link)
                axios.get(link)
                .then(function (response) {
                    this.photo = response.data.data;
                }.bind(this))
                .catch(function (error) {
                    console.log(error);
                });
            },
            handleAvatarSuccess(res, file) {
                this.coverImageBlob = URL.createObjectURL(file.raw);
                this.photo = res.data;
                this.$message.success('Image updated!');
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
                return isJPG && isLt2M;
            },
            handleClose: function(tag) {
                var link = "{!! url('photos/tags/delete') !!}/" + tag.id;
                axios.get(link)
                .then(function (response) {
                    this.fetchPhoto(this.photo.id);
                }.bind(this))
                .catch(function (error) {
                    console.log(error)
                });
            },
            showTagInput: function() {
                this.tagInputVisible = true;
                this.$nextTick(_ => {
                    this.$refs.saveTagInput.$refs.input.focus();
                });
            },
            handleInputConfirm: function() {
                let inputValue = this.tagInputValue;
                if (inputValue) {
                    var link = "{!! url('photos/tags/create') !!}";
                    var params = {
                        tag: inputValue,
                        photo_id: this.photo.id
                    }
                    axios.post(link, params)
                    .then(function (response) {
                        this.fetchPhoto(this.photo.id);
                    }.bind(this))
                    .catch(function (error) {
                        console.log(error)
                    });
                }
                this.tagInputVisible = false;
                this.tagInputValue = '';
            }
        }
    })
@endsection