<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

// Route::get('/languages', 'LanguageController@index');

// Auth::routes();

// Route::group(['prefix' => '/', 'middleware' => 'guest'], function () {
//     Route::get('/', 'Auth\LoginController@getLogin');

//     Route::get('/register', 'Auth\RegisterController@getRegister');

//     Route::post('/register', [
//         'as' => 'register-user',
//         'uses' => 'Auth\RegisterController@register',
//     ]);

//     Route::get('/login', 'Auth\LoginController@getLogin');

//     Route::post('/login', [
//         'as' => 'login-user',
//         'uses' => 'Auth\LoginController@login',
//     ]);

//     Route::get('/redirect/{provider}', 'User\SocialAuthController@redirect');

//     Route::get('/callback/{provider}', 'User\SocialAuthController@callback');
// });

// Route::get('/', 'SurveyController@index');

// Route::get('/logout', 'Auth\LoginController@logout');


// Route::group(['prefix' => '/supper-admin', 'middleware' => 'supperadmin'], function () {
//     Route::resource('/request', 'Admin\RequestController', [
//         'only' => ['index'],
//     ]);

//     Route::post('/request/update/{id}', 'Admin\RequestController@update');

//     Route::post('/request/delete', 'Admin\RequestController@destroy');
// });

// Route::group(['prefix' => '/admin', 'middleware' => 'admin'], function () {
//     Route::resource('/dashboard', 'Admin\DashboardController', [
//         'only' => ['index'],
//     ]);

//     Route::resource('/survey', 'Admin\SurveyController', [
//         'only' => ['index', 'update'],
//     ]);

//     Route::post('/destroy-survey', 'Admin\SurveyController@destroySurvey');

//     Route::resource('/user', 'Admin\UserController', [
//         'only' => ['index', 'update', 'show'],
//     ]);

//     Route::post('/change-status-user/{status}', 'Admin\UserController@changeStatus');

//     Route::get('/search', 'Admin\UserController@search');

//     Route::resource('/request', 'Admin\RequestController', ['only' => 'store']);

//     Route::post('/request/cancel', 'Admin\RequestController@cancel');

//     Route::resource('/feedback', 'FeedbackController', [
//         'only' => [
//             'index',
//             'show',
//             'destroy',
//         ],
//     ]);

//     Route::post('/post-update-feedback', 'FeedbackController@update');
// });

// Route::get('/', 'SurveyController@index');

// Route::post('/invite/send/{id}/{type}', 'SurveyController@inviteUser');

// Route::post('/delete-survey', 'SurveyController@delete');

// Route::post('/close-survey/{id}', 'SurveyController@close');

// Route::post('/open-survey/{id}', 'SurveyController@open');

// Route::post('/duplicate-survey/{id}', 'SurveyController@duplicate');

// Route::get('/export/{id}/{type}', 'User\ExportController@export');

// Route::group(['prefix' => '/home'], function () {
//     Route::get('/', 'SurveyController@index');

//     Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
//         Route::post('/mark-survey', 'User\LikeController@markLike');

//         Route::get('/list-invited', 'SurveyController@getInviteSurvey');

//         Route::get('/individual', 'SurveyController@listSurveyUser');

//         Route::get('/user/detail', 'User\UserController@show');

//         Route::put('/user/update', 'User\UserController@update');

//         Route::get('/private/{token}', 'AnswerController@answerPrivate');

//         Route::resource('/save', 'User\SaveTempController', [
//             'only' => ['store', 'index'],
//         ]);
//         Route::get('/show-temp', 'User\SaveTempController@show');
//     });
// });

//         Route::get('/show-temp', 'User\SaveTempController@show');
//     });
// });

// Route::get('/detail/{token}', 'AnswerController@show');

// Route::post('/add-temp/{type}', 'TempController@addTemp');

// Route::post('/create', [
//     'as' => 'create',
//     'uses' => 'SurveyController@create',
// ]);

// Route::post('/update-setting/{id}/{token}', 'SettingController@update');

// Route::post('/survey/result/{token}', 'ResultController@result');

// Route::get('/complete/{token}', 'SurveyController@complete');

// Route::post('/search', 'SurveyController@search');

// Route::get('/public/{token}', 'AnswerController@answerPublic');

