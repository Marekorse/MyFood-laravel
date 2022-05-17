@extends('master')
@section('content')
<navigation-panel
    :user="{{auth()->user() ?? 0}}"
></navigation-panel>
<recipe-form
:book="{{$book}}"
:auth="{{auth()->id()}}"
:editable-recipe="{{$recept}}"
action="{{$action}}"
:selected-categories="{{$categories}}"
with-redirect
><recipe-form>    
@endsection