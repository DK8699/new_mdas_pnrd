@php
    $page_title="OSR Dashboard";
@endphp

@extends('layouts.app_user_osr')

@section('custom_css')
    <link href="https://fonts.googleapis.com/css?family=Old+Standard+TT&display=swap" rel="stylesheet">
    <style>

        .rep{
            background-color: #211d1f;
            border-radius: 2px;
            padding: 6px 4px 6px 5px;
        }
        rep.focus, .rep:focus, .rep:hover {
            color: #f7efef;
        }
        
    </style>
@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Home</a></li>
            <li><a href="{{url('osr/osr_panel')}}/{{encrypt($data['fy_id'])}}">OSR</a></li>
            <li class="active">Asset List Download &amp; Upload</li>
        </ol>
    </div>
<div class="container">
    <div class="row mt40">
            <div class="col-md-12 col-sm-12 col-xs-12">
                  <h4  style="color:#e11724;background-color: #fbcece;padding: 10px;margin-bottom: 39px;border-radius: 12px;"><span>*Download the report of the shortlisted asset, sign it and upload</span></h4>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable1">
                        <thead>
                        <tr class="bg-primary">
                            <td>SL</td>
                            <td>Finacial Year</td>
                            <td>Download PDF</td>
                            <td>Upload PDF(<span style="color:#e11724">*Sign and Upload</span>)</td>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                                @foreach($data['fyList'] as $li)
                                <?php 
                                $result = DB::select('select * from osr_non_tax_signed_asset_reports where osr_fy_year_id=? AND zila_id=?', [$li->id,$z_id]);
                                
                                ?>
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$li->fy_name}}</td>
                                    <td><a target="_blank" href="{{route('osr.non_tax.asset.download.assetReport', [encrypt($li->id), encrypt($z_id)])}}" class="btn rep">
                                        <i class="fa fa-download"></i>
                                        Download Asset Report
                                    </a>  
                                    @if(empty($result))
                                    <td>
                                    <p id="viewAttachment_{{$li->id}}" style="display:none">
                                                <a href="" target="_blank" class="btn btn-success btn-xs" id="attachment_view_link_{{$li->id}}">
                                                    <i class="fa fa-check"></i>
                                                    View
                                                </a>
                                                <button type="button" class="btn btn-warning btn-xs edit_attachment" data-fy_id="{{$li->id}}">
                                                    <i class="fa fa-edit"></i>
                                                    Edit
                                                </button>
                                    </p>
                                    <form action="#" method="POST" id="attachmentForm_{{$li->id}}">
                                        <input type="hidden" name="fy_id" value="{{$li->id}}"/>

                                        <input type="file" class="form-control" name="attachment"/>
                                        <button type="submit" class="btn btn-primary btn-xs" id="upload_attach_{{$li->id}}" style="margin-top: 4px;">
                                            <i class="fa fa-upload"></i>
                                            Upload
                                        </button>
                                            <button type="button" class="btn btn-danger btn-xs cancel_attachment" id="cancel_attach_btn{{$li->id}}" data-fy_id="{{$li->id}}" style="margin-top: 4px; display:none">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                        Cancel
                                            </button>
                                    </form>
                                    </td>
                                    @else
                                    <td>
                                    <p id="viewAttachment1_{{$li->id}}">
                                        
                                                <a href="{{route('osr.non_tax.asset.shortlist.report.view', [encrypt($li->id), encrypt($z_id)])}}" 
                                                   target="_blank" class="btn btn-success btn-xs" id="attachment_view_link1_{{$li->id}}">
                                                    <i class="fa fa-check"></i>
                                                    View
                                                </a>
                                                <button type="button" class="btn btn-warning btn-xs edit_attachment1" data-fy_id="{{$li->id}}">
                                                    <i class="fa fa-edit"></i>
                                                    Edit
                                                </button>
                                    </p>
                                       <form action="#" method="POST" id="attachmentForm1_{{$li->id}}" style="display:none">
                                        <input type="hidden" name="fy_id" value="{{$li->id}}"/>

                                        <input type="file" class="form-control" name="attachment"/>
                                        <button type="submit" class="btn btn-primary btn-xs" id="upload_attach1_{{$li->id}}" style="margin-top: 4px;">
                                            <i class="fa fa-upload"></i>
                                            Upload
                                        </button>
                                            <button type="button" class="btn btn-danger btn-xs cancel_attachment1" id="cancel_attach_btn1_{{$li->id}}" data-fy_id="{{$li->id}}" style="margin-top: 4px;">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                        Cancel
                                            </button>
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                        @php $i++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</div>
@endsection