// Route::get('/show/{token}', 'SurveyController@showDetail');

// Route::put('/update-survey/{id}', 'SurveyController@updateSurvey');

// Route::get('/show-user-answer-detail/{surveyId}/{userId?}/{email?}/{name?}/{clientIp?}', 'AnswerController@showMultiHistory');

// Route::post('/update/{id}/{token}', 'SurveyController@updateSurveyContent');

// Route::get('/feedback', 'FeedbackController@getFeedback');

// Route::post('/post-feedback', 'FeedbackController@create');

// Route::get('/answer/completed/{survey}/{name?}', 'ResultController@show');

// Route::post('update-link-survey', 'AnswerController@updateLinkSurvey');

// Route::post('verify-link-survey', 'AnswerController@verifyLinkSurvey');

// Route::post('survey/get-deadline', 'AnswerController@getDeadline');

// Route::post('ajax/get-mail-suggestion', 'SurveyController@getMailSuggestion');

Route::group(['namespace' => 'Ajax', 'prefix' => 'ajax'], function () {
    Route::post('fetch-element/section', 'ElementFetchingController@fetchSection')->name('ajax-fetch-section');

    Route::post('fetch-element/short-answer', 'ElementFetchingController@fetchShortAnswer')
        ->name('ajax-fetch-short-answer');

    Route::post('fetch-element/long-answer', 'ElementFetchingController@fetchLongAnswer')
        ->name('ajax-fetch-long-answer');

    Route::post('fetch-element/multiple-choice', 'ElementFetchingController@fetchMultipleChoice')
        ->name('ajax-fetch-multiple-choice');

    Route::post('fetch-element/checkboxes', 'ElementFetchingController@fetchCheckboxes')
        ->name('ajax-fetch-checkboxes');

    Route::post('fetch-element/date', 'ElementFetchingController@fetchDate')
        ->name('ajax-fetch-date');

    Route::post('fetch-element/time', 'ElementFetchingController@fetchTime')
        ->name('ajax-fetch-time');

    Route::post('fetch-element/title-description', 'ElementFetchingController@fetchTitleDescription')
        ->name('ajax-fetch-title-description');

    Route::post('upload-image', 'UploadImageController@insertImage')
        ->name('ajax-upload-image');

    Route::post('fetch-element/image', 'ElementFetchingController@fetchImage')
        ->name('ajax-fetch-image-section');

    Route::post('fetch-element/video', 'ElementFetchingController@fetchVideo')
        ->name('ajax-fetch-video');

    Route::post('fetch-element/image-question', 'ElementFetchingController@fetchImageQuestion')
        ->name('ajax-fetch-image-question');

    Route::post('fetch-element/redirect-question', 'ElementFetchingController@fetchRedirectQuestion')
        ->name('ajax-fetch-redirect-question');

    Route::post('fetch-element/linear-scale-question', 'ElementFetchingController@fetchLinearScaleQuestion')
        ->name('ajax-fetch-linear-scale-question');

    Route::post('fetch-element/grid-question', 'ElementFetchingController@fetchGridQuestion')
        ->name('ajax-fetch-grid-question');

    Route::post('fetch-element/dropdown-question', 'ElementFetchingController@fetchDropdownQuestion')
        ->name('ajax-fetch-dropdown-question');

    Route::post('fetch-element/redirect-section', 'ElementFetchingController@fetchRedirectSection')
        ->name('ajax-fetch-redirect-section');

    Route::post('fetch-element/image-answer', 'ElementFetchingController@fetchImageAnswer')
        ->name('ajax-fetch-image-answer');

    Route::post('fetch-element/background-survey', 'ElementFetchingController@fecthBackgroundImageSurvey')
        ->name('ajax-fetch-background-survey');

    Route::post('remove-image', 'UploadImageController@removeImage')
        ->name('ajax-remove-image');
    Route::post('suggest-email', 'SuggestEmailController@suggestEmail')
        ->name('ajax-suggest-email');
    Route::get('list-survey/{flag}', 'SurveyController@getListSurvey')
        ->name('ajax-list-survey');
    Route::get('management-survey', 'SurveyController@getSurveyManage')
        ->name('ajax-management-survey');
    Route::get('get-overview/{tokenManage}', 'ManagementSurvey@getOverviewSurvey')
        ->name('ajax-get-overview');
    Route::get('get-setting/{token}', 'ManagementSurvey@settingSurvey')
        ->name('ajax-setting-survey');
    Route::get('surveys-delete/{tokenManage}', 'ManagementSurvey@deleteSurvey')
        ->name('ajax-survey-delete');
    Route::get('surveys-close/{tokenManage}', 'ManagementSurvey@closeSurvey')
        ->name('ajax-survey-close');
    Route::get('surveys-open/{tokenManage}', 'ManagementSurvey@openSurvey')
        ->name('ajax-survey-open');
    Route::get('surveys-clone/{tokenManage}', 'ManagementSurvey@cloneSurvey')
        ->name('ajax-survey-clone');
    Route::post('change-token', 'ManagementSurvey@updateTokenSurvey')
        ->name('change-token');
    Route::post('change-token-manage', 'ManagementSurvey@updateTokenManageSurvey')
        ->name('change-token-manage');
    Route::post('status-invite', 'SurveyController@getStatusInvite')
        ->name('survey.status-invite');
});

