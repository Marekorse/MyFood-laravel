<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipesController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\AdminController;;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ConversationsController;
use App\Http\Controllers\ratingsController;
use App\Http\Controllers\bookController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\FollowersController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\categoryController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\ImprovedRecipeController;
use GuzzleHttp\Middleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
|--------------------------------------------------------------------------
| Settings
|--------------------------------------------------------------------------
*/
Route::middleware(['isadmin'])->group(function () {

     Route::get('/configclear', [ConfigController::class, 'resetConfig']);
     Route::get('/link', [ConfigController::class, 'link']);
     Route::get('/privacy', [ConfigController::class, 'seedPrivacyOptions']);
     Route::get('/roles', [ConfigController::class, 'seedRoles']);

});
/*
|--------------------------------------------------------------------------
| Guest
|--------------------------------------------------------------------------
*/
     Route::middleware(['verify', 'visitors'])->group(function () {

          /*
          Recipe
          */

               Route::get('/recept/novy', [RecipesController::class, 'create']);
               Route::post('/recept', [RecipesController::class, 'store']);
               Route::get('recepty', [RecipesController::class, 'index']);
               Route::get('/', [RecipesController::class, 'index']);
               Route::get('/recept/{id}', [RecipesController::class, 'show']);

          /*
          Rating
          */

               Route::get('/rating', [RatingsController::class, 'index']);
               Route::post('/rating', [RatingsController::class, 'store']);

          /*
          Comment
          */

               Route::resource('/comments', CommentsController::class)->only(['index'])->middleware('isAjax');

          /*
          Likes
          */

               Route::resource('/likes', LikesController::class)->only(['index']);

          /*
          followers
          */

               Route::resource('/followers', FollowersController::class)->only(['index']);
          /*
          Rating
          */

               Route::get('/rating/users', [RatingsController::class, 'UsersRatings']);
          /*
          categories
          */
               Route::get('/category', [categoryController::class, 'index']);



     });

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

     Route::middleware(['isadmin', 'auth','verify'])->group(function () {
          /*
          todo
          */
               Route::resource('/todo', TodoController::class);
               Route::put('/task/reorder', [TasksController::class, 'reorder']);
               Route::resource('/task', TasksController::class);
               Route::resource('/user', UsersController::class);
          /*
          Admin
          */
               Route::get('/admin', [AdminController::class, 'index']);
               Route::get('/admin/stats', [AdminController::class, 'showStatsByDate']);
               Route::get('/admin/users', [AdminController::class, 'usersFilter']);
               Route::get('/admin/recipes', [AdminController::class, 'recipesFilter']);
               Route::put('/admin/recipe/{id}', [AdminController::class, 'recipeAdministration']);
               Route::delete('/admin/recipe/{id}', [AdminController::class, 'recipeAdministration']);
               Route::put('/admin/user/{id}', [AdminController::class, 'userAdministration']);
               Route::delete('/admin/user/{id}', [AdminController::class, 'deleteUser']);
          /*
          category
          */
               Route::put('/category', [categoryController::class, 'update']);
               Route::post('/category', [categoryController::class, 'store']);
               Route::delete('/category/{id}', [categoryController::class, 'destroy']);

     });

     Route::middleware(['auth','verify'])->group(function () {
          /*
          user
          */
               Route::get('/pouzivatel/{id}', [UsersController::class, 'show']);
               Route::get('/nastavenia', [UsersController::class, 'edit']);
               Route::put('/user/online/{id}', [UsersController::class, 'isOnline']);
               Route::put('/user/offline/{id}', [UsersController::class, 'isOffline']);
               Route::resource('/user', UsersController::class)->only(['update', 'destroy',]);
               Route::delete('/notification/all', [NotificationController::class, 'destroyAll']);
               Route::put('/notification', [NotificationController::class, 'markAsRead']);
               Route::resource('/notification', NotificationController::class)->only(['index', 'destroy']);

          /*
          Recipe
          */

               Route::resource('/recept', RecipesController::class)->only(['update', 'destroy', 'edit']);

          /*
          Conversation
          */
               Route::get('/images/conversations/{id}/{filename}/{thumb?}', [ImagesController::class, 'showConversationImage']);
               Route::resource('/spravy', ConversationsController::class)->only('index');
               Route::resource('/conversation', ConversationsController::class)->only('store', 'show', 'destroy');
               Route::resource('/messages', MessagesController::class)->only('store', 'destroy');

          /*
          RecipesBook
          */
               
               
               Route::delete('/book/{book}/user/{id}', [bookController::class, 'destroyUser']);
               Route::delete('/book/{book}/recipe/{id}', [bookController::class, 'destroyRecipe']);
               Route::put('/book/{id}/name', [bookController::class, 'updateBookName']);
               Route::put('/book/{id}/privacy', [bookController::class, 'updateBookPrivacy']);
               Route::post('/book/{book}/recipe', [bookController::class, 'storeRecipe']);
               Route::post('/book/{book}/user', [bookController::class, 'storeUser']);
               Route::get('/receptar/{id?}', [bookController::class, 'showBook']);
               Route::get('/book/users', [bookController::class, 'showUsers']);
               Route::get('/book/shared', [bookController::class, 'showSharedbooks']);
               Route::post('/book/request', [bookController::class, 'request']);


          /*
          ImprovedRecipes
          */
               Route::get('/images/improvedRecipes/{id}/{filename}', [ImagesController::class, 'showRecipeBookImage']);
               Route::get('/receptar/{book}/recept/{id}', [ImprovedRecipeController::class, 'show']);
               Route::get('/receptar/{book}/recept/{id}/edit', [ImprovedRecipeController::class, 'edit']);
               Route::get('/receptar/{book}/recept/{id}/create', [ImprovedRecipeController::class, 'create']);
               Route::post('/receptar/{book}/recept/{id}', [ImprovedRecipeController::class, 'store']);
               Route::put('/receptar/{book}/recept/{id}', [ImprovedRecipeController::class, 'update']);
               Route::delete('/receptar/{book}/recept/{id}', [ImprovedRecipeController::class, 'destroy']);

          /*
          Comment
          */

               Route::resource('/comments', CommentsController::class)->only([
                    'store', 'destroy', 'update'
               ])->middleware('isAjax');

          /*
          Likes
          */

               Route::resource('/likes', LikesController::class)->only(['store', 'destroy']);

          /*
          followers
          */

               Route::post('/followers', [FollowersController::class, 'store']);
               Route::delete('/followers/{id}', [FollowersController::class, 'destroy']);
               Route::post('/followers/request', [FollowersController::class, 'request']);

     });

require __DIR__ . '/auth.php';
