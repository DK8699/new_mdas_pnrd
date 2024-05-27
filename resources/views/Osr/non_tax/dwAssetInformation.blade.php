@php
    $page_title="assetInformation";
@endphp

@extends('layouts.app_user_osr')

@section('custom_css')
    <link href="https://fonts.googleapis.com/css?family=Old+Standard+TT&display=swap" rel="stylesheet">
<style>
    body{
        margin: 0px;
        padding: 0px;
        /*background: #e74c3c;*/
        /*font-family: 'Lato', sans-serif;*/
    }
        #exTab2 h3 {
  color : white;
  background-color: #428bca;
  padding : 5px 15px;
}
.nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {
    color: #fff;
    cursor: default;
    background-color: #FF5722;
    border: 1px solid #ddd;
    border-bottom-color: transparent;
}
.nav>li>a:focus, .nav>li>a:hover {
    text-decoration: none;
    background-color: #a4114c;
}

</style>
@endsection

@section('content')
<div class="row">
        <ol class="breadcrumb">
            <li><a href="">Home</a></li>
            <li><a  href="">OSR Non-Tax Resources</a></li>
            <li class="active"> osrAssetSingleBranchList</li>
        </ol>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 mb20">
         <a href="javascript:history.back()" style=""><button class="btn" onMouseOver="this.style.color='#fff'" style="border-radius: 18px;margin-bottom: 2px;"><i class="fa fa-arrow-left"></i> Back</button></a>    

        </div>
    </div>

        
        <h3> ASSET, BID & PAYMENT INFORMATION of <b>Bootboriya Haat</b> for the FY-2019-2020</h3></div>
     </div>
	 

<div id="exTab2" class="container"> 
<ul class="nav nav-tabs green-back">
    <li class="active">
        <a  href="#1" data-toggle="tab" style="color: #fff">Asset Information</a>
    </li>
    <li class=""><a href="#2" data-toggle="tab" style="color: #fff">Bid Information</a>
    </li>
    <li class=""><a href="#3" data-toggle="tab" style="color: #fff">Payment Information</a>
    </li>
