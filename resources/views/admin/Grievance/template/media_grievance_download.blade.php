<!DOCTYPE html>
<html>
<head>
    <title>Media Grievance</title>
</head>
<body style="font-size: 11px;">
        <style type="text/css">
        table tr td{
            border-color: #DCDCDC;
            border-style: solid;
            border-width: 0px 1px 1px 0;
        }
        table tr th{
            border-color: #DCDCDC;
            border-style: solid;
            border-width: 0px 1px 1px 0;
        }

        .head{
            width: 100%;
        }
        .head p{
            text-align: center;
        }

        .txt-upper{
            text-transform: uppercase;
        }

        .left{
           text-align: left;
        }

        td,th {
            padding-left: 5px;
            padding-right: 5px;
        }

        p{  margin:3px 5px 3px 5px;  }

        .table{
            width: 100%;
            margin-bottom: 0px;
            border-top: 1px solid #DCDCDC;
            border-left: 1px solid #DCDCDC;
            font-size: 14px;
        }
        .table1{
            width: 100%;
            margin-bottom: 0px;
            border-top: 1px solid #DCDCDC;
            border-left: 1px solid #DCDCDC;
            font-size: 14px;
        }
        .table_date{
                width: 100%;
                margin-bottom: 0px;
                font-size: 14px;
                font-weight:800;
         }
            .table2 td{
                border:none;
            }
       
        .table td{
            width:25%;
        }
        .table .main-heading{
            font-size: 16px;
            font-weight: 500;
        }
        .table .label, .table1 .label{
            font-size: 14px;
            font-weight: 700;
        }

        h1{
            margin-top:30px;
        }
        
          .page-break { 
            page-break-before: always;
            }

    </style>
    

    <table cellpadding="0" cellspacing="0" class="head">
        <!--<tr>
            <td style="padding-left:310px; border:none;">
                <img src="{{asset('mdas_assets/images/gov_assam_b.png')}}" alt="LOGO" style="width: 80px;"/>
            </td>
        </tr>-->
        <tr>
            <td style="border: 0px;">
                <p style="font-size:20px; text-decoration:underline;" class="txt-upper">DETAILS OF GRIEVANCES RECIEVED THROUGH VARIOUS MEDIA</p>
            </td>
        </tr>
    </table>
	
	<table cellspacing="0" class="table" style="margin-top:20px;">
		<tr>
			<th><p class="label">Published Date</p></th>
			<th><p class="label">Media</p></th>
			<th><p class="label">Publisher</p></th>
			<th><p class="label">Nature of Grievance</p></th>
			<th><p class="label">District/State</p></th>
			<th><p class="label">Block</p></th>
			<th><p class="label">GP/VCDC/VDC</p></th>
			<th><p class="label">Action taken</p></th>
			<th><p class="label">Remarks</p></th>
		</tr>
		@foreach($grievanceList as $li)
		<tr>
			<td style="font-size:14px;">{{Carbon\Carbon::parse($li->published_date)->format('d/m/Y')}}</td>
			<td style="font-size:14px;">{{$li->name}}</td>
			<td style="font-size:14px;"><h3>{{$li->name_of_media_publisher}}</h3></td>
			<td style="font-size:14px;">{{$li->description}}</td>
			<td style="font-size:14px;">
			@if(!($li->district_name))
				STATE
			@else
				{{$li->district_name}}
			@endif
			</td>
			<td style="font-size:14px;">{{$li->block_name}}</td>
			<td style="font-size:14px;">{{$li->gram_panchayat_name}}</td>
			<td></td>
			<td></td>
			
		</tr>
		 @endforeach
	</table>
</body>
</html>