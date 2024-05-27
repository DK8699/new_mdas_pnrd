@php
    $page_title="PRIs_Menbers";
@endphp

@extends('layouts.app_public')

@section('custom_css')
    <style>

    </style>
@endsection

@section('content')

    <div class="container">
        <h5>Demo Testing</h5>
        <div class="row">
            <form action="#" method="POST">
                <div class="form-group">
                    <label>Camera</label>
                    {{--<input type="file" accept="image/*;capture=camera">--}}
                    <input type="file" accept="image/*" capture="camera" />
                </div>
            </form>

            <p>Click the button to get your coordinates.</p>

            <button onclick="getLocation()">Try It</button>

            <p id="demo"></p>
        </div>
    </div>

@endsection

@section('custom_js')
    <script type="application/javascript">
        var x = document.getElementById("demo");

        function getLocation() {
            console.log("Hiii....1");
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                x.innerHTML = "Geolocation is not supported by this browser.";
            }
        }

        function showPosition(position) {
            console.log("Hiii....2");
            x.innerHTML = "Latitude: " + position.coords.latitude +
                "<br>Longitude: " + position.coords.longitude;
        }
    </script>
@endsection