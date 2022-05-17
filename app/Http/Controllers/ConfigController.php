<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use App\Models\Role;
use App\Models\Privacy;

class ConfigController extends Controller
{
    public function resetConfig()
    {
       Artisan::call('config:cache');

       Artisan::call('route:cache');

       Artisan::call('view:cache');

      return 
      '<pre>
      ====================
        config clear 
      ====================
        route cache 
      ====================
        view cache 
      ====================
      </pre>'
      ;
    }
    public function link()
    {
      Artisan::call('storage:link', []);
   
      return '<pre>
      ====================
        Link created
      ====================
      </pre>';
    }
    public function seedPrivacyOptions()
    {
       Privacy::create(['privacy' => 'private']);
       Privacy::create(['privacy' => 'only_followers']);
       Privacy::create(['privacy' => 'public']);

      return '<pre>
      ====================
        PrivacyOptions created
      ====================
      </pre>';
    }
    public function seedRoles()
    {
       Role::create(['role' => 'administrator']);
       Role::create(['role' => 'user']);

      return '<pre>
      ====================
        roles created
      ====================
      </pre>';
    }
}
