@extends('master')
@section('content')
<navigation-panel
    :user="{{auth()->user() ?? 0}}"
></navigation-panel>
<recipe-form
:auth="{{auth()->id()}}"
:editable-recipe="{{$recept}}"
action="editRecipe"
:selected-categories="{{$categories}}"
with-redirect
><recipe-form>   
    
@endsection