Route::get('/languages', 'LanguageController@index')->name('set-language');

Route::get('/', 'SurveyController@index')->name('home');

Route::get('/home', 'SurveyController@index');

//login social

Route::get('/redirect/{provider}', 'User\SocialAuthController@redirect')->name('socialRedirect');

Route::get('/callback/{provider}', 'User\SocialAuthController@callback')->name('socialCallback');

Route::group(['namespace' => 'Survey', 'middleware' => 'profile'], function () {
    Route::resource('profile', 'ProfileController', [
        'as' => 'survey',
    ]);
    Route::get('change-password', [
        'uses' => 'ProfileController@showChangePassword',
        'as' => 'survey.profile.changepassword',
    ]);
    Route::post('change-password', [
        'uses' => 'ProfileController@changePassword',
        'as' => 'survey.profile.changepassword',
    ]);
    Route::post('change-avatar', [
        'uses' => 'ProfileController@changeAvatar',
        'as' => 'survey.profile.changeavatar',
    ]);
    Route::get('delete-avatar', [
        'uses' => 'ProfileController@deleteAvatar',
        'as' => 'survey.profile.deleteavatar',
    ]);
    Route::get('list-survey', [
        'as' => 'survey.survey.show-surveys',
        'uses' => 'SurveyManagementController@showSurveys',
    ]);
    Route::get('list-survey-data', [
        'as' => 'survey.survey.get-surveys',
        'uses' => 'SurveyManagementController@getSurveys',
    ]);
    Route::post('update-background', [
        'as' => 'survey.survey.update-background',
        'uses' => 'ProfileController@setBackground',
    ]);
    Route::get('surveys/delete/{token}', [
        'uses' => 'SurveyManagementController@deleteSurvey',
        'as' => 'survey.delete',
    ]);
    Route::get('surveys/close/{token}', [
        'uses' => 'SurveyManagementController@closeSurvey',
        'as' => 'survey.close',
    ]);
    Route::get('surveys/open/{token}', [
        'uses' => 'SurveyManagementController@openSurvey',
        'as' => 'survey.open',
    ]);
    Route::get('management-survey/{tokenManage}', 'SurveyManagementController@managementSurvey')->name('survey.management');
});

Route::group(['namespace' => 'Auth'], function () {
    Route::post('login', 'LoginController@login')->name('login');

    Route::post('register', 'RegisterController@register')->name('register');

    Route::get('confirmation-register/{code}', 'RegisterController@confirmCode')->name('confirmation-register');

    Route::get('logout', 'LoginController@logout')->name('logout');

    Route::post('reset-password', 'ForgotPasswordController@sendMailResetPassword')->name('send-mail-reset-password');

    Route::get('password/reset/{token}', 'ResetPasswordController@showFormReset')->name('show-form-reset');

    Route::post('password/reset', 'ResetPasswordController@resetPasswordUser')->name('reset-password');
});

