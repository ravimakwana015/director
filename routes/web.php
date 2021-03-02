<?php
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
//Clear Cache facade value:
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});


Route::get('/sitemap.xml', 'SitemapController@index');
Route::post('stripe/webhook','WebhookController@handleWebhook');

Route::get('/', 'HomeController@index')->name('home');
Route::get('contact-us', 'ContactController@index')->name('contact.us');
Route::get('page/{slug}', 'PageController@index')->name('page');
Route::post('send-message', 'ContactController@sendMessage')->name('send.message');

Route::any('profiles', 'UserController@index')->name('users');
Route::get('profile-details/{username}', 'UserController@userProfile')->name('profile-details');

Route::get('/forum-list', 'ForumController@showList')->name('forumlist');
Route::get('/topic/{id}', 'ForumController@topic')->name('topic');
Route::get('/form-category/{category_id}', 'ForumController@formCategory')->name('form-category');

Route::post('contact-form', 'UserController@contactForm')->name('contact.form');
Route::get('getSearchOptions', 'UserController@getSearchOptions')->name('getSearchOptions');

Route::get('/search-list', 'SearchController@searchList')->name('searchlist');
Route::any('/advance-search-list', 'SearchController@advanceSearchList')->name('advancesearchlist');
Route::get('/advance-search-lists', 'SearchController@advanceSearchReset')->name('advancesearchlists');
Route::get('/{username}/activity/', 'ActivityController@index')->name('user.activity');

Route::get('/career', 'CareerController@index')->name('career');
Route::get('/get/career/search/options', 'CareerController@getCareerSearchOptions')->name('get.career.search.options');
Route::get('/enter', 'DiscoverController@index')->name('discover');
Route::get('/get/discover/search/options', 'DiscoverController@getCareerSearchOptions')->name('get.discover.search.options');
Route::get('/develop', 'ExploreController@index')->name('explore');
Route::get('/get/develop/search/options', 'ExploreController@getDevelopSearchOptions')->name('get.develop.search.options');

Route::post('/sessiondata', 'HomeController@sessionvalue')->name('transfer');
Route::post('/get-country', 'HomeController@getCountry')->name('get.user.country');

Auth::routes();
Route::get('/instagram', 'Auth\InstagramController@redirectToInstagramProvider')->name('instagram');
Route::get('/instagram/callback', 'Auth\InstagramController@handleProviderInstagramCallback');

