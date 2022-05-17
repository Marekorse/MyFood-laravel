@extends('master')
@section('content')
<verify-email email="{{auth()->user()->email}}"></verify-email >
@endsection