<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Visitor;

class visitors
{
    /**
     * Handle an incoming request
     * and count visitors in page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $page=url()->current();
        $viewed=Session::get('viewed', []);
        if (in_array($page, $viewed)) {
            return $next($request);
        } else {
            Session::push('viewed', $page);
            $daily_page_stats=Visitor::where('page', $page)->whereDate('created_at', date('Y-m-d'))->first();

            if (!$daily_page_stats) {
                $visitors = 1;
                Visitor::create([
                    'daily_visitors'=>$visitors,
                    'page'=> $page
                ]);
            } else {
                $visitors = $daily_page_stats->daily_visitors +1;
            }
            Visitor::whereDate('created_at', date('Y-m-d'))->first()->update(['daily_visitors'=> $visitors]);
            return $next($request);
        }
    }
}