@extends('master')
@section('content')
   <reset-password token="{{$request->route('token')}}"></reset-password>
@endsection