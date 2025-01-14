<?php

use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('register', 'API\AuthController@register');
Route::post('login', 'API\AuthController@login');
Route::post('reset_password', 'API\AuthController@resetPassword');
Route::get('plans', 'API\AuthController@plans');
Route::get('time_zones', 'HomeController@getTimeZones');
Route::get('/mail/receive/{type?}', 'HomeController@receiveMail')->name('receiveEmail');
Route::get('version', 'API\AuthController@getAppVersion');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('auth/check', 'Controller@checkAuth');
    Route::get('logout', 'API\AuthController@logout');
    Route::get('roles', 'API\RoleController@roles');
    Route::get('roles/full', 'API\RoleController@getRolesWithPermissions');
    Route::put('roles/full', 'API\RoleController@mergeRolesWithPermissions');
    Route::get('permissions', 'API\RoleController@permissions');


    Route::group(['middleware' => 'setLanguage'], function () {
        //user management
        Route::get('user', 'API\UserController@find');
        Route::get('user/find/{id}', 'API\UserController@find');
        Route::post('user', 'API\UserController@update');
        Route::post('user/invite', 'API\UserController@sendInvite');
        Route::post('user/is_active', 'API\UserController@changeIsActive');
        Route::get('user/roles/id', 'API\UserController@authorizedRoleIds');
        Route::patch('user/roles', 'API\UserController@updateRoles');
        Route::get('user/permissions/id', 'API\UserController@authorizedPermissionIds');
        Route::get('user/settings', 'API\UserController@getSettings');
        Route::post('user/settings', 'API\UserController@updateSettings');
        Route::post('user/notifications', 'API\UserController@setNotifications');
        Route::post('user/avatar', 'API\UserController@updateAvatar');
        Route::delete('user/delete/{id}', 'API\UserController@delete');
        Route::post('user/restore', 'API\UserController@restoreDeleted');

        //company management
        Route::get('company/{id?}', 'API\CompanyController@find');
        Route::post('company/{id}', 'API\CompanyController@update');
        Route::post('company/product', 'API\CompanyController@attachProduct');
        Route::get('company/{id?}/product_categories/tree', 'API\CompanyController@getProductCategoriesTree');
        Route::get('company/{id?}/product_categories/flat', 'API\CompanyController@getProductCategoriesFlat');
        Route::post('company/{id}/product_category', 'API\CompanyController@attachProductCategory');
        Route::delete('product_category/{id}', 'API\CompanyController@detachProductCategory');
        Route::post('main_company/settings', 'API\CompanyController@updateSettings');
        Route::post('company/{id}/settings', 'API\CompanyController@updateSettings');
        Route::post('company/{id}/logo', 'API\CompanyController@updateLogo');
        Route::get('main_company/name', 'API\CompanyController@mainCompanyName');
        Route::get('main_company/logo', 'API\CompanyController@mainCompanyLogo');
        Route::post('main_company/logo', 'API\CompanyController@updateLogo');
        Route::get('main_company/settings', 'API\CompanyController@getSettings');
        Route::get('main_company/product_categories/tree', 'API\CompanyController@getProductCategoriesTree');
        Route::get('main_company/product_categories/flat', 'API\CompanyController@getProductCategoriesFlat');

        //employee management
        Route::get('employee', 'API\CompanyController@getIndividuals');
        Route::post('company/{id}/employee', 'API\CompanyController@invite');
        Route::delete('company/employee/{id}', 'API\CompanyController@removeEmployee');

        //phone management
        Route::post('phone', 'API\PhoneController@add');
        Route::patch('phone/{id}', 'API\PhoneController@edit');
        Route::delete('phone/{id}', 'API\PhoneController@delete');
        Route::get('phone_types', 'API\PhoneController@getTypes');
        Route::post('phone_type', 'API\PhoneController@addType');
        Route::patch('phone_type/{id}', 'API\PhoneController@updateType');
        Route::delete('phone_type/{id}', 'API\PhoneController@deleteType');

        //social management
        Route::get('social_types', 'API\SocialController@getTypes');
        Route::post('social_type', 'API\SocialController@addType');
        Route::patch('social_type/{id}', 'API\SocialController@updateType');
        Route::delete('social_type/{id}', 'API\SocialController@deleteType');
        Route::post('social', 'API\SocialController@add');
        Route::patch('social/{id}', 'API\SocialController@edit');
        Route::delete('social/{id}', 'API\SocialController@delete');

        //address management
        Route::get('address_types', 'API\AddressController@getTypes');
        Route::post('address_type', 'API\AddressController@addType');
        Route::patch('address_type/{id}', 'API\AddressController@updateType');
        Route::delete('address_type/{id}', 'API\AddressController@deleteType');
        Route::post('address', 'API\AddressController@add');
        Route::patch('address/{id}', 'API\AddressController@edit');
        Route::delete('address/{id}', 'API\AddressController@delete');

        //email management
        Route::get('email_types', 'API\EmailController@getTypes');
        Route::post('email_type', 'API\EmailController@addType');
        Route::patch('email_type/{id}', 'API\EmailController@updateType');
        Route::delete('email_type/{id}', 'API\EmailController@deleteType');
        Route::post('email', 'API\EmailController@add');
        Route::patch('email/{id}', 'API\EmailController@edit');
        Route::delete('email/{id}', 'API\EmailController@delete');

        //client management
        Route::get('client', 'API\ClientController@clients');
        Route::get('supplier', 'API\ClientController@suppliers');
        Route::post('client', 'API\ClientController@create');
        Route::get('client/{id}', 'API\ClientController@find');
        Route::patch('client/{id}', 'API\ClientController@update');
        Route::delete('client/{id}', 'API\ClientController@delete');
        Route::post('client/employee', 'API\ClientController@attach');
        Route::delete('client/employee/{id}', 'API\ClientController@detach');
        Route::get('all', 'API\ClientController@all');
        Route::post('client/is_active', 'API\ClientController@changeIsActive');
        Route::get('recipients', 'API\ClientController@recipientsTree');
        Route::post('client/{id}/logo', 'API\ClientController@updateLogo');

        //team management
        Route::get('team', 'API\TeamController@get');
        Route::post('team', 'API\TeamController@create');
        Route::get('team/{id}', 'API\TeamController@find');
        Route::patch('team/{id}', 'API\TeamController@update');
        Route::delete('team/{id}', 'API\TeamController@delete');
        Route::post('team/employee', 'API\TeamController@attach');
        Route::delete('team/employee/{id}', 'API\TeamController@detach');

        //product management
        Route::get('product', 'API\ProductController@get');
        Route::post('product', 'API\ProductController@create');
        Route::get('product/{id}', 'API\ProductController@find');
        Route::patch('product/{id}', 'API\ProductController@update');
        Route::delete('product/{id}', 'API\ProductController@delete');
        Route::post('product/employee', 'API\ProductController@attachEmployee');
        Route::delete('product/employee/{id}', 'API\ProductController@detachEmployee');
        Route::post('product/client', 'API\ProductController@attachClient');
        Route::delete('product/client/{id}', 'API\ProductController@detachClient');

        //ticket management
        Route::get('ticket', 'API\TicketController@get');
        Route::post('ticket', 'API\TicketController@create');
        Route::get('ticket/{id}', 'API\TicketController@find');
        Route::patch('ticket/{id}', 'API\TicketController@update');
        Route::delete('ticket/{id}', 'API\TicketController@delete');
        Route::post('ticket/{id}/team', 'API\TicketController@attachTeam');
        Route::post('ticket/{id}/employee', 'API\TicketController@attachEmployee');
        Route::post('ticket/{id}/contact', 'API\TicketController@attachContact');
        Route::post('ticket/{id}/answer', 'API\TicketController@addAnswer');
        Route::post('ticket/{id}/notice', 'API\TicketController@addNotice');
        Route::get('ticket_priorities', 'API\TicketController@priorities');
        Route::get('ticket_types', 'API\TicketController@types');
        Route::get('ticket_categories', 'API\TicketController@categories');
        Route::post('merge/ticket', 'API\TicketController@addMerge');
        Route::delete('merge/ticket/{id}', 'API\TicketController@removeMerge');
        Route::post('link/ticket', 'API\TicketController@addLink');
        Route::post('spam/ticket', 'API\TicketController@markAsSpam');

        //custom ticket filter
        Route::get('ticket_filters', 'API\TicketController@getFilters');
        Route::get('ticket_filter_parameters', 'API\TicketController@getFilterParameters');
        Route::post('ticket_query', 'API\TicketController@addFilter');

        //notifications management
        Route::get('notification_types', 'API\NotificationController@getTypes');
        Route::post('notification_type', 'API\NotificationController@addType');
        Route::patch('notification_type/{id}', 'API\NotificationController@updateType');
        Route::delete('notification_type/{id}', 'API\NotificationController@deleteType');
        Route::get('notifications', 'API\NotificationController@get');
        Route::get('notification/{id}', 'API\NotificationController@find');
        Route::post('notification', 'API\NotificationController@add');
        Route::patch('notification/{id}', 'API\NotificationController@edit');
        Route::delete('notification/{id}', 'API\NotificationController@delete');
        Route::post('notification/send', 'API\NotificationController@send');
        Route::get('notifications/history', 'API\NotificationController@history');
        Route::get('notification/history/{id}', 'API\NotificationController@historyDetails');

        //email signatures management
        Route::get('email_signatures', 'API\EmailSignatureController@get');
        Route::post('email_signature', 'API\EmailSignatureController@add');
        Route::patch('email_signature/{id}', 'API\EmailSignatureController@update');
        Route::delete('email_signature/{id}', 'API\EmailSignatureController@delete');

        //Tracking projects
        Route::prefix('tracking')
            ->namespace('API\Tracking')->group(function () {
                //Tracking projects
                Route::get('/projects', 'ProjectController@get');
                Route::get('/projects/{id}', 'ProjectController@find');
                Route::post('/projects', 'ProjectController@create');
                Route::patch('/projects/{id}', 'ProjectController@update');
                Route::delete('/projects/{id}', 'ProjectController@delete');

                //Additional tracking routes
                Route::get('/clients', 'BaseController@getClientList');
                Route::get('/products', 'BaseController@getProductList');
                Route::get('/coworkers', 'BaseController@getCoworkers');
                Route::get('/tickets', 'BaseController@getTickets');

                //Tracker
                Route::get('/tracker', 'TrackingController@get');
                Route::post('/tracker', 'TrackingController@create');
                Route::patch('/tracker/{tracking}', 'TrackingController@update');
                Route::post('/tracker/{tracking}/duplicate', 'TrackingController@duplicate');
                Route::delete('/tracker/{tracking}', 'TrackingController@delete');

                // Reports
                Route::post('/reports', 'ReportController@generate');
            });

        // Tags
        Route::get('/tags', 'API\TagController@get');
        Route::post('/tags', 'API\TagController@create');
        Route::patch('/tags/{tag}', 'API\TagController@update');
        Route::delete('/tags/{tag}', 'API\TagController@delete');

        // Services
        Route::get('/services', 'API\ServiceController@get');
        Route::post('/services', 'API\ServiceController@create');
        Route::patch('/services/{service}', 'API\ServiceController@update');
        Route::delete('/services/{service}', 'API\ServiceController@delete');
    });

    //language management
    Route::get('lang', 'API\LanguageController@getLanguages');
    Route::get('lang/map/{id?}', 'API\LanguageController@find');
    Route::get('lang/all', 'API\LanguageController@getAllLanguages');
    Route::get('lang/company/{company_id?}', 'API\LanguageController@getCompanyLanguages');
    Route::post('lang/company', 'API\LanguageController@addCompanyLanguage');
    Route::delete('lang/{id}/company/{company_id?}', 'API\LanguageController@deleteCompanyLanguage');

    // country management
    Route::get('countries', 'API\CountryController@getCountries');
    Route::get('countries/all', 'API\CountryController@getAllCountries');
    Route::get('countries/company/{company_id?}', 'API\CountryController@getCompanyCountries');
    Route::post('country/company', 'API\CountryController@addCompanyCountry');
    Route::delete('country/{id}/company/{company_id?}', 'API\CountryController@deleteCompanyCountry');

    //custom license
    Route::get('custom_license', 'API\CustomLicenseController@index');
    Route::get('custom_license/{id}', 'API\CustomLicenseController@find');
    Route::put('custom_license/{id}', 'API\CustomLicenseController@update');
    Route::put('custom_license/{id}/limits', 'API\CustomLicenseController@updateLimits');
    Route::get('custom_license/{id}/history', 'API\CustomLicenseController@history');
    Route::get('custom_license/{id}/users', 'API\CustomLicenseController@users');
    Route::get('custom_license_unassigned', 'API\CustomLicenseController@unassignedIxarmaUsersList');
    Route::post('custom_license_unassigned/assign', 'API\CustomLicenseController@assignToIxarmaCompany');
    Route::get('custom_license/{id}/user/{remoteUserId}/{idLicensed}', 'API\CustomLicenseController@manageUser');
    Route::put('custom_license_user/{remoteUserId}/trial', 'API\CustomLicenseController@setUserTrial');
});

