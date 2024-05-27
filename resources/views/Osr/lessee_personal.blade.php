@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_user_osr')

@section('custom_css')
    <style>
    .card{

        border: solid 2px;
        padding:10px;
    }
    h5{
      background:#2dc2e3;
      padding:15px;
    }
    .well{
            margin:0px;
        }
    .modal-body
    {
       background:#f5f5f5;
       padding:auto;
    }
    strong
    {
            color:red;
        }
    .Zebra_DatePicker_Icon_Wrapper{
            width:100% !important;
        }
    .form-control
    {
      height:28px;
      padding:2px 5px;
      font-size: 12px;
    }
        label
        {
            font-size: 11px;
        }
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button
         {
                -webkit-appearance: none;
                 margin: 0;
          }
    </style>
@endsection






@section('content')
<div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="active">Sixth Assam State Finance</li>
        </ol>
</div>
<div class="container mb-50"><!------- start Container------------>
    <div class="container-fluid"><!----------start container-fluid---------->
        <div class="card">
           <div class="card-header">
             <h5 style="margin-top: -5px" >A. Personal Details</h5>
            </div>

           <div class="card-body">

                    <form action="#" method="POST">

                        <!------------------Top Band-------------------->

                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group text-center">
                                    <img id="pri_image" src="{{asset('mdas_assets/images/user_add.png')}}" style="border:1px solid #ddd;width:120px;max-height:130px;cursor:pointer" />
                                    <input type="file" name="pic" id="pic" style="display: none"/><br>
                                    <label>Click the above image to upload passport photo</label>
                                    <a href="#" data-toggle="tooltip" title="Note: Click on the photo to upload passport photo. Upload jpg, jpeg and png file only. Max image size should not exceed 100KB and not less than 10KB">
                                        <i class="fa fa-question-circle"></i>
                                    </a>
                                </div>

                                <div class="form-group">
                                    <label>Photo Identity Proof of Member (PAN, Voter ID etc.)</label>
                                    <a href="#" data-toggle="tooltip" title="Note: Upload jpg, jpeg and png file only. Max image size should not exceed 200KB and not less than 10KB">
                                        <i class="fa fa-question-circle"></i>
                                    </a>
                                    <input type="file" name="photo_i_proof" id="photo_i_proof" />
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <div class="row">
                                    <div class="col-md-4 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label>First Name <strong>*</strong></label>
                                            <input type="text" class="form-control" name="osr_f_name" id="osr_f_name"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Middle Name</label>
                                            <input type="text" class="form-control" name="osr_m_name" id="osr_m_name"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Last Name <strong>*</strong></label>
                                            <input type="text" class="form-control" name="osr_l_name" id="osr_l_name"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Mobile Number <strong>*</strong></label>
                                            <input type="number" class="form-control" name="osr_phone" id="osr_phone"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Email Id<strong>*</strong></label>
                                            <input type="email" class="form-control" name="osr_email" id="osr_email"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Alt Mobile Number</label>
                                            <input type="number" class="form-control" name="osr_alt_number" id="osr_alt_number"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Gender<strong>*</strong></label>
                                            <select class="form-control" name="osr_gender" id="osr_gender">
                                                <option value="">---Select---</option>
                                                <option value="">Male</option>
                                                <option value="">Female</option>
                                                <option value="">Transgender</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label>PAN Number<strong>*</strong></label>
                                            <input type="text" class="form-control" name="osr_pan" id="osr_pan"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label>GST Number<strong>*</strong></label>
                                            <input type="text" class="form-control" name="osr_gst" id="osr_gst"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Caste<strong>*</strong></label>
                                            <select class="form-control" name="osr_caste" id="osr_caste">
                                                <option value="">---Select---</option>
                                                <option value="">General</option>
                                                <option value="">SC</option>
                                                <option value="">ST</option>
                                                <option value="">OBC</option>
                                                <option value="">MOBC</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Nationality<strong>*</strong></label>
                                            <select class="form-control" name="osr_nationality" id="osr_nationality">
                                                <option value="">---Select---</option>
                                                <option value="">Indian</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Religion<strong>*</strong></label>
                                            <select class="form-control" name="osr_religion" id="osr_religion">
                                                <option value="">---Select---</option>
                                                <option value="">Hunduism</option>
                                                <option value="">Islam</option>
                                                <option value="">Christianity</option>
                                                <option value="">Sikh</option>
                                                <option value="">Buddhist</option>
                                                <option value="">Jain</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>Address<strong>*</strong></label>
                                            <textarea class="form-control" rows="2" id="osr_address"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>Pin<strong>*</strong></label>
                                            <input type="text" class="form-control" name="osr_pin" id="osr_pin"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>District<strong>*</strong></label>
                                            <input type="text" class="form-control" name="osr_dist" id="osr_dist"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>State<strong>*</strong></label>
                                            <input type="text" class="form-control" name="osr_state" id="osr_state"/>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                       <button type="submit" class="btn btn-primary pull-right" style="margin-left:5px; margin-top:8px;">
                                          <i class="fa fa-send"></i>
                                           Submit
                                       </button>
                                    </div>
                            </div>
                        </div>
                    </div> <!-- End of main row!-->



                    <!-------------------------Middle Band---------------->

                   </form>
             </div> <!----End of main row---->




            </div>
          </div> <!----end container-fluid------->
        </div><!----end container------------->
@endsection




@section('custom_js')
<script>
 $('#osr_dot').Zebra_DatePicker({
            direction: ['{{\Carbon\Carbon::parse('2018-01-01')->subYears(100)->format('Y-m-d')}}', '{{\Carbon\Carbon::parse('2018-01-01')->subYears(18)->format('Y-m-d')}}']
        });


$('#pic').change(function () {
      if (this.files && this.files[0]) {
          checkImage(this.files[0]);
      }
});


function checkImage(file){
            var extension = file.name.substr((file.name.lastIndexOf('.') + 1));

            if (extension === 'jpg' || extension === 'jpeg' || extension === 'png') {

                    var img=file.size;
                    var imgsize=img/1024;
                    if(imgsize >= 10 && imgsize <=110){
                        var reader = new FileReader();
                        reader.onload = imageIsLoaded;
                        reader.readAsDataURL(file);
                    }else{
                        swal("Information", "Image size must be less than or equal to 100 KB and greater than 10 KB!", "info");
                        $('#pic').val('');
                        $('#pri_image').attr('src', '{{asset('mdas_assets/images/user_add.png')}}');
                        exit();
                    }

            } else {
                swal("Information", "Please select only jpeg, jpg and png format only!", "info");
                $('#pic').val('');
                $('#pri_image').attr('src', '{{asset('mdas_assets/images/user_add.png')}}');
                exit();
            }

        }
$('#pri_image').click(function(e){
    e.preventDefault();
  $('#pic').click()
});

</script>
@endsection

