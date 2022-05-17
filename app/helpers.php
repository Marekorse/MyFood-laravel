<?php

     function JSON_array_filter($json)
     {
         $decoded_json=json_decode($json);
         $filtered_array=array_filter($decoded_json);
         return json_encode($filtered_array);
     }

     function pagination($model, $options=false)
     {
         $options = (object)$options;
         $current_page= (int)$options->current_page;
         $paginate=$options->paginate;
         $class=  'App\Models\\' . $model;
         $offset = ($current_page * $paginate) - $paginate ;
         $prev_page = null;

         if ($offset>0) {
             $prev_page = $current_page - 1;
         }

         if (isset($options->where)) {
             $data = $class::skip($offset)->take($paginate)->where($options->where)->get();
             $number_of_pages=$class::where($options->where)->count() / $paginate ;
         } else {
             $data = $class::skip($offset)->take($paginate)->get();
             $number_of_pages=$class::all()->count() / $paginate ;
         };

         if (isset($options->load)) {
             if (is_object($options->load)) {
                 foreach ($options->load as $relation) {
                     $data->load($relation);
                 };
             } else {
                 $data->load($options->load);
             }
         };

         if (is_float($number_of_pages)) {
             $number_of_pages = (int)$number_of_pages +1;
         };

         if ((int)$current_page === (int)$number_of_pages) {
             $next_page = null;
         } else {
             $next_page = $current_page + 1;
         } ;

         return [
             'data' => $data,
             'numberOfPages'=> $number_of_pages,
             'prevPage'=> $prev_page,
             'nextPage' =>$next_page,
             'currentPage'=>$current_page
         ];
     }