Route::group(['middleware' => 'profile'], function () {
    Route::resource('surveys', 'SurveyController');

    Route::get('/surveys/complete/{token}', [
        'uses' => 'SurveyController@complete',
        'as' => 'survey.create.complete',
    ]);

    Route::post('/surveys/save-draft', [
        'uses' => 'SurveyController@saveDraft',
        'as' => 'survey.save-draft',
    ]);

    Route::put('/surveys/update-setting/{token}', [
        'uses' => 'SurveyController@updateSetting',
        'as' => 'survey.update-setting',
    ]);

    Route::put('/surveys/update-draft/{token}', [
        'uses' => 'SurveyController@updateDraft',
        'as' => 'survey.update-draft',
    ]);

    Route::group(['namespace' => 'Survey'], function () {
        Route::get('surveys/result/{tokenManage}', [
            'as' => 'survey.result.index',
            'uses' => 'ResultController@result',
        ]);

        Route::get('surveys/result/detail/{tokenManage}', [
            'as' => 'survey.result.detail-result',
            'uses' => 'ResultController@detail',
        ]);

        Route::post('surveys/get-redirect-result', [
            'as' => 'survey.redirect.result',
            'uses' => 'ResultController@getRedirectResult',
        ]);

        Route::post('survey/get-personal-result', [
            'as' => 'survey.personal.result',
            'uses' => 'ResultController@getPersonalResult'
        ]);

        Route::get('surveys-preview', [
            'uses' => 'PreviewSurveyController@preview',
            'as' => 'survey.create.preview',
        ]);

        Route::get('next-preview/{id}', [
            'uses' => 'PreviewSurveyController@nextSection',
            'as' => 'survey.create.preview.next'
        ]);

        Route::get('previous-preview/{id}', [
            'uses' => 'PreviewSurveyController@previousSection',
            'as' => 'survey.create.preview.previous'
        ]);

        Route::post('surveys/export', 'ExportController@export')->name('export-result');

        Route::post('surveys/syns-to-sheets', 'ExportController@synsToSheets')->name('syns-to-sheets');

        Route::post('surveys/preview/get-json', [
            'uses' => 'PreviewSurveyController@getJson',
            'as' => 'survey.create.get-json',
        ]);

        Route::get('remove-session', [
            'uses' => 'PreviewSurveyController@removeSession',
            'as' => 'survey.remove.session',
        ]);
    });
});

Route::post('store-result', [
    'uses' => 'SurveyController@storeResult',
    'as' => 'survey.create.storeresult',
]);

Route::get('edit-answer/{token}/{tokenResult}', [
    'uses' => 'SurveyController@editAnswer',
    'as' => 'survey.create.edit-answer',
])->middleware('editanswer');

Route::post('store-edit-answer', [
    'uses' => 'SurveyController@storeNewResults',
    'as' => 'survey.create.store-edit-answer',
]);

Route::get('surveys-{token}', [
    'uses' => 'SurveyController@show',
    'as' => 'survey.create.do-survey',
])->middleware('doingsurvey');

Route::get('answer-complete/{token}', [
    'uses' => 'SurveyController@showCompleteAnswer',
    'as' => 'show-complete-answer',
]);

Route::group(['error'], function () {
    Route::get('/error/404', 'Survey\ErrorController@error404')->name('404');

    Route::get('/error/403', 'Survey\ErrorController@error403')->name('403');
});

Route::resource('/feedbacks', 'FeedbackController', [
    'only' => [
        'store',
    ]
]);

Route::group(['middleware' => 'admin'], function () {
    Route::resource('/feedbacks', 'FeedbackController', [
        'only' => [
            'index',
            'destroy',
        ]
    ]);

    Route::get('/list-feedback', 'FeedbackController@getListFeedback')
        ->name('ajax-list-feedback');

    Route::resource('/management-user', 'UserManagementController');

    Route::get('/management-survey', 'ListSurveyController@index')
        ->name('list-survey-management');

    Route::get('/list-user', 'UserManagementController@getListUser')
        ->name('ajax-list-user');

    Route::put('/change-status', 'UserManagementController@changeStatus')
        ->name('change-status');
});

Route::get('see-result-by-date', 'Survey\ResultController@getResultByDate')->name('see-result-by-date');
Route::get('result-personal-by-date', 'Survey\ResultController@getResultPersonalByDate')->name('result-personal-by-date');
