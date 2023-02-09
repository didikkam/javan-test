@extends('layouts.blank')

@section('content')
    <div class="lockscreen-wrapper">
        <div class="lockscreen-logo" style="font-size: 10rem;">
            <b>404</b>
        </div>
        <div class="lockscreen-name">
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found.</h3>
        </div>

        <div class="help-block text-center">
            We could not find the page you were looking for.
        </div>
        <div class="text-center my-3">
           <a href="{{route('base')}}" class="btn btn-primary"><i class="fas fa-home"></i> Home</a>
        </div>
        <div class="lockscreen-footer text-center">
            Copyright &copy; 2022 <b>{{ config('app.name', 'Laravel') }}</b><br>
            All rights reserved
        </div>
    </div>
@endsection
