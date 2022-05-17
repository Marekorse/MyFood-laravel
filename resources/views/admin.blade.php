@extends('master')
@section('content')
<navigation-panel
  :user="{{auth()->user() ?? 0}}"
></navigation-panel>
 <admin-panel
   :auth-id="{{auth()->id()}}"
 ></admin-panel> 
@endsection
