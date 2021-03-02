<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route ::prefix('v1') -> group(function () {

    Route ::post('login', 'API\AuthController@login');
    Route ::post('signup', 'API\RegisterController@signup');

    Route ::get('career', 'API\CareerController@index');
    Route ::get('develop', 'API\DevelopController@index');
    Route ::get('enter', 'API\EnterController@index');
    Route ::get('forum', 'API\ForumController@index');
    Route ::post('contact', 'API\ContactController@sendMessage');

    Route ::get('/profiles', 'API\UserController@index');
    Route ::post('/search-profiles', 'API\UserController@searchProfiles');
    Route ::get('/profile-details', 'API\UserController@userProfile');
    Route ::post('/contact-form', 'API\UserController@contactForm');


    Route ::group(['middleware' => 'auth:api'], function () {

        Route ::post('/edit/user/profile', 'API\EdituserController@userProfile');
        Route ::post('/update/user/profile', 'API\EdituserController@updateProfile');
        Route ::post('/user/photo/gallery', 'API\EdituserController@uploadPhotos');
        Route ::post('/delete/photo/gallery', 'API\EdituserController@deletePhotos');
        Route ::post('/user/video/gallery', 'API\EdituserController@uploadVideos');
        Route ::post('/delete/video/gallery', 'API\EdituserController@deleteVideo');
        Route ::post('/uploadproficepic', 'API\EdituserController@saveProfilePictute');
        Route ::post('/uploadCV', 'API\EdituserController@uploadCv');

        Route ::get('/plans', 'API\RegisterController@plans');
        Route ::get('/get-plans', 'API\RegisterController@getPlans');
        Route ::post('/check-coupon', 'API\RegisterController@checkCoupon');
        Route ::post('/order-post', 'API\RegisterController@orderPost');

        Route ::post('/career/details', 'API\CareerController@applicationForm');
        Route ::post('/send/career/application', 'API\CareerController@sendApplication');

        Route ::post('/develop/details', 'API\DevelopController@applicationForm');
        Route ::post('/send/develop/application', 'API\DevelopController@sendApplication');

        Route ::post('/enter/details', 'API\EnterController@applicationForm');
        Route ::post('/send/enter/application', 'API\EnterController@sendApplication');

        Route ::get('/forum-search', 'API\ForumController@searchForum');
        Route ::post('/topic', 'API\ForumController@topic');
        Route ::get('/forum/category', 'API\ForumController@forumCategory');
        Route ::post('/add/forum/topic', 'API\ForumController@addTopic');
        Route ::post('/add/forum/topic/comment', 'API\ForumController@addComment');

        Route ::get('/dashboard', 'API\DashboardController@index');
        Route ::post('/addCard', 'API\DashboardController@addCard');
        Route ::post('/getCard', 'API\DashboardController@getCard');
        Route ::post('/updateCard', 'API\DashboardController@updateCard');
        Route ::post('/deleteCard', 'API\DashboardController@deleteCard');
        Route ::post('/setDefaultCard', 'API\DashboardController@setDefaultCard');
        Route ::post('/getCountry', 'API\DashboardController@getCountry');
        Route ::post('/increasePersonality', 'API\DashboardController@increasePersonality');
        Route ::post('/shownotification', 'API\DashboardController@showNotification');
        Route ::post('/readnotification', 'API\DashboardController@readNotification');
        Route ::post('/collect/reward', 'API\DashboardController@collectReward');
        Route ::post('/change/membership/plan', 'API\DashboardController@changeMembershipPlan');

        Route ::get('/referfriend', 'API\ReferController@index');
        Route ::post('/send/referfriend', 'API\ReferController@sendMessage');
        Route ::get('refer/{token}', 'API\ReferController@registerfriend');

        Route ::post('like', 'API\UserController@likeProfile');
        Route ::post('unlike', 'API\UserController@unlikeProfile');

        Route ::post('increase-personality', 'API\UserController@increasePersonality');

        Route ::post('/add/friend', 'API\UserNetworkController@addFriend');
        Route ::post('/confirm/friend', 'API\UserNetworkController@confirmFriend');
        Route ::post('/remove/friend', 'API\UserNetworkController@removeFriend');

        Route ::get('/network', 'API\UserNetworkController@myNetwork');
        Route ::post('/add/user/status', 'API\UserNetworkController@addUserStatus');
        Route ::post('/like/post', 'API\UserNetworkController@likePost');
        Route ::post('/dis/like/post', 'API\UserNetworkController@disLikePost');
        Route ::post('/post/comment', 'API\UserNetworkController@postComment');
        Route ::post('/get/post', 'API\UserNetworkController@getPost');
        Route ::post('/update/post', 'API\UserNetworkController@updatePost');
        Route ::post('/delete/post', 'API\UserNetworkController@deletePost');

        Route ::get('/chat', 'API\MessageController@chatWithUser');
        Route ::get('/chatUsers', 'API\MessageController@chatUsers');
        Route ::get('/private-messages', 'API\MessageController@privateMessages');
        Route ::post('/send-message', 'API\MessageController@sendPrivateMessage');

        Route ::get('logout', 'API\AuthController@logout');
    });
});
