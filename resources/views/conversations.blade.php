@extends('master')
@section('content')
<div class="">
<navigation-panel
  :user="{{auth()->user() ?? 0}}"
></navigation-panel>  
</div>
  <conversations
  :Auth-user="{{auth()->user()}}"
  />    
@endsection