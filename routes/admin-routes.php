<?php
Route ::group(['namespace' => 'Admin'], function () {

    /*For Login*/
    Route ::get('/admin/login', 'Auth\AdminLoginController@showLoginForm') -> name('admin.login');
    Route ::post('/admin/login', 'Auth\AdminLoginController@login') -> name('admin.login.submit');
    Route ::get('/admin/logout', 'Auth\AdminLoginController@logout') -> name('admin.logout');

    Route ::group(['middleware' => 'auth:admin', 'prefix' => 'admin/'], function () {

        Route ::get('/dashboard', 'AdminController@index') -> name('admin.home');
        Route ::get('data', 'AdminController@chart') -> name('admin.chart');
        Route ::get('piedata', 'AdminController@pieChart') -> name('admin.piedata');
        Route ::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');


        Route ::resource('users', 'Auth\User\UserController');
        Route ::post('user-delete', 'Auth\User\UserController@deleteUser') -> name('user.delete');
        Route ::get('user/deleted', 'Auth\User\UserController@getDeleted') -> name('user.deleted');
        Route ::get('user/restore/{id}', 'Auth\User\UserController@restore') -> name('user.restore');
        Route ::post('user/get', 'Auth\User\UserTableController') -> name('user.get');

        Route ::resource('careers', 'Career\CareerController');
        Route ::post('upload-career-icon', 'Career\CareerController@uploadCareerIcon') -> name('upload-career-icon');
        Route ::post('career/get', 'Career\CareerTableController') -> name('career.get');

        Route ::post('upload-admin-icon', 'Career\CareerController@uploadAdminIcon')-> name('upload-admin-icon');

        // Ravi Routes Start
        Route ::resource('careersrequest', 'CareersRequest\CareersRequestController');
        Route ::post('careersrequest/get', 'CareersRequest\CareersRequestTableController') -> name('careersrequest.get');
        Route ::get('careersrequestdetails/{id}', 'CareersRequest\CareersRequestTableController@careersRequestDetails') -> name('careersrequestdetails.get');

        Route ::resource('explore', 'Explore\ExploreController');
        Route ::post('upload-explore-icon', 'Explore\ExploreController@uploadExploreIcon') -> name('upload-explore-icon');
        Route ::post('explore/get', 'Explore\ExploreTableController') -> name('explore.get');

        Route ::resource('explorerequest', 'ExploreRequest\ExploreRequestController');
        Route ::post('explorerequest/get', 'ExploreRequest\ExploreRequestTableController') -> name('explorerequest.get');
        Route ::get('explorerequestdetails/{id}', 'ExploreRequest\ExploreRequestTableController@exploreRequestDetails') -> name('explorerequestdetails.get');

        Route ::resource('topic', 'Topic\TopicController');
        Route ::post('topic/get', 'Topic\TopicTableController') -> name('topic.get');

        Route ::resource('forumcategory', 'ForumCategory\ForumCategoryController');
        Route ::post('forumcategory/get', 'ForumCategory\ForumCategoryTableController') -> name('forumcategory.get');

        Route ::resource('contactinquire', 'ContactInquire\ContactInquireController');
        Route ::post('contactinquire/get', 'ContactInquire\ContactInquireTableController') -> name('contactinquire.get');

        Route ::resource('comment', 'Comment\CommentController');
        Route ::post('comment/get', 'Comment\CommentTableController') -> name('comment.get');
        Route ::get('comment/{id}/{topic_id}/{user_id}', 'Comment\CommentTableController@unapprove') -> name('comment.unapprove');
        Route ::get('comments/{id}/{topic_id}/{user_id}', 'Comment\CommentTableController@approve') -> name('comment.approve');

        Route ::resource('pages', 'Pages\PagesController');
        Route ::post('pages/get', 'Pages\PagesTableController') -> name('pages.get');

        Route ::resource('access-code', 'AccessCode\AccessCodeController');
        Route ::post('access/code/get', 'AccessCode\AccessCodeTableController') -> name('access.code.get');

        Route ::get('user-reports', 'Report\ReportController@userReports') -> name('user.reports');
        Route ::post('get-reports', 'Report\ReportTableController@getData') -> name('get.user');

        Route ::get('transaction-reports', 'Transaction\TransactionController@transactionReports') -> name('transaction.reports');
        Route ::get('transactiondata', 'Transaction\TransactionController@transactionReports') -> name('transaction.data');
        Route ::post('get-transactionreports', 'Transaction\TransactionTableController@getData') -> name('get.transaction');

        Route ::resource('discover', 'Discover\DiscoverController');
        Route ::post('get/competition/user', 'Discover\DiscoverController@getCompetitionUser') -> name('get.competition.user');
        Route ::post('declare/result', 'Discover\DiscoverController@declareResult') -> name('declare.result');
        Route ::post('view/winner/user', 'Discover\DiscoverController@viewWinnerUser') -> name('view.winner.user');

        Route ::post('upload-discover-icon', 'Discover\DiscoverController@uploadDiscoverIcon') -> name('upload-discover-icon');
        Route ::post('discover/get', 'Discover\DiscoverTableController') -> name('discover.get');

        Route ::resource('discoverrequest', 'DiscoverRequest\DiscoverRequestController');
        Route ::post('discoverrequest/get', 'DiscoverRequest\DiscoverRequestTableController') -> name('discoverrequest.get');
        Route ::get('discoverrequestdetails/{id}', 'DiscoverRequest\DiscoverRequestTableController@discoverRequestDetails') -> name('discoverrequestdetails.get');

        Route ::resource('discoverlikes', 'DiscoverLikes\DiscoverLikesController');
        Route ::post('discoverlikes/get', 'DiscoverLikes\DiscoverLikesTableController') -> name('discoverlikes.get');
        Route ::post('discoverlikesprofile', 'DiscoverLikes\DiscoverLikesTableController@likeprofiles') -> name('discoverlikes.profile');
        Route ::get('general/{id}', 'General\GeneralController@edit') -> name('general.edit');
        Route ::post('generals/{id}', 'General\GeneralController@update') -> name('general.update');

        Route ::get('careerlist/{id}', 'CareerSEO\CareerSeoController@edit') -> name('careerlist.edit');
        Route ::post('careerlists/{id}', 'CareerSEO\CareerSeoController@update') -> name('careerlist.update');

        Route ::get('careerpage/{id}', 'CareerPageSEO\CareerPageSeoController@edit') -> name('careerpage.edit');
        Route ::post('careerpages/{id}', 'CareerPageSEO\CareerPageSeoController@update') -> name('careerpage.update');

        Route ::get('profilelist/{id}', 'ProfileListSEO\ProfileListSeoController@edit') -> name('profilelist.edit');
        Route ::post('profilelist/{id}', 'ProfileListSEO\ProfileListSeoController@update') -> name('profilelist.update');

        Route ::get('profilepage/{id}', 'ProfilePageSEO\ProfilePageSeoController@edit') -> name('profilepage.edit');
        Route ::post('profilepages/{id}', 'ProfilePageSEO\ProfilePageSeoController@update') -> name('profilepage.update');

        Route ::get('discoverlist/{id}', 'DiscoverListSEO\DiscoverListSeoController@edit') -> name('discoverlist.edit');
        Route ::post('discoverlist/{id}', 'DiscoverListSEO\DiscoverListSeoController@update') -> name('discoverlist.update');

        Route ::get('discoverpage/{id}', 'DiscoverPageSEO\DiscoverPageSeoController@edit') -> name('discoverpage.edit');
        Route ::post('discoverpages/{id}', 'DiscoverPageSEO\DiscoverPageSeoController@update') -> name('discoverpage.update');

        Route ::get('explorelist/{id}', 'ExploreListSEO\ExploreListSeoController@edit') -> name('explorelist.edit');
        Route ::post('explorelist/{id}', 'ExploreListSEO\ExploreListSeoController@update') -> name('explorelist.update');

        Route ::get('explorepage/{id}', 'ExplorepageSEO\ExplorepageSeoController@edit') -> name('explorepage.edit');
        Route ::post('explorepages/{id}', 'ExplorepageSEO\ExplorepageSeoController@update') -> name('explorepage.update');

        Route ::resource('refercode', 'Refercode\AdminReferCode');
        Route ::post('refercode/get', 'Refercode\AdminReferTableCode') -> name('refercode.get');

        Route ::resource('referlist', 'ReferList\ReferlistresourceController');
        Route ::post('referlist/get', 'ReferList\ReferlistController') -> name('referlist.get');
        //Route::resource('transcationdata','Transcationdata\DiscoverRequestController');
        // Ravi Routes End

        Route ::resource('sliders', 'Sliders\SlidersController');
        Route ::post('sliders/get', 'Sliders\SlidersTableController') -> name('sliders.get');

        Route ::resource('plans', 'Plans\PlansController');
        Route ::post('plans/get', 'Plans\PlansTableController') -> name('plans.get');
        Route ::get('import-plans', 'Plans\PlansController@importStripePlans') -> name('plans.import');

        Route ::get('chatreport', 'ChatReport\ChatReportController@index') -> name('chatreport.index');
        Route ::post('chatreportdata', 'ChatReport\ChatReportController@chatReportData') -> name('chatreport.get');

        Route ::resource('user-invitation', 'UserInvitationLink\UserInvitationLinkController');
        Route ::post('user-invitation/get', 'UserInvitationLink\UserInvitationLinkTableController') -> name('user.invitation.get');

        //Route::resource('settings','Settings\SettingsController');
        Route ::get('settings/{id}', 'Settings\SettingsController@edit') -> name('settings.edit');
        Route ::post('settings/{id}', 'Settings\SettingsController@update') -> name('settings.update');


        // ========================== County ==============================
        Route ::resource('country', 'Country\CountryController');
        /*datatables*/
        Route ::post('get-country', 'Country\CountryDatatablesController@getData') -> name('get.country');

        // ========================== Increase User Points ==============================
        Route ::get('increase/user/likes/form', 'IncreaseUserPointsPersonalty\IncreaseUserPointsPersonaltyController@increaseUserLikesForm')->name('increase.user.likes.form');
        Route ::post('increase/user/likes', 'IncreaseUserPointsPersonalty\IncreaseUserPointsPersonaltyController@increaseUserLikes')->name('increase.user.likes');
        Route ::get('increase/user/personality/form', 'IncreaseUserPointsPersonalty\IncreaseUserPointsPersonaltyController@increaseUserPersonalityForm')->name('increase.user.personality.form');
        Route ::post('get/user/personality', 'IncreaseUserPointsPersonalty\IncreaseUserPointsPersonaltyController@getUserPersonality')->name('get.user.personality');
        Route ::post('increase/user/personality', 'IncreaseUserPointsPersonalty\IncreaseUserPointsPersonaltyController@increaseUserPersonality')->name('increase.user.personality');
        Route ::post('get/user/likes', 'IncreaseUserPointsPersonalty\IncreaseUserPointsPersonaltyController@getUserLikes')->name('get.user.likes');
        Route ::get('get/contact/help', 'Contact\ContactHelpController@index')->name('contact.index');
        Route ::post('get/contact/data', 'Contact\ContactHelpController@getContactData')->name('get.contact');
    });

});
?>
