@extends('master')
@section('content')
<navigation-panel
    search-allowed
    :user="{{auth()->user() ?? 0}}"
></navigation-panel>
<user-panel
:auth-id="{{$auth_id}}  "
:user="{{$user}}"
:book-access="{{json_encode($book_access)}}"
:avg-rating="{{$avg_ratings}}"
></user-panel>
@endsection