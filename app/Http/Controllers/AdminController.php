<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Display view.
     * @return Response
     */
    public function index(Request $request)
    {
        return view('admin');
    }

    /**
     *show statistics by date.
     *@return Response
     */
    public function showStatsByDate(Request $request)
    {
        $day=$request->day;
        $month=$request->month;
        $year=$request->year;

        $models = ['Visitor', 'User', 'Recipe', 'Notification'];

        foreach ($models as $model_name) {
            $model = 'App\Models\\' . $model_name;

            ${$model_name . 's'} = $model::when($day, function ($query, $day) {
                return $query->whereDay('created_at', $day);
            })
                ->when($month, function ($query, $month) {
                    return $query->whereMonth('created_at', $month);
                })
                ->when($year, function ($query, $year) {
                    return $query->whereYear('created_at', $year);
                });
        }
        return response([
            'visitors' => (int) $Visitors->sum('daily_visitors'),
            'users' => $Users->count(),
            'recipes' => $Recipes->count(),
            'events' => $Notifications->count(),
        ]);
    }

    /**
     * filter users on admin page.
     * @return Response
     */
    public function usersFilter(Request $request)
    {
        $verified=$request->email == 'verified';
        $unverified=$request->email == 'unverified';
        $newest=$request->group == 'newest';
        $oldest=$request->group == 'oldest';
        $role=$request->role ;

        $users=User::

        when($verified, function ($query, $confirmation) {
            $query->whereNotNull('email_verified_at');
        })
        ->when($unverified, function ($query, $confirmation) {
            $query->whereNull('email_verified_at');
        })
        ->when($role, function ($query ,$role) {
            $query->where('role_id',$role);
        })
        ->when($oldest, function ($query) {
            $query->oldest();
        })
        ->when($newest, function ($query) {
            $query->latest();
        })
        ->paginate(12);
        
        return response($users,200);
    }

    /**
     * filter recipes on admin page.
     *
     * @return Response
     */

    public function recipesFilter(Request $request)
    {

        $confirmed=$request->confirmation == 'confirmed';
        $unconfirmed=$request->confirmation == 'unconfirmed';
        $user=$request->user == 'user';
        $guest=$request->user == 'guest';
        $newest=$request->group == 'newest';
        $oldest=$request->group == 'oldest';

        $recipes=Recipe::
            when($confirmed, function ($query, $confirmation) {
                $query->whereNotNull('confirmation');
            })
            ->when($unconfirmed, function ($query, $confirmation) {
                $query->whereNull('confirmation');
            })
            ->when($user, function ($query) {
                $query->whereNotNull('user_id');
            })
            ->when($guest, function ($query) {
                $query->whereNull('user_id');
            })
            ->when($oldest, function ($query,$oldest) {
                $query->oldest();
            })
            ->when($newest, function ($query) {
                $query->latest();
            })
            ->with('user')
            ->paginate(12);

        return response($recipes,200);

    }

    /**
     * users admninistration.
     *
     * @return Response
     */

    public function userAdministration(Request $request, $id)
    {
        $request->validate([
            'role'=> Rule::in([1, 2]),
        ]);

        $user = User::find($id);

        $this->authorize('isAdmin', $user);

        $user->update([
            'role_id' => $request->role_id,
        ]);
    }

    /**
     * Remove the specified user.
     *
     * @id  \App\Models\user  $user
     * @return Response
     */
    public function deleteUser(Request $request, $id)
    {
        $user=User::find($id);

        $this->authorize('delete', $user);

        Storage::deleteDirectory("public/images/users/$user->name/");

        $user->delete();

        return response()->json(['message'=> 'User deleted'], 200);
        
    }

    /**
     * Confirm recipes from unregistered users.
     *
     * @id  \App\Models\user  $user
     * @return Response
     */

    public function recipeAdministration(Request $request, $id)
    {
        $request->validate([
            'confirmation' => 'integer',
        ]);

        $recipe = Recipe::find($id);

        $recipe->update([
            'confirmation' => $request->confirmation,
        ]);
    }
}
