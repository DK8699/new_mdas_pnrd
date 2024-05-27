@extends('layouts.app_user_osr')
@php
    $page_title="change_password";
@endphp
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="card mt40">
                    <div class="card-header">Change Password</div>
                    <div class="card-body">
                        <form method="POST" id="changePasswordForm" action="">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-6">
                                    <input id="email" type="password" class="form-control" name="existing_password" value="" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">New Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="new_password" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="conform_password" required>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4 text-right">
                                    <button type="submit" class="btn btn-primary">Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_js')
    <script>
//----------------------------------------------# changePasswordForm #---------------------------------------------------
                $("#changePasswordForm").validate({
                rules: {
                existing_password:{
                required: true
                },
                new_password:{
                required: true
                },
                conform_password:{
                required: true
                }
                }
                });

                $('#changePasswordForm').on('submit', function(e){
                e.preventDefault();
                if($('#changePasswordForm').valid()){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{route('update_password')}}',
                dataType: "json",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                if (data.msgType == true) {
                swal("Success", data.msg, "success")
                .then((value) => {
                location.reload();
                });
                }else{
                if(data.msg == "VE"){
                swal("Error", "Validation error.Please check the form correctly!", 'error');
                $.each(data.errors, function( index, value ) {
                $('#'+index).after('<p class="text-danger form_errors">'+value+'</p>');
                });
                }else{
                swal("Error", data.msg, 'error');
                }
                }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                },
                complete: function (data) {
                $('.page-loader-wrapper').fadeOut();
                }
                });
                }

                });
    </script>
@endsection