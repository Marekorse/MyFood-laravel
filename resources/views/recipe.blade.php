
@extends('master')
@section('title', $recipe->title)
@section('description', $recipe->teaser ?? 'jednoduche recepty na každý deň')
@section('content')
    <navigation-panel
        :user="{{$user ?? 0}}"
    ></navigation-panel>
    <div class=" md:flex justify-center items-start pt-14 md:pt-32  max-w-6xl m-auto">
        <recipe
            :book="{{ $book ?? 'null' }}"
            :user="{{$user ?? 0}}"
            :recipe-prop="{{$recipe}}"
        ></recipe>
    </div>  
@endsection    

    