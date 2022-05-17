@extends('master')
@section('title', 'my food')
@section('content')
  <navigation-panel
  search-allowed
  :user="{{$user ?? 0 }}"
  ></navigation-panel>
  <div class="pt-20 flex items-start flex-col md:flex-row">
    <recipes-container
      class="min-h-screen"
      card-url="{{$card_url}}"
      base-url="{{$base_url}}"
    ></recipes-container>
    <leaderboard
      :user="{{$user ?? 0}}"
      :most-liked="{{$most_liked}}"
      :most-popular-user="{{$most_popular_user}}"
      :most-commented="{{$most_commented}}"
      :most-popular-categories="{{$most_popular_categories}}"
      class="w-full md:w-6/12 xl:w-5/12"
    />
  </div>  
@endsection    
