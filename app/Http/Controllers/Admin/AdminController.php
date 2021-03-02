<?php

namespace App\Http\Controllers\Admin;

use App\Models\Career\Career;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Like\Like;
use App\Models\Transactions\Transactions;
use App\Models\Comment\Comment;
use DB;

class AdminController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $actor = User ::where('user_type', '1') -> count();
        $model = User ::where('user_type', '2') -> count();
        $musicians = User ::where('user_type', '3') -> count();
        $crew = User ::where('user_type', '4') -> count();
        $transactions = Transactions ::all() -> count();
        $latestTenUsers = User ::orderBy('id', 'desc') -> take(12) -> get();
        $activeusers = User ::orderBy('id', 'desc') -> where('status', 1) -> take(10) -> get();
        $inactiveusers = User ::leftjoin('subscriptions as s', 'users.id', '=', 's.user_id') -> where('stripe_status', '!=', 'active') -> orderBy('users.id', 'desc') -> take(10) -> get();
        $comments = Comment ::orderBy('id', 'desc') -> take(10) -> get();
        $like = Like ::leftjoin('users as u','u.id','=','likes.user_id')->selectRaw('likes.*,SUM(count) as userLikes')
            -> where('u.deleted_at',null)
            -> orderby('userLikes', 'DESC')
            -> groupBy('profile_id')
            -> take(10) -> get();
        $tentranscation = Transactions ::leftjoin('users as u','u.id','=','transactions.user_id')-> where('u.deleted_at',null)->orderBy('transactions.id', 'desc')  -> take(10) -> get();
        return view('admin/dashboard', compact('latestTenUsers', 'activeusers', 'inactiveusers', 'like', 'tentranscation', 'actor', 'model', 'musicians', 'transactions', 'comments', 'crew'));
    }


    public function chart()
    {

        $monthwisecount = DB ::select('SELECT  YEAR(created_at) AS y, MONTH(created_at) AS m, COUNT(DISTINCT id)as count FROM transactions  GROUP BY y,m');

        if (isset($monthwisecount)) {
            $year = array();
            foreach ($monthwisecount as $key => $value) {
                $year[$value -> y][$value -> m] = $value -> count;
            }
            for ($i = 1; $i <= 12; $i++) {
                foreach ($year as $key => $value) {
                    if (isset($value[$i])) {
                        $year[$key][$i] = $value[$i];
                    } else {
                        $year[$key][$i] = 0;
                    }
                    ksort($year);
                }
            }

            foreach ($year as $x => $x_value) {
                ksort($x_value);
                $year[$x] = $x_value;
            }

            return array(
                'data' => $year
            );
        }
    }

    public function pieChart()
    {

        $actorusers = DB ::table('users')
            -> select(DB ::raw('count(*) as actoruser'))
            -> where('user_type', '1')
            -> where('status', '1')
            -> get();

        $modelusers = DB ::table('users')
            -> select(DB ::raw('count(*) as modeluser'))
            -> where('user_type', '2')
            -> where('status', '1')
            -> get();
        $musicianusers = DB ::table('users')
            -> select(DB ::raw('count(*) as musicianuser'))
            -> where('user_type', '3')
            -> where('status', '1')
            -> get();
        $crewusers = DB ::table('users')
            -> select(DB ::raw('count(*) as crewnuser'))
            -> where('user_type', '4')
            -> where('status', '1')
            -> get();

        return [$actorusers[0] -> actoruser, $modelusers[0] -> modeluser, $musicianusers[0] -> musicianuser, $crewusers[0] -> crewnuser];

    }

}
