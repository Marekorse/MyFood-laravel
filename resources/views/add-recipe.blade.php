@extends('master')
@section('content')
<navigation-panel
    :user="{{auth()->user() ?? 0}}"
></navigation-panel>
    <recipe-form
    action="newRecipe"
    :auth="{{auth()->id() ?? '0'}}"
    with-redirect
    ></recipe-form>
@endsection