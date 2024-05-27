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
		   p{
			   font-size:18px;
		   }

    </style>
    

    <table cellpadding="0" cellspacing="0" class="head">
        <tr>
            <td style="border: 0px;">
                <p style="font-size:25px;" class="txt-upper">Acknowledgement</p>
            </td>
        </tr>
    </table>
	<hr style="border: 1px solid black" />
	<span style="margin-left:510px; font-size:20px;">Date: {{Carbon\Carbon::parse($grievanceData->entry_date)->format('d M Y')}} </span><br><br><br>
	
	<p> Dear Mr./Mrs./Ms. {{$grievanceData->name}},</p><br><br>
	
	<p> Sub: Customer Complaint Number  <span style="font-weight:bold">{{$grievanceData->grievance_code}}</span></p><br><br><br><br>
	
	<p> We acknowledge receipt of your complaint dated: {{Carbon\Carbon::parse($grievanceData->entry_date)->format('d/m/Y')}} and alloted complaint number <span style="font-weight:bold">{{$grievanceData->grievance_code}}</span> to your complaint.</p><br><br>
	
	<p>We assure you our priority attention at all times.</p><br><br><br>
	
	<p>Your Faithfully,</p><br><br><br>
	
	<p>............................................................</p>
</body>
</html>