@section('custom_js')
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
    
    <script type="application/javascript">
        
        $('.edit_attachment1').on('click', function(e){
            e.preventDefault();
            var fy_id= $(this).data('fy_id');
            if(fy_id){
                $('#viewAttachment1_'+fy_id).hide();
                $('#cancel_attach_btn1_'+fy_id).show();
                $('#attachmentForm1_'+fy_id).show();
            }

        });
        
        $(document).on("click",".cancel_attachment1",function(e){
            e.preventDefault();
            var fy_id= $(this).data('fy_id');
            if(fy_id){
                $('#viewAttachment1_'+fy_id).show();
                $('#attachmentForm1_'+fy_id).hide();
                $('#cancel_attach_btn1_'+fy_id).hide();
            }
        });
        
        @foreach($data['fyList'] as $li)
        
        $('#attachmentForm1_{{$li->id}}').validate({
            rules: {
                attachment: {
                    required: true,
                }
            },
        });
        
        $('#attachmentForm1_{{$li->id}}').on('submit', function(e){
            e.preventDefault();
            $('.form_errors').remove();

            if($('#attachmentForm1_{{$li->id}}').valid()) {
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('osr.non_tax.asset.attachment_upload')}}',
                    dataType: "json",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.msgType == true) {
                            $('#viewAttachment1_'+data.data.fy_id).show();
                            $('#attachmentForm1_'+data.data.fy_id).hide();
                            $('#attachment_view_link1_'+data.data.fy_id).attr('href', data.data.imgUrl+data.data.attachment_path);
                            $('#cancel_attach1_'+data.data.fy_id).remove();
                        } else {
                            if (data.msg == "VE") {
                                swal("Error", "Please select attachment to upload. The attachment must be in pdf format only. Maximum size is 400KB and minimum 10KB", 'error');
                            } else {
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
            }else{
                alert('Please select attachment.');
            }
        });
        
         @endforeach
        
        
        $('.edit_attachment').on('click', function(e){
            e.preventDefault();
            var fy_id= $(this).data('fy_id');
            if(fy_id){
                $('#viewAttachment_'+fy_id).hide();
                $('#cancel_attach_btn'+fy_id).show();
                $('#attachmentForm_'+fy_id).show();
            }

        });
        
        $(document).on("click",".cancel_attachment",function(e){
            e.preventDefault();
            var fy_id= $(this).data('fy_id');
            if(fy_id){
                $('#viewAttachment_'+fy_id).show();
                $('#attachmentForm_'+fy_id).hide();
                $('#cancel_attach_'+fy_id).hide();
            }
        });
        
        @foreach($data['fyList'] as $li)
        
        $('#attachmentForm_{{$li->id}}').validate({
            rules: {
                attachment: {
                    required: true,
                }
            },
        });
        
        $('#attachmentForm_{{$li->id}}').on('submit', function(e){
            e.preventDefault();
            $('.form_errors').remove();

            if($('#attachmentForm_{{$li->id}}').valid()) {
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('osr.non_tax.asset.attachment_upload')}}',
                    dataType: "json",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.msgType == true) {
                            $('#viewAttachment_'+data.data.fy_id).show();
                            $('#attachmentForm_'+data.data.fy_id).hide();
                            $('#attachment_view_link_'+data.data.fy_id).attr('href', data.data.imgUrl+data.data.attachment_path);
                            $('#cancel_attach_'+data.data.fy_id).remove();
                           
                        } else {
                            if (data.msg == "VE") {
                                swal("Error", "Please select attachment to upload. The attachment must be in pdf format only. Maximum size is 400KB and minimum 10KB", 'error');
                            } else {
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
            }else{
                alert('Please select attachment.');
            }
        });

        
        @endforeach

    </script>
@endsection