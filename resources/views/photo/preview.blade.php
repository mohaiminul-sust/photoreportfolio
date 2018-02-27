@extends('layouts.app')

@section('style')
    <link href="{{ asset('css/formtags.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div id="preview-photo" class="container" v-cloak>
        <div class="box box-primary">
            <div class="box-header with-border user-block">
                <img v-img class="img-circle img-bordered-sm" :src="photo.album.cover_image" :alt="photo.album.name">
                <span class="username">Photo : from album <button @click="showAlbum" class="btn-sm btn-primary">@{{ photo.album.name }}</button></span>
                <span class="description">Created @{{ photo.created_ago }} at @{{ photo.created_date }}</span>
                <span class="description">Updated at @{{ photo.updated_date }}</span>
                <div class="pull-right">
                    <el-button @click="deletePhoto" type="danger" icon="el-icon-delete"></el-button>
                    <el-button @click="editPhoto" class="pull-right" type="success" icon="el-icon-edit"></el-button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="box">
                    <div class="box-header">
                        <strong><i class="fa fa-book margin-r-5"></i> Photo</strong>
                        <button @click="openImageInfo" type="button" class="btn-sm btn-primary pull-right"><i class="fa fa-align-left"></i> Info</button>
                    </div>
                    <div class="box-body">
                        <img v-img class="image-aspect" :src="photo.image" :alt="photo.caption" id="photoimg" ref="photoimg">
                    </div>
                </div>
                <div class="box">
                    <div class="box-header">
                        <strong><i class="fa fa-book margin-r-5"></i> Caption</strong>
                    </div>
                    <div class="box-body">
                        <span class="description">@{{ photo.caption }}</span>
                    </div>
                </div>
                <div class="box">
                    <div class="box-header">
                        <strong><i class="fa fa-book margin-r-5"></i> Tags</strong>
                    </div>
                    <div class="box-body">
                        <el-tag v-for="tag in photo.tags" :key="tag.id">@{{ tag.tag }}</el-tag>
                    </div>
                </div>
                <div class="box">
                    <div class="box-header">
                        <strong><i class="fa fa-book margin-r-5"></i> Story</strong>
                    </div>
                    <div class="box-body">
                        <span class="description">@{{ photo.notes }}</span>
                    </div>
                </div>
            </div>
        </div>
        <sweet-modal ref="imageinfo">
            <sweet-modal-tab title="Camera" id="cameraTab">
                <div class="box">
                    <div class="box-body">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Parameters</th>
                                    <th>Value</th>
                                </tr>
                                <tr v-for="(value, key) in cameraData">
                                    <td>@{{ toCapitalizedWords(key) }}</td>
                                    <td>@{{ value }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </sweet-modal-tab>
            <sweet-modal-tab title="Image" id="imageTab">
                <div class="box">
                    <div class="box-body">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Parameters</th>
                                    <th>Value</th>
                                </tr>
                                <tr v-for="(value, key) in imageData">
                                    <td>@{{ toCapitalizedWords(key) }}</td>
                                    <td>@{{ value }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </sweet-modal-tab>
        </sweet-modal>
    </div>
@endsection

@section('script')
    var previewphoto = new Vue({
        el: '#preview-photo',
        data: {
            photo: {}
        },
        computed: {
            cameraData : function() {
                let exif = this.photo.exif;
                return {
                    brand: exif.Make,
                    model: exif.Model,
                    meteringMode: exif.MeteringMode,
                    aperture: exif.COMPUTED.ApertureFNumber,
                    exposureProgram: exif.ExposureProgram,
                    exposureTime: exif.ExposureTime,
                    exposureBiasValue: exif.ExposureBiasValue,
                    focalLength: exif.FocalLength,
                    iso: exif.ISOSpeedRatings,
                    flash: exif.Flash,
                    orientation: exif.Orientation
                };
            },
            imageData : function() {
                let exif = this.photo.exif;
                return {
                    fileName: exif.FileName,
                    editedBy: exif.Software,
                    height: exif.COMPUTED.Height,
                    width: exif.COMPUTED.Width,
                    color: exif.COMPUTED.IsColor,
                    originalDate: exif.DateTimeOriginal
                };
            }
        },
        created() {
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
            deletePhoto: function () {
                var link = "{!! url('photos/delete') !!}/" + this.photo.id;
                console.log("firing " + link);
                axios.get(link)
                .then(function (response) {
                    var redirect = "{!! url('photos') !!}";
                    document.location.href = redirect;
                }.bind(this))
                .catch(function (error) {
                    this.formerrors = error;
                });
            },
            editPhoto: function() {
                var link = "{!! url('photos/update') !!}/" + this.photo.id;
                document.location.href = link;
            },
            showAlbum: function() {
                var link = "{!! url('albums/preview') !!}/" + this.photo.album.id;
                document.location.href = link;
            },
            openImageInfo: function() {
                this.$refs.imageinfo.open();
            },
            toCapitalizedWords: function(name) {
                var words = name.match(/[A-Za-z][a-z]*/g);
                return words.map(this.capitalize).join(" ");
            },
            capitalize: function(word) {
                return word.charAt(0).toUpperCase() + word.substring(1);
            }
        }
    })
@endsection