Route::group(['middleware' => ['subscription']], function () {

	Route::group(['middleware' => 'auth'], function () {

		Route::get('/chat', 'MessageController@privateChat')->name('chat.index');
		Route::get('/user-chat/{id}', 'MessageController@userPrivateChat')->name('user-chat');
		Route::get('/currentUserProfile/{id}', 'MessageController@currentUserProfile');
		Route::get('/markAsRead/{id}', 'MessageController@markAsRead');
		Route::get('/deleteUser/{id}', 'MessageController@deleteUser');
		Route::post('/reportUser', 'MessageController@reportUser');

		Route::get('/chatUsers', 'MessageController@chatUsers');

		Route::get('messages', 'MessageController@fetchMessages');
		// Route::get('groupmessages', 'MessageController@fetchGroupMessages');
		Route::post('messages', 'MessageController@sendMessage');
		Route::get('/private-messages/{user}', 'MessageController@privateMessages')->name('privateMessages');
		Route::post('/private-messages/{user}', 'MessageController@sendPrivateMessage')->name('privateMessages.store');


		Route::get('/friends', 'ChatController@friends')->name('friends');
		Route::get('/friend-delete/{id}', 'ChatController@deleteFriends')->name('friend.delete');
		// Route::get('/chat', 'ChatController@index')->name('chat.index');
		Route::get('/chat/{id}', 'ChatController@show')->name('chat.show');
		Route::post('/chat/getChat/{id}', 'ChatController@getChat');
		Route::post('/chat/getFriendProfileImage/{id}', 'ChatController@getFriendProfileImage');
		Route::post('/chat/sendChat', 'ChatController@sendChat');
		Route::post('/chat/searchFriends', 'ChatController@searchFriends');


		Route::post('/add/friend', 'UserNetworkController@addFriend')->name('add.friend');
		Route::post('/confirm/friend', 'UserNetworkController@confirmFriend')->name('confirm.friend');
		Route::post('/remove/friend', 'UserNetworkController@removeFriend')->name('remove.friend');
		Route::post('/report/network/user', 'UserNetworkController@reportNetworkUser')->name('report.network.user');
		Route::get('/{username}/network', 'UserNetworkController@myNetwork')->name('my-network');
		Route::post('/add/user/status', 'UserNetworkController@addUserStatus')->name('add.user.status');
		Route::post('/delete/post', 'UserNetworkController@deletePost')->name('delete.post');
		Route::post('/get/post', 'UserNetworkController@getPost')->name('get.post');
		Route::post('/update/post', 'UserNetworkController@updatePost')->name('update.post');
		Route::post('/like/post', 'UserNetworkController@likePost')->name('like.post');
		Route::post('/dis/like/post', 'UserNetworkController@disLikePost')->name('dis.like.post');
		Route::post('/post/comment', 'UserNetworkController@postComment')->name('post.comment');
		Route::post('/delete/post/comment', 'UserNetworkController@deletePostComment')->name('delete.post.comment');
		Route::post('/upload-feed-image', 'UserNetworkController@uploadFeedImage')->name('upload.feed.image');


		Route::post('/send-career-application', 'CareerController@sendApplication')->name('send.application');

		// Ravi Routes Start

		Route::get('/career/{job_title}', 'CareerController@applicationForm')->name('apply.form');

		Route::get('/enter/{title}', 'DiscoverController@applicationForm')->name('discover.form');

		Route::get('/develop/{title}', 'ExploreController@applicationForm')->name('explore.form');
		Route::post('/send-explore-application', 'ExploreController@sendApplication')->name('explore.application');

		Route::get('/forum','ForumController@index')->name('forum');
		Route::post('/forum-add', 'ForumController@addTopic')->name('add.topic');




		Route::post('/comment', 'ForumController@addComment')->name('add.comment');

		Route::post('/like', 'UserController@likeProfile')->name('like');
		Route::post('/unlike', 'UserController@unlikeProfile')->name('unlike');




		Route::post('/send-discover-application', 'DiscoverController@sendApplication')->name('discover.application');

		Route::get('contactinquires', 'UserController@contactinquires')->name('contactinquires');
		Route::get('delete-contactinquires/{id}', 'UserController@deleteContactInquires')->name('delete.contactinquire');
		Route::get('delete-notification/{id}', 'UserController@deleteNotification')->name('delete.notification');
		// Ravi Routes End

		Route::get('edit/user/profile', 'HomeController@userProfile')->name('profile');
		Route::get('user/photo/gallery', 'HomeController@imagegallery')->name('photo_gallery');
		Route::get('user/video/gallery', 'HomeController@videogallery')->name('video_gallery');
		Route::get('dashboard', 'HomeController@userDashboard')->name('dashboard');
		Route::post('upload-profile-picture', 'HomeController@saveProfilePictute')->name('upload-profile-picture');
		Route::post('update-profile', 'HomeController@updateProfile')->name('update-profile');
		Route::post('upload-photos', 'HomeController@uploadPhotos')->name('upload-photos');
		Route::post('delete-photo', 'HomeController@deletePhotos')->name('delete-photo');

		Route::post('upload-videos', 'HomeController@uploadVideos')->name('upload-videos');
		Route::post('delete-video', 'HomeController@deleteVideo')->name('delete-video');

		Route::post('upload-cv', 'HomeController@uploadCv')->name('upload-cv');
		Route::get('/notifications', 'HomeController@showNotification')->name('show.all.notification');
		Route::get('/read-all-notifications', 'HomeController@readNotification')->name('read.all.notification');
		Route::post('/markAsRead/notification', 'HomeController@markAsReadNotification')->name('markAsRead.notification');

		Route::get('/referfriend', 'ReferController@index')->name('referfriend');
		Route::post('/refer-message', 'ReferController@sendMessage')->name('refer.message');
		Route::get('reminder/{id}','ReferController@Reminder')->name('reminder');
		// Route::get('send-refer-reminder/{invite-id}', 'ReferController@sendReferReminder')->name('send.refer.reminder');

		Route::get('refer/{token}', 'ReferController@registerfriend')->name('refer.friend');

		Route::post('increase-personality', 'HomeController@increasePersonality')->name('increase.personality');

		Route::get('/changepassword', 'ChangePassword@index')->name('changepassword');
		Route::post('/changepasswords', 'ChangePassword@update')->name('changepassword.update');

		Route::post('add-card', 'HomeController@addCard')->name('add-card');
		Route::post('delete-card', 'HomeController@deleteCard')->name('delete-card');
		Route::post('get-card', 'HomeController@getCard')->name('get-card');
		Route::post('update-card', 'HomeController@updateCard')->name('update-card');
		Route::post('set-default-card', 'HomeController@setDefaultCard')->name('set-default-card');

		Route::post('collect/reward','HomeController@collectReward')->name('collect.reward');
		Route::get('get/membership/plan','HomeController@getMembershipPlan')->name('get.membership.plan');
		Route::get('change/membership/plan/{plan_id}','HomeController@changeMembershipPlan')->name('change.plan');

	});
});


Route::group(['middleware' => 'auth'], function () {

	Route::get('subscription', 'StripeController@plans')->name('plans');
	Route::get('subscription/{plan_id}', 'StripeController@getPlans')->name('get.plans');
	Route::get('payment/{plan_id}', 'StripeController@payment')->name('get.payment');
	Route::post('subscriptions', 'StripeController@checkcoupon')->name('get.checkcoupon');
	Route::post('order-post', 'StripeController@orderPost')->name('order-post');
	Route::get('payment-trail/{plan_id}', 'StripeController@trailPayment')->name('get.payment.trail');
	Route::post('trail-order-post', 'StripeController@trailOrderPost')->name('trail-order-post');
	Route::get('thankyou', 'StripeController@thankYou')->name('thankyou');
	Route::post('cancle-membership', 'StripeController@cancleMembership')->name('cancle-membership');
	Route::get('resume-membership', 'StripeController@resumeMembership')->name('resume-membership');
	Route ::post('delete/user/profile', 'HomeController@deleteUserProfile') -> name('delete.user.profile');
});

Route::get('register/{code}', 'Auth\RegisterController@userRegisterForm')->name('user-register-from');
Route::post('register', 'Auth\RegisterController@userRegister')->name('user-register');

include('admin-routes.php');
