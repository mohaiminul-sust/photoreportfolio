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
                <div class="box-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th class="centered-table-header">Parameter</th>
                                <th class="centered-table-header">Value</th> 
                            </tr>
                            <tr v-for="(value, key) in cameraData">
                                <td>@{{ toCapitalizedWords(key) }}</td>
                                <td>@{{ value }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </sweet-modal-tab>
            <sweet-modal-tab title="Image" id="imageTab">
                <div class="box-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th class="centered-table-header">Parameter</th>
                                <th class="centered-table-header">Value</th> 
                            </tr>
                            <tr v-for="(value, key) in imageData">
                                <td>@{{ toCapitalizedWords(key) }}</td>
                                <td>@{{ value }}</td>
                            </tr>
                        </tbody>
                    </table>
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
                    brand: exif.Make ? exif.Make : 'Not Found',
                    model: exif.Model ? exif.Model : 'Not Found',
                    aperture: exif.COMPUTED.ApertureFNumber ? exif.COMPUTED.ApertureFNumber : 'Not Found',
                    exposure: this.humanizeExposureProgram(exif.ExposureProgram),
                    exposureTime: exif.ExposureTime ? exif.ExposureTime : 'Not Found',
                    exposureBias: exif.ExposureBiasValue ? exif.ExposureBiasValue : 'Not Found',
                    focalLength: exif.FocalLength ? exif.FocalLength : 'Not Found',
                    iso: exif.ISOSpeedRatings ? exif.ISOSpeedRatings : 'Not Found',
                    meteringMode: this.humanizeMeteringMode(exif.MeteringMode),
                    flash: this.humanizeFlashMode(exif.Flash)
                };
            },
            imageData : function() {
                let exif = this.photo.exif;
                return {
                    fileName: exif.FileName ? exif.FileName : 'Not Found',
                    editedBy: exif.Software ? exif.Software : 'Not Found',
                    height: exif.COMPUTED.Height ?  exif.COMPUTED.Height + ' px' : 'Not Found',
                    width: exif.COMPUTED.Width ? exif.COMPUTED.Width + ' px' : 'Not Found',
                    orientation: this.humanizeOrientation(exif.Orientation),
                    color: this.humanizeImageColorMode(exif.COMPUTED.IsColor),
                    originalDate: exif.DateTimeOriginal ? exif.DateTimeOriginal : 'Not Found'
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
            },
            humanizeImageColorMode: function(color) {
                switch(color) {
                    case 0:
                        return 'No'
                    case 1:
                        return 'Yes'
                    default:
                        return 'Not Found'        
                }
            },
            humanizeMeteringMode: function(mode) {
                switch(mode) {
                    case 0:
                        return 'Unknown'
                    case 1:
                        return 'Average'
                    case 2:
                        return 'Center-weighted Average'
                    case 3:
                        return 'Spot'
                    case 4:
                        return 'Multi-Spot'
                    case 5:
                        return 'Multi-Segment'
                    case 6:
                        return 'Partial'
                    case 255:
                        return 'Other'
                    default:
                        return 'Not Found'                            
                }
            },
            humanizeOrientation: function(orientation) {
                switch(orientation) {
                    case 1:
                        return 'Horizontal'
                    case 2:
                        return 'Mirrored Horizontal'
                    case 3:
                        return 'Rotated 180'
                    case 4:
                        return 'Mirrored Vertical'
                    case 5:
                        return 'Mirrored Horizontal & Rotated 270 CW'
                    case 7:
                        return 'Rotated 90 CW'
                    case 7:
                        return 'Mirrored Horizontal & Rotated 90 CW'
                    case 8:
                        return 'Rotated 270 CW'
                    default:
                        return 'Not Found'                            
                }
            },
            humanizeExposureProgram: function(program) {
                switch(program) {
                    case 0:
                        return 'Not Defined'
                    case 1:
                        return 'Manual'
                    case 2:
                        return 'Program AE'
                    case 3:
                        return 'Aperture-priority AE'
                    case 4:
                        return 'Shutter speed priority AE'
                    case 5:
                        return 'Creative (Slow speed)'
                    case 6:
                        return 'Action (High speed)'
                    case 7:
                        return 'Portrait'
                    case 8:
                        return 'Landscape'
                    case 9:
                        return 'Bulb'    
                    default:
                        return 'Not Found'                            
                }
            },
            humanizeFlashMode: function(mode) {
                switch(mode) {
                    case 0:
                        return 'No Flash'
                    case 1:
                        return 'Fired'
                    case 5:
                        return 'Fired, Return not detected'
                    case 7:
                        return 'Fired, Return detected'
                    case 8:
                        return 'On, Did not fire'
                    case 9:
                        return 'On, Fired'
                    case 13:
                        return 'On, Return not detected'
                    case 15:
                        return 'On, Return detected'
                    case 16:
                        return 'Off, Did not fire'
                    case 20:
                        return 'Off, Did not fire, Return not detected'
                    case 24:
                        return 'Auto, Did not fire'
                    case 25:
                        return 'Auto, Fired'
                    case 29:
                        return 'Auto, Fired, Return not detected'
                    case 31:
                        return 'Auto, Fired, Return detected'
                    case 32:
                        return 'No flash function'
                    case 48:
                        return 'Off, No flash function'
                    case 65:
                        return 'Fired, Red-eye reduction'
                    case 69:
                        return 'Fired, Red-eye reduction, Return not detected'
                    case 71:
                        return 'Fired, Red-eye reduction, Return detected'
                    case 73:
                        return 'On, Red-eye reduction'   
                    case 77:
                        return 'On, Red-eye reduction, Return not detected'
                    case 79:
                        return 'On, Red-eye reduction, Return detected'
                    case 80:
                        return 'Off, Red-eye reduction'
                    case 88:
                        return 'Auto, Did not fire, Red-eye reduction'
                    case 89:
                        return 'Auto, Fired, Red-eye reduction'
                    case 93:
                        return 'Auto, Fired, Red-eye reduction, Return not detected'
                    case 95:
                        return 'Auto, Fired, Red-eye reduction, Return detected'        
                    default:
                        return 'Not Found'                            
                }
            }
        }
    })
@endsection