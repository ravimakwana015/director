<?php

use Illuminate\Support\Facades\Auth;
use App\Models\MembershipSubscriptionPlan\MembershipSubscriptionPlan;
use App\Models\Like\Like;
use App\Models\Pages\Page;
use App\Models\Settings\Settings;
use App\Models\CareerSEO\CareerSeo;
use App\Models\CareerPageSEO\CareerPageSeo;
use App\Models\DiscoverListSEO\DiscoverListSeo;
use App\Models\DiscoverPageSEO\DiscoverPageSeo;
use App\Models\ExploreListSEO\ExploreListSeo;
use App\Models\ExplorepageSEO\ExplorepageSeo;
use App\Models\generalseo\GeneralSeo;
use App\Models\UserFeedsLikes\UserFeedsLikes;
use App\Models\UserFeedComments\UserFeedComments;
use App\Models\UserNetwork\UserNetwork;
use App\Models\ForumCategory\ForumCategory;
use Carbon\Carbon;
if (!function_exists('dayDiff')) {
    function dayDiff($date)
    {
          $datework = $date;
          $now = Carbon::now();
          return $diff = $datework->diffInDays($now);
    }
}
if (!function_exists('forumCategory')) {
    function forumCategory()
    {
        return ForumCategory::where('status', 1)->orderBy('title', 'ASC')->get();
    }
}
if (!function_exists('getUserType')) {
    function getUserType($user_type)
    {
        if ($user_type == 'actor') {
            return $u_type = 1;
        } elseif ($user_type == 'model') {
            return $u_type = 2;
        } elseif ($user_type == 'musician') {
            return $u_type = 3;
        } elseif ($user_type == 'crew') {
            return $u_type = 4;
        }
    }
}
if (!function_exists('getUserTypeValue')) {
    function getUserTypeValue($user_type)
    {
        if ($user_type == '1') {
            return 'Actor';
        } elseif ($user_type == '2') {
            return 'Model';
        } elseif ($user_type == '3') {
            return 'Musician';
        } elseif ($user_type == '4') {
            return 'Creator';
        }
    }
}
if (!function_exists('getPersonalityTraits')) {
    function getPersonalityTraits($traits)
    {
        if ($traits == 'loneliness') {
            return $u_type = 'Determination';
        } elseif ($traits == 'entertainment') {
            return $u_type = 'Genre Flexibility';
        } elseif ($traits == 'curiosity') {
            return $u_type = 'Communication';
        } elseif ($traits == 'relationship') {
            return $u_type = 'Work Ethic';
        } elseif ($traits == 'hookup') {
            return $u_type = 'Honesty';
        }
    }
}

if (!function_exists('addScheme')) {
    function addScheme($url, $scheme = 'http://')
    {
        return parse_url($url, PHP_URL_SCHEME) === null ?
            $scheme . $url : $url;
    }
}

if (!function_exists('is_friend')) {

    function is_friend($friend_id)
    {
        $userFriend = UserNetwork::where('status', 1)->where(['user_id' => $friend_id, 'friend_id' => Auth::user()->id])
            ->orWhere(function ($query) use ($friend_id) {
                $query->where('status', 1)->where(['user_id' => Auth::user()->id, 'friend_id' => $friend_id]);
            })->where('status', 1)->first();
        if (isset($userFriend)) {
            return true;
        } else {
            return false;
        }
    }
}
if (!function_exists('userPostLike')) {

    function userPostLike($feed_id)
    {
        return UserFeedsLikes::leftjoin('users as u','u.id','=','user_feeds_likes.friend_id')-> where('u.deleted_at',null)->where('user_feeds_likes.friend_id', Auth::id())
            ->where('user_feeds_likes.feed_id', $feed_id)->first();
    }
}
if (!function_exists('userPostComments')) {

    function userPostComments($feed_id)
    {
        return UserFeedComments::leftjoin('users as u','u.id','=','user_feed_comments.friend_id')-> where('u.deleted_at',null)->where('user_feed_comments.feed_id', $feed_id)->orderBy('created_at',
            'DESC')->get(array('user_feed_comments.*'));
    }
}
if (!function_exists('user')) {

    function plan($plan_id)
    {
        return MembershipSubscriptionPlan::where('plan_id', $plan_id)->first();
    }
}
if (!function_exists('likeProfile')) {

    function likeProfile($user_id, $profile_id, $ip, $like_user_type)
    {
        return Like::create([
            'user_id' => $user_id,
            'profile_id' => $profile_id,
            'like_user_type' => $like_user_type,
            'ip_address' => $ip
        ]);
    }
}
if (!function_exists('likeCount')) {

    function likeCount($profile_id)
    {
        return Like::where('profile_id', $profile_id)->sum('count');
    }

}
if (!function_exists('settings')) {

    function settings()
    {
        return Settings::all();
    }
}
if (!function_exists('pages')) {
    function pages()
    {
        return Page::where('status', '1')->get();
    }
}

if (!function_exists('careerlist')) {
    function careerlist()
    {
        return CareerSeo::all();
    }
}

if (!function_exists('careerpage')) {
    function careerpage()
    {
        return CareerPageSeo::all();
    }
}
if (!function_exists('discoverlist')) {
    function discoverlist()
    {
        return DiscoverListSeo::all();
    }
}
if (!function_exists('discoverpage')) {
    function discoverpage()
    {
        return DiscoverPageSeo::all();
    }
}
if (!function_exists('explorelist')) {
    function explorelist()
    {
        return ExploreListSeo::all();
    }
}
if (!function_exists('explorepage')) {
    function explorepage()
    {
        return ExplorepageSeo::all();
    }
}
if (!function_exists('generalpage')) {
    function generalpage()
    {
        return GeneralSeo::all();
    }
}

function is404($exception)
{
    return $exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException
        || $exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
}

function log404($request)
{
    $error = [
        'url' => $request->url(),
        'method' => $request->method(),
        'data' => $request->all(),
    ];

    $message = '404: ' . $error['url'] . "\n" . json_encode($error, JSON_PRETTY_PRINT);

    \Log::debug($message);
}

function is301($exception)
{
    return $exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException
        || $exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
}

function log301($request)
{
    $error = [
        'url' => $request->url(),
        'method' => $request->method(),
        'data' => $request->all(),
    ];

    $message = '301: ' . $error['url'] . "\n" . json_encode($error, JSON_PRETTY_PRINT);

    \Log::debug($message);
}

function is401($exception)
{
    return $exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException
        || $exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
}

function log401($request)
{
    $error = [
        'url' => $request->url(),
        'method' => $request->method(),
        'data' => $request->all(),
    ];

    $message = '401: ' . $error['url'] . "\n" . json_encode($error, JSON_PRETTY_PRINT);

    \Log::debug($message);
}

?>
