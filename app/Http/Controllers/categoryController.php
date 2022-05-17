<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use App\Models\Category;
use Illuminate\Http\Request;

class categoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locale = App::currentLocale();
        $categories= Category::where('lang', $locale )->orderBy('position', 'asc')->get();
        return response( $categories,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category = new Category();
        $category->name = $request->name;
        $category->position = $request->position;
        $category->lang=App::currentLocale();
        $category->save();
        return $category;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        foreach (Category::where('lang',App::currentLocale())->get() as $category) {
            $id = $category->id;
            foreach ($request->categories as $new_category) {
                if ($new_category['id'] == $id) {
                    $category->update(['position' => $new_category['position']]);
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $category=Category::find($request->id);
        $category->delete();
    }
}
