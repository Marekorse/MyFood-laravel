@extends('master')
@section('content')
<navigation-panel
    search-allowed
    :user="{{$user ?? 0}}"
></navigation-panel>
<div class="pt-16 min-h-screen">
    <recipes-book
    :privacy-options="{{$privacy_options}}"
    card-url="{{$card_url}}"
    base-url="{{$base_url}}"
    :most-liked="{{$most_liked}}"
    :book="{{ $book }}"
    :shared="{{$shared}}"
    :user="{{$user}}"
    :user-popular-categories="{{ $user_popular_categories }}"
    />
</div>  
@endsection