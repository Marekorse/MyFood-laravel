@extends('master')
@section('content')
<navigation-panel
    :user="{{auth()->user() ?? 0}}"
></navigation-panel>
<user-settings
:prop-user="{{$user}}"
></user-settings>

    
@endsection