</ul>

            <div class="tab-content ">
              <div class="tab-pane active" id="1">
                  <div class="table-responsive" style="background-color: #fff;">
                         <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="bold-text">Asset Name</td>
                                        <td>Bootboriya Haat</td>
                                    </tr>
                                    <tr>
                                        <td class="bold-text">Asset Code</td>
                                        <td>JOR-253-001</td>
                                    </tr>
                                        <tr>
                                            <td class="bold-text">Location</td>
                                            <td>Jorhat, Titabor, Bajali, reee</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Listing Date</td>
                                            <td>25th May 2018</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Brief Description</td>
                                            <td></td>
                                        </tr>
                                </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="2">
                    <div class="table-responsive" style="background-color: #fff;">
					<div class="table-responsive" style="background-color: #fff;">
                         <table class="table table-bordered">
                                <tbody>
								<tr>
								  <td colspan="2" style="text-align:center"><b>Bid Winner Information</b></td>
								</tr>
                                     <tr>
                                            <td class="bold-text">Bidder Name</td>
                                            <td>Rahul Hazarika</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Father Name</td>
                                            <td>Debanan Hazarika</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Address</td>
                                            <td>Dehajaan, Majuli, Assam</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Mobile No</td>
                                            <td>+91-9876543210</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Email ID</td>
                                            <td>rahul99@gmail.com</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">PAN</td>
                                            <td>AS123PAN356D</td>
                                        </tr>
                                </tbody>
                        </table>
                    </div>
                         <table class="table table-bordered">
                                <tbody>
								<tr>
								  <td colspan="2" style="text-align:center"><b>General Information</b></td>
								</tr>
                                    <tr>
                                        <td class="bold-text">Bid Value</td>
                                        <td>₹ 1,00,000.00</td>
                                    </tr>
                                    <tr>
                                        <td class="bold-text">Tender Date</td>
                                        <td>25th May 2018</td>
                                    </tr>
                                        <tr>
                                            <td class="bold-text">Advertisement</td>
                                            <td><a href="">Click to view</a></td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">No. of Bid Rejected</td>
                                            <td>25</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Withdrawn Bidders</td>
                                            <td>10</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">No. of Bid Rejected</td>
                                            <td>5</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Forfieted Withdrawn Bidders</td>
                                            <td>1</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Forfieted Earnest Money Deposit</td>
                                            <td>₹ 30,000.00</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Settled Amount</td>
                                            <td>5</td>
                                        </tr>
                                       
                                        <tr>
                                            <td class="bold-text">Contract Awarded Date</td>
                                            <td>16/July/2018</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">File No.</td>
                                            <td>AS-JOR-123</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Work Order No.</td>
                                            <td>AS-JOR-123</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Comparative Statement</td>
                                            <td><a href="{{asset('mdas_assets/images/docs/ComparativeBiddingReport .pdf')}}" target="_blank">Click to View the Document</a></td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Aggrement Letter</td>
                                            <td>Click to View the Document</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Work Order</td>
                                            <td>Click to View the Document</td>
                                        </tr>


                                </tbody>
                        </table>
                    </div>
                </div>
               <div class="tab-pane" id="3">
                    <div class="table-responsive" style="background-color: #fff;">
                         <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="bold-text">Payment Status</td>
                                        <td>Completed</td>
                                    </tr>
                                    <tr>
                                        <td class="bold-text">Settlement Amount</td>
                                        <td>₹ 1,00,000.00</td>
                                    </tr>
                                    <tr>
                                        <td class="bold-text">Total Collection (1st + 2nd + 3rd) Installments</td>
                                        <td>₹ 90,000.00</td>
                                    </tr>
                                    <tr>
                                        <td class="bold-text">Rebate Amount</td>
                                        <td>₹ 10,000.00</td>
                                    </tr>
                                    <tr>
                                        <td class="bold-text">Total Gap Period Collection</td>
                                        <td>₹ 45,000.00</td>
                                    </tr>
                                    <tr>
                                        <td class="bold-text" style="color: #a4114c">Total Net Collection</td>
                                        <td style="color: #a4114c; font-weight: 600">₹ 1,35,000.00</td>
                                    </tr>
                                </tbody>
                        </table>
                    </div>
                    <div class="table-responsive" style="background-color: #fff;">
                         <table class="table table-bordered">
                                <tbody>
                                    <tr class="bold-color">
                                        <td></td>
                                        <td>Amount</td>
                                        <td>ZP Share</td>
                                        <td>AP Share</td>
                                        <td>GP Share</td>
                                    </tr>
                                    <tr>
                                        <td class="bold-text">First Installment (Partial Payment)</td>
                                        <td>₹ 35,000.00</td>
                                        <td>₹ 7,000.00</td>
                                        <td>₹ 14,000.00</td>
                                        <td>₹ 14,000.00</td>
                                    </tr>
                                    <tr>
                                        <td class="bold-text">Second Installment (Partial Payment)</td>
                                        <td>₹ 35,000.00</td>
                                        <td>₹ 7,000.00</td>
                                        <td>₹ 14,000.00</td>
                                        <td>₹ 14,000.00</td>
                                    </tr>
                                    <tr>
                                        <td class="bold-text">Third Installment (Full Payment)</td>
                                        <td>₹ 20,000.00</td>
                                        <td>₹ 4,000.00</td>
                                        <td>₹ 8,000.00</td>
                                        <td>₹ 8,000.00</td>
                                    </tr>
                                    <tr>
                                        <td class="bold-text">Forfeited Earnest Money Deposit</td>
                                        <td>₹ 20,000.00</td>
                                        <td>NA</td>
                                        <td>NA</td>
                                        <td>NA</td>
                                    </tr>
                                    <tr>
                                        <td class="bold-text">Security Money Deposit</td>
                                        <td>₹ 30,000.00</td>
                                        <td>NA</td>
                                        <td>NA</td>
                                        <td>NA</td>
                                    </tr>
                                    <tr>
                                </tbody>
                        </table>
                    </div> 
                    <div class="table-responsive" style="background-color: #fff;">
                         <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="bold-text">Defaulter Name</td>
                                        <td>NA</td>
                                    </tr>
                                    <tr>
                                        <td class="bold-text">Defaulter's Father Name</td>
                                        <td>NA</td>
                                    </tr>
                                    <tr>
                                        <td class="bold-text">Bakijai</td>
                                        <td>No</td>
                                    </tr>
                                    <tr>
                                        <td class="bold-text">Bakijai Case no</td>
                                        <td>NA</td>
                                    </tr>
                                    <tr>
                                        <td class="bold-text">Bakijai Remarks</td>
                                        <td>NA</td>
                                    </tr>
                                </tbody>
                        </table>
                    </div>
               </div>
            </div>
  </div>
  <div class="mt40"></div>
    </div>

@endsection

@section('custom_js')
@endsection
