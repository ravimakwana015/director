@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                User
            </h1>
        </section>
        <section class="content">
            {{-- @include("admin.include.message") --}}
            <div class="box box-success">
                <div class="box-header with-border ">
                    <h3 class="box-title">User Update</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                title="Collapse">
                            <i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip"
                                title="Remove">
                            <i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    {!! Form::model($users, ['route' => ['users.update', $users->id], 'role' => 'form', 'method' => 'PATCH', 'files' => true]) !!}
                    @include("admin.users.form")
                    <div class="box-body">
                        <div class="form-group">
                            <button class="btn btn-success">Update</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="box-footer"></div>
            </div>
        </section>
    </div>
@endsection
@section('after-scripts')
    <script>
        var resize = $('#upload-demo').croppie({
            enableExif: true,
            // enableOrientation: false,
            viewport: {
                width: 500,
                height: 500,
                type: 'square'
            },
            boundary: {
                width: 600,
                height: 600
            }
        });
        var _URL = window.URL || window.webkitURL;
        $('#profile_picture-1').on('change', function () {
            var file, img

           if ((file = this.files[0])) {
               img = new Image();
               img.onload = function () {
//
                   if (this.width >= 400 && this.height>= 400) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            resize.croppie('bind', {
                                url: e.target.result
                            }).then(function () {
                                $('.profile-image-preview').addClass('active');
                                $('body').addClass('profile-popup');
                            });
                        }
                        reader.readAsDataURL(file);
                  }
                    else
                    {
                   // alert(this.width + " " + this.height);
                      $('#image-dimension').html('Image Dimension must be 400 X 400');
                    }
                };
                 img.onerror = function () {
                     alert("not a valid file: " + file.type);
                 };
                 img.src = _URL.createObjectURL(file);
            }

        });

        $('#close_image_crop').click(function (event) {
            $('.profile-image-preview').removeClass('active');
            $('body').removeClass('profile-popup');
            $('#profile_picture-1').val('');
        });

        $('.upload-image').on('click', function (ev) {
            resize.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (img) {
                $('#loading').show();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('upload-admin-icon') }}",
                    type: "POST",
                    data: {"image": img,
                        id: '{{ $users->id }}'
                    },
                    success: function (data) {
                        $('#loading').hide();
                        var path = '{{ asset('public/img/profile_picture/') }}';
                        $('#career_img').attr('src', path + '/' + data);
                        $('#icon_img').val(data);
                        $('.profile-image-preview').removeClass('active');
                        $('body').removeClass('profile-popup');
                    }
                });
            });
        });
    </script>
@endsection
