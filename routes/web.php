<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Welcomer;
use App\Http\Controllers\Backend\BackendController;
use App\Http\Controllers\Finance\QuickBooksController;
use App\Http\Controllers\References\BiologyController;
use App\Http\Controllers\References\DictionaryController;
use App\Http\Controllers\References\PedagogyController;
use App\Http\Controllers\References\ResearchController;
use App\Http\Controllers\Store\CheckoutController;
use App\Http\Controllers\Store\SubscriptionController;
use App\Http\Controllers\Store\StripeWebhookController;
use App\Http\Controllers\Store\StorefrontController;
use App\Http\Controllers\User\AuthLogic;
use App\Http\Controllers\User\MasterController;
use App\Http\Controllers\User\OrgController;
use App\Http\Controllers\User\UserProfileController;


// Main Welcome Index Pages
Route::get('/',                         [Welcomer::class, 'educators']);
Route::get('/educators',                [Welcomer::class, 'educators']);
Route::get('/organizations',            [Welcomer::class, 'organizations']);
Route::get('/professionals',            [StorefrontController::class, 'professionals']);
Route::get('/{path}/handbook-mental-health-practitioners',  [StorefrontController::class, 'mental_health']);
Route::get('/{path}/handbook-nursing-professionals',        [StorefrontController::class, 'nursing']);
Route::get('/{path}/handbook-child-welfare-providers',      [StorefrontController::class, 'child_welfare']);
Route::get('/{path}/handbook-primary-care-providers',       [StorefrontController::class, 'physicians']);
Route::get('/parents',                  [Welcomer::class, 'parents']);
Route::get('/youth',                    [Welcomer::class, 'youth']);
//Authentication
Route::get('/validate/{string}',        [AuthLogic::class, 'validate_account'])->name("validate_account");
Route::get('/new_user/{string}',        [AuthLogic::class, 'new_user'])->name("new_user");
Route::get('/login',                    [AuthLogic::class, 'index'])->name("login");
Route::prefix('/{path}')->group(function () {
    Route::get('/login',                [AuthLogic::class, 'index']);
    Route::get('/add_default_roles',    [AuthLogic::class, 'add_default_roles'])->name("add_default_roles");
    Route::post('/login',               [AuthLogic::class, 'authenticate']);
    Route::get('/impersonate/{id}',     [AuthLogic::class, 'impersonate'])->name("impersonate");
    Route::get('/logout',               [AuthLogic::class, 'logout'])->name("logout");
    Route::post('/logout',              [AuthLogic::class, 'logout']);
    Route::post('/password.request',    [AuthLogic::class, 'password_request']);
    Route::post('/password.update',     [AuthLogic::class, 'password_update']);
    Route::get('/reset_pwd/{string}',   [AuthLogic::class, 'reset_pwd']);
    Route::get('/register',             [AuthLogic::class, 'register'])->name("register");
    Route::post('/register',            [AuthLogic::class, 'create']);
    Route::post('/verification.send',   [AuthLogic::class, 'resend'])->name("verification.send");
    Route::post('/verification.resend', [AuthLogic::class, 'resend']);
    Route::get('/forgot',               [AuthLogic::class, 'forgot']);
});
//Orgs-Master Admin
Route::prefix('/{path}/backend')->group(function () {
    Route::group(['middleware' => 'role:admin'], function(){
        Route::get('/master-orgs',              [OrgController::class, 'orgs']);
        Route::post('/org-add',                 [OrgController::class, 'add_org']);
        Route::get('/org-profile/{id}',         [OrgController::class, 'view_org']);
        Route::post('/org-update',              [OrgController::class, 'org_update']);
        Route::post('/org-lic-add',             [OrgController::class, 'org_lic']);
        Route::post('/org-train-add',           [OrgController::class, 'org_training']);
        Route::get('/get_lic',                  [OrgController::class, 'get_lic']);
        Route::post('/org-lic-update',          [OrgController::class, 'lic_update']);
        Route::delete('/lic_delete/{licenses}', [OrgController::class, 'lic_delete']);
        Route::get('/get_training',             [OrgController::class, 'get_training']);
        Route::post('/org-training-update',     [OrgController::class, 'training_update']);
        Route::delete('/training_delete/{trainings}', [OrgController::class, 'training_delete']);

        //Users-Profiles
        Route::get('/user-profile/{id}',         [UserProfileController::class, 'view_user']);

        //Users-Master Admin
        Route::get('/master-users',             [MasterController::class, 'users']);
        Route::get('/master-user-get',          [MasterController::class, 'user_get']);
        Route::post('/assign_users',            [MasterController::class, 'assign_users']);
        Route::post('/master-user-add',         [MasterController::class, 'user_add']);
        Route::post('/master-user-update',      [MasterController::class, 'user_update']);
        Route::delete('/delete_user/{users}',   [MasterController::class, 'delete_user']);
    });
    Route::get('/master-users/resend/{id}', [App\Http\Controllers\User\MasterController::class, 'resend_activation'])->middleware('role:admin|head');
});
//Users-Schools
Route::prefix('/{path}')->group(function () {
    Route::group(['middleware' => 'role:head|admin'], function(){
        Route::get('/api/organization-users', [App\Http\Controllers\User\SchoolController::class, 'get_organization_users']);
    });
});

Route::prefix('/{path}/dashboard')->group(function () {
    Route::group(['middleware' => 'role:head|admin'], function(){
        Route::get('/users', [App\Http\Controllers\User\SchoolController::class, 'users']);
        Route::post('/add_user', [App\Http\Controllers\User\SchoolController::class, 'add_user']);
        Route::get('/user/{id}', [App\Http\Controllers\User\SchoolController::class, 'user_get']);
        Route::post('/user_update', [App\Http\Controllers\User\SchoolController::class, 'user_update']);
        Route::delete('/users/delete_user/{user}', [App\Http\Controllers\User\SchoolController::class, 'delete_user']);
        Route::post('/users/licenses', [App\Http\Controllers\User\SchoolController::class, 'user_licenses']);
        Route::get('/assign-access', [App\Http\Controllers\User\SchoolController::class, 'subscriptions']);
        Route::post('/assign-access', [App\Http\Controllers\User\SchoolController::class, 'assign_curriculum']);
        Route::post('/assign-training', [App\Http\Controllers\User\SchoolController::class, 'assign_training_post']);
        Route::post('/remove-access', [App\Http\Controllers\User\SchoolController::class, 'remove_access']);
        Route::post('/bulk-assign-access', [App\Http\Controllers\User\SchoolController::class, 'bulk_assign_access']);
        Route::post('/bulk-assign-training', [App\Http\Controllers\User\SchoolController::class, 'bulk_assign_access']);
        Route::post('/bulk-remove-access', [App\Http\Controllers\User\SchoolController::class, 'bulk_remove_access']);
        Route::post('/bulk-remove-training', [App\Http\Controllers\User\SchoolController::class, 'bulk_remove_access']);
        Route::post('/add_multiple_packages', [App\Http\Controllers\User\SchoolController::class, 'add_multiple_packages']);

    });
});
//Dashboard - all users except 'Student'
Route::prefix('/{path}')->group(function () {
    Route::get('/dashboard',[App\Http\Controllers\HomeController::class, 'index'])->middleware('role:user|head|team|admin');
    Route::post('/dashboard',[App\Http\Controllers\HomeController::class, 'update_profile'])->middleware('role:user|head|team|admin');
    Route::post('/update_password',[App\Http\Controllers\HomeController::class, 'update_password'])->middleware('role:user|head|team|admin');
    
    // Address Book Management
    Route::get('/dashboard/address-book',[App\Http\Controllers\HomeController::class, 'address_book'])->middleware('role:user|head|team|admin');
    Route::get('/address/{id}',[App\Http\Controllers\HomeController::class, 'getAddress'])->middleware('role:user|head|team|admin');
    Route::post('/address/{id}/set-default',[App\Http\Controllers\HomeController::class, 'setDefaultAddress'])->middleware('role:user|head|team|admin');
    Route::delete('/address/{id}',[App\Http\Controllers\HomeController::class, 'deleteAddress'])->middleware('role:user|head|team|admin');
});
//Backend
Route::prefix('{path}')->group(function () {
    Route::group(['middleware'=>'role:admin|team'], function(){
        Route::get('/backend/permissions',  [BackendController::class]);
        Route::get('/backend',              [BackendController::class, 'index']);
        // Backend stats API for AJAX year selection
        Route::get('/backend/stats/{year}', [BackendController::class, 'stats']);
        //// Effectiveness Data ////
        Route::get('/backend/effective', [App\Http\Controllers\Data\EffectController::class, 'index1']);
        Route::post('/effective/add_family', [App\Http\Controllers\Data\EffectController::class, 'add_family']);
        //Parent's Data
        Route::get('/effective/parents', [App\Http\Controllers\Data\EffectController::class, 'index2']);
        Route::post('/effective/parents/add', [App\Http\Controllers\Data\EffectController::class, 'parent_add']);
        Route::get('/effective/parents/{id}', [App\Http\Controllers\Data\EffectController::class, 'parent_view']);
        Route::get('/effective/parents/{id}/edit', [App\Http\Controllers\Data\EffectController::class, 'parent_show']);
        Route::get('/effective/parents/{id}/dashboard', [App\Http\Controllers\Data\EffectController::class, 'parent_content']);
        Route::patch('/effective/parents', [App\Http\Controllers\Data\EffectController::class, 'parent_update']);
        Route::delete('/effective/parents/{id}', [App\Http\Controllers\Data\EffectController::class, 'delete_parent']);
        //Participant's Data
        Route::get('/effective/participants', [App\Http\Controllers\Data\EffectController::class, 'index3']);
        //Student Rosters
        Route::prefix('/backend/ms-roster')->group(function () {
            Route::get('', [App\Http\Controllers\Students\MsRosterController::class, 'index']);
            Route::get('/{id}', [App\Http\Controllers\Students\MsRosterController::class, 'get_student']);
            Route::patch('', [App\Http\Controllers\Students\MsRosterController::class, 'update_student']);
            Route::post('/{id}/delete', [App\Http\Controllers\Students\MsRosterController::class, 'delete']);
        });
        Route::prefix('/backend/hs-roster')->group(function () {
            Route::get('', [App\Http\Controllers\Students\HsRosterController::class, 'index']);
            Route::get('/{id}', [App\Http\Controllers\Students\HsRosterController::class, 'get_student']);
            Route::patch('', [App\Http\Controllers\Students\HsRosterController::class, 'update_student']);
            Route::post('/{id}/delete', [App\Http\Controllers\Students\HsRosterController::class, 'delete']);
        });
    });
});
//// Finance ////
//Billing
Route::prefix('{path}/dashboard')->group(function () {
    Route::group(['middleware' => 'role:user|head|team|admin'], function(){
        Route::get('/billing', [App\Http\Controllers\Finance\BillingController::class, 'billing']);
        Route::get('/billing/print/{id}', [App\Http\Controllers\Finance\BillingController::class, 'print_invoice_product']);
        Route::get('/my-subscriptions', [App\Http\Controllers\Finance\BillingController::class, 'subscriptions']);
        Route::get('/shipping', [App\Http\Controllers\Finance\BillingController::class, 'shipping']);
        Route::get('/my-donations', [App\Http\Controllers\Finance\BillingController::class, 'donations']);
        Route::get('/subscriptons-trainings', [App\Http\Controllers\Finance\RenewalsController::class, 'subscriptons_trainings']);
        Route::get('/renewals', [App\Http\Controllers\Finance\RenewalsController::class, 'renewals']);
        Route::get('/abandoned_carts', [App\Http\Controllers\Finance\AbandonedCartController::class, 'abandonedCarts']);
    });
});
// Order fulfillment
Route::prefix('{path}/backend')->group(function () {
    Route::group(['middleware' => 'role:user|head|team|admin'], function(){
        Route::get('/fulfillment', [App\Http\Controllers\Finance\FulfillmentController::class, 'index']);
        Route::post('/fulfillment', [App\Http\Controllers\Finance\FulfillmentController::class, 'update']);
        
        // Donation fulfillment (manual donation entry)
        Route::get('/donations-fulfillment', [App\Http\Controllers\Finance\FulfillmentController::class, 'donations']);
        Route::post('/donations-fulfillment', [App\Http\Controllers\Finance\FulfillmentController::class, 'storeDonation']);
        Route::get('/donations-fulfillment/{id}/edit', [App\Http\Controllers\Finance\FulfillmentController::class, 'editDonation']);
        Route::put('/donations-fulfillment/{id}', [App\Http\Controllers\Finance\FulfillmentController::class, 'updateDonation']);
        Route::delete('/donations-fulfillment/{id}', [App\Http\Controllers\Finance\FulfillmentController::class, 'deleteDonation']);
        
        // User search API for donor selection
        Route::get('/api/users/search', [App\Http\Controllers\Finance\FulfillmentController::class, 'searchUsers']);
        Route::post('/api/users/create-donor', [App\Http\Controllers\Finance\FulfillmentController::class, 'createDonorUser']);
    });
});

// Donation management (admin only)
Route::prefix('{path}/admin/donations')->group(function () {
    Route::group(['middleware' => 'role:admin|team'], function(){
        Route::get('/', [App\Http\Controllers\Backend\Finance\DonationsController::class, 'index'])->name('{path}.admin.donations');
        Route::get('/data', [App\Http\Controllers\Backend\Finance\DonationsController::class, 'getData'])->name('{path}.admin.donations.data');
        Route::get('/stats', [App\Http\Controllers\Backend\Finance\DonationsController::class, 'getStats'])->name('{path}.admin.donations.stats');
        Route::get('/export', [App\Http\Controllers\Backend\Finance\DonationsController::class, 'export'])->name('{path}.admin.donations.export');
        Route::get('/{id}', [App\Http\Controllers\Backend\Finance\DonationsController::class, 'show']);
        Route::put('/{id}', [App\Http\Controllers\Backend\Finance\DonationsController::class, 'update']);
        Route::delete('/{id}', [App\Http\Controllers\Backend\Finance\DonationsController::class, 'destroy']);
        Route::get('/{id}/receipt', [App\Http\Controllers\Backend\Finance\DonationsController::class, 'generateReceipt']);
    });
});

//Discounts
Route::prefix('{path}/backend/discounts')->group(function () {
    Route::group(['middleware' => 'role:admin'], function(){
        Route::get('', [App\Http\Controllers\Finance\DiscountController::class, 'index']);
        Route::post('', [App\Http\Controllers\Finance\DiscountController::class, 'create']);
        Route::get('/{id}', [App\Http\Controllers\Finance\DiscountController::class, 'view']);
        Route::patch('', [App\Http\Controllers\Finance\DiscountController::class, 'update']);
        Route::delete('/{id}', [App\Http\Controllers\Finance\DiscountController::class, 'delete']);
    });
});
//Short Links
Route::prefix('{path}/backend/short-links')->group(function () {
    Route::group(['middleware' => 'role:admin'], function(){
        Route::get('', [App\Http\Controllers\Backend\ShortlinkController::class, 'index']);
        Route::post('', [App\Http\Controllers\Backend\ShortlinkController::class, 'create']);
        Route::get('/{id}', [App\Http\Controllers\Backend\ShortlinkController::class, 'view']);
        Route::patch('', [App\Http\Controllers\Backend\ShortlinkController::class, 'update']);
        Route::delete('/{id}', [App\Http\Controllers\Backend\ShortlinkController::class, 'delete']);
    });
});
//// ABOUT ////
Route::prefix('{path}')->group(function () {
    Route::get('/about', [App\Http\Controllers\Welcomer::class, 'about']);
    //Mission & Values
    Route::get('/values', [App\Http\Controllers\Welcomer::class, 'values']);
    Route::get('/values-test', [App\Http\Controllers\Welcomer::class, 'values_test']);
    //Team
    Route::get('/team', [App\Http\Controllers\About\TeamController::class, 'catalog']);
    Route::group(['middleware' => 'role:admin', 'prefix' => 'backend/team'], function(){
        Route::get('', [App\Http\Controllers\About\TeamController::class, 'index']);
        Route::get('/create', [App\Http\Controllers\About\TeamController::class, 'add']);
        Route::post('', [App\Http\Controllers\About\TeamController::class, 'create']);
        Route::get('/{id}', [App\Http\Controllers\About\TeamController::class, 'view']);
        Route::get('/{id}/edit', [App\Http\Controllers\About\TeamController::class, 'show']);
        Route::get('/{id}/dashboard', [App\Http\Controllers\About\TeamController::class, 'content']);
        Route::patch('', [App\Http\Controllers\About\TeamController::class, 'update']);
        Route::delete('/{id}', [App\Http\Controllers\About\TeamController::class, 'delete']);
    });
    Route::get('/team/{slug}', [App\Http\Controllers\About\TeamController::class, 'view_each_member']);
    // Apply
    Route::get('/apply', [App\Http\Controllers\About\ApplyController::class, 'apply']);
    Route::get('/board', [App\Http\Controllers\About\ApplyController::class, 'board']);
    Route::get('/byLaws', [App\Http\Controllers\About\ApplyController::class, 'byLaws']);
    Route::get('/councils', [App\Http\Controllers\About\ApplyController::class, 'councils']);
    Route::get('/volunteer', [App\Http\Controllers\About\ApplyController::class, 'volunteer']);
    // Projects
    Route::get('/projects', [App\Http\Controllers\About\ProjectsController::class, 'projects']);
    //Social Media
    Route::get('/social', [App\Http\Controllers\Welcomer::class, 'social']);
    //Standards
    Route::get('/standards', [App\Http\Controllers\About\StandardsController::class, 'standards']);
    Route::group(['middleware' => 'role:admin|team', 'prefix'=>'backend/standards'], function(){
        Route::get('', [App\Http\Controllers\About\StandardsController::class, 'index']);
        Route::get('/create', [App\Http\Controllers\About\StandardsController::class, 'add']);
        Route::post('', [App\Http\Controllers\About\StandardsController::class, 'create']);
        Route::get('/{id}', [App\Http\Controllers\About\StandardsController::class, 'view']);
        Route::get('/{id}/edit', [App\Http\Controllers\About\StandardsController::class, 'show']);
        Route::get('/{id}/dashboard', [App\Http\Controllers\About\StandardsController::class, 'content']);
        Route::patch('', [App\Http\Controllers\About\StandardsController::class, 'update']);
        Route::delete('/{id}', [App\Http\Controllers\About\StandardsController::class, 'delete']);
        Route::get('/flag/{id}', [App\Http\Controllers\About\StandardsController::class, 'view_flag']);
        Route::patch('/flag', [App\Http\Controllers\About\StandardsController::class, 'update_flag']);
        Route::delete('/flag/{id}', [App\Http\Controllers\About\StandardsController::class, 'delete_flag']);
    });
    //Legal, Terms of Use and other fun stuff
    Route::get('/impressum', [App\Http\Controllers\Welcomer::class, 'impressum']);
    Route::get('/privacy', [App\Http\Controllers\Welcomer::class, 'privacy']);
    Route::get('/terms', [App\Http\Controllers\Welcomer::class, 'terms']);
    Route::get('/purchases', [App\Http\Controllers\Welcomer::class, 'purchases']);
    //Fundraisers
    Route::get('/bday', [App\Http\Controllers\Fundraisers\FundraisersController::class, 'bday']);
    Route::get('/bday-2023', [App\Http\Controllers\Fundraisers\FundraisersController::class, 'bday2023']);
    Route::get('/bday-2022', [App\Http\Controllers\Fundraisers\FundraisersController::class, 'bday2022']);
    Route::get('/bday-2021', [App\Http\Controllers\Fundraisers\FundraisersController::class, 'bday2021']);
    Route::post('/thanks-for-donating', [App\Http\Controllers\Fundraisers\BdayController::class, 'step1'])->name('step1');
    Route::get('/thanks-for-donating', [App\Http\Controllers\Fundraisers\BdayController::class, 'step2'])->name('step2');
    Route::get('/bod-retreat', [App\Http\Controllers\Fundraisers\FundraisersController::class, 'BoD']);
    Route::get('/thebellyproject', [App\Http\Controllers\Fundraisers\FundraisersController::class, 'thebellyproject']);
    Route::post('/thebellyproject/thank-you', [App\Http\Controllers\Fundraisers\BellyProjectController::class, 'submit'])->name('submit');
    Route::get('/thebellyproject/thank-you', [App\Http\Controllers\Fundraisers\BellyProjectController::class, 'thanks'])->name('thanks');
    Route::get('/thebellyproject/index', [App\Http\Controllers\Fundraisers\BellyProjectController::class, 'index']);
});
//// SERVICES ////
Route::prefix('{path}')->group(function () {
    //Educator Services
    Route::get('/services', [App\Http\Controllers\Welcomer::class, 'services']);
    //Curricula
    Route::get('/subscription-info', [App\Http\Controllers\Welcomer::class, 'subscription_info']);
    //Parent Services
    Route::get('/pre-k', [App\Http\Controllers\Parents\ParentsController::class, 'prek']);
    Route::get('/k-thru-5', [App\Http\Controllers\Parents\ParentsController::class, 'kthru5']);
    Route::get('/six-thru-eight', [App\Http\Controllers\Parents\ParentsController::class, 'sixthrueight']);
        Route::get('/middle-school-classes', [App\Http\Controllers\Parents\ParentsController::class, 'classMs']);
        Route::get('/middle-school-registration', [App\Http\Controllers\Parents\ParentsController::class, 'classMsReg']);
        Route::post('/ms-registration', [App\Http\Controllers\Parents\RegisterController::class, 'msStep1'])->name('msStep1');
        Route::get('/ms-registration', [App\Http\Controllers\Parents\RegisterController::class, 'msStep2'])->name('msStep2');
        Route::get('/class-ms-pay', [App\Http\Controllers\Parents\RegisterController::class, 'msPay']);
        Route::get('/parents', [App\Http\Controllers\Parents\ParentsController::class, 'index']);
    Route::get('/nine-thru-twelve', [App\Http\Controllers\Parents\ParentsController::class, 'ninethrutwelve']);
        Route::get('/high-school-classes', [App\Http\Controllers\Parents\ParentsController::class, 'classHs']);
        Route::get('/high-school-registration', [App\Http\Controllers\Parents\ParentsController::class, 'classHsReg']);
        Route::post('/hs-registration', [App\Http\Controllers\Parents\RegisterController::class, 'hsStep1'])->name('hsStep1');
        Route::get('/hs-registration', [App\Http\Controllers\Parents\RegisterController::class, 'hsStep2'])->name('hsStep2');
        Route::get('/class-hs-pay', [App\Http\Controllers\Parents\RegisterController::class, 'hsPay']);
        Route::get('/parents', [App\Http\Controllers\Parents\ParentsController::class, 'index']);
    Route::get('/parent-guardian', [App\Http\Controllers\Parents\ParentsController::class, 'parents']);
    //Youth Services
    Route::get('/hs-drop-in', [App\Http\Controllers\Youth\YouthController::class, 'hsDropIn']);
    //Org Services
    //Consulting
    Route::get('/consulting', [App\Http\Controllers\Welcomer::class, 'consulting']);
});

//// FREE CONTENT ////
Route::prefix('{path}')->group(function () {
    // Free
    Route::get('/free-content', [App\Http\Controllers\Welcomer::class, 'free']);
    //Arcade
    Route::get('/arcade', [App\Http\Controllers\Arcade\ArcadeController::class, 'arcade']);
        //Friendzone
        Route::get('/friendzone', [App\Http\Controllers\Arcade\ArcadeController::class, 'friendzone']);
        Route::get('/friendzone-play', [App\Http\Controllers\Arcade\ArcadeController::class, 'friendzone_play']);
        //Living Things
        Route::get('/living-things', [App\Http\Controllers\Arcade\ArcadeController::class, 'living_things']);
        Route::get('/living-things-play', [App\Http\Controllers\Arcade\ArcadeController::class, 'living_things_play']);
        //Madlibs Game
        Route::get('/madlibs', [App\Http\Controllers\Arcade\MadlibsController::class, 'madlibs']);
        Route::get('/madlibs-play', [App\Http\Controllers\Arcade\MadlibsController::class, 'madlibs_play']);
        Route::post('/mad', [App\Http\Controllers\Arcade\MadlibsController::class, 'mad1'])->name('mad1');
        Route::get('/mad', [App\Http\Controllers\Arcade\MadlibsController::class, 'mad2'])->name('mad2');
        //Ringerangaroo
        Route::get('/ringerangaroo', [App\Http\Controllers\Arcade\ArcadeController::class, 'ringerangaroo']);
        Route::get('/ringerangaroo-play', [App\Http\Controllers\Arcade\ArcadeController::class, 'ringerangaroo_play']);
        //Space Sharks
        Route::get('/space-sharks', [App\Http\Controllers\Arcade\SpaceSharksController::class, 'spacesharks']);
        Route::get('/space-sharks-1', [App\Http\Controllers\Arcade\SpaceSharksController::class, 'spacesharks_1']);
        //Lifespan Game
        Route::get('/lifespan', [App\Http\Controllers\Arcade\ArcadeController::class, 'lifespan']);
        Route::get('/lifespan-play', [App\Http\Controllers\Arcade\ArcadeController::class, 'lifespan_play']);
    //Dictionary
    Route::get('/sex-ed-dictionary', [DictionaryController::class, 'sex_ed_dict']);
        //Backend CRUD Dictionaries
        Route::group(['middleware'=>'role:admin|team', 'prefix'=>'backend/dictionaries'], function(){
            //Tags
            Route::post('/tags', [DictionaryController::class, 'save_tags']);
            Route::post('/types', [DictionaryController::class, 'add_types'])->middleware('role:admin|team');
            //Terms
            Route::get('', [DictionaryController::class, 'index']);
            Route::get('/create', [DictionaryController::class, 'add']);
            Route::post('', [DictionaryController::class, 'create']);
            Route::get('/edit/{id}', [DictionaryController::class, 'view']);
            Route::post('/edit', [DictionaryController::class, 'update_entry']);
            Route::post('/delete/{id}', [DictionaryController::class, 'delete']);
            //Defintions
            Route::get('/term/{id}/defintions', [DictionaryController::class, 'term_defs']);
            Route::post('/term/definitions', [DictionaryController::class, 'term_defs_update']);
            Route::get('/{id}/dashboard', [DictionaryController::class, 'content']);
    });
    //Biological Sex Glossary
    Route::get('/biological-sex', [BiologyController::class, 'biological']);
    Route::group(['middleware'=>'role:admin', 'prefix' => 'backend/biological'], function(){
        Route::get('', [BiologyController::class, 'index']);
        Route::post('', [BiologyController::class, 'create']);
        Route::get('/{id}', [BiologyController::class, 'view']);
        Route::patch('', [BiologyController::class, 'update']);
        Route::delete('/{id}', [BiologyController::class, 'delete']);
    });
    // Research Citations
    Route::get('/research', [ResearchController::class, 'research']);
    Route::get('/legal', [ResearchController::class, 'legal']);
    Route::get('/pedagogy', [ResearchController::class, 'pedagogy']);
    Route::get('/statistics', [ResearchController::class, 'statistics']);
    // {{ path/backend }}
    Route::group(['middleware' => 'role:admin|team', 'prefix' => 'backend'], function(){
        //Pedagogy
        Route::group(['prefix' => 'pedagogy'], function(){
            Route::get('', [PedagogyController::class, 'index']);
            Route::get('/create', [PedagogyController::class, 'add']);
            Route::post('', [PedagogyController::class, 'create']);
            Route::get('/edit/{id}', [PedagogyController::class, 'view']);
            Route::post('/edit', [PedagogyController::class, 'update']);
            Route::post('/delete/{id}', [PedagogyController::class, 'delete']);
        });
        //Stats
        Route::group(['prefix' => 'statistics'], function(){
            Route::get('', [ResearchController::class, 's_index']);
            Route::get('/create', [ResearchController::class, 's_add']);
            Route::post('', [ResearchController::class, 's_create']);
            Route::get('/{id}', [ResearchController::class, 's_view']);
            Route::get('/{id}/edit', [ResearchController::class, 's_show']);
            Route::get('/{id}/dashboard', [ResearchController::class, 's_content']);
            Route::patch('', [ResearchController::class, 's_update']);
            Route::delete('/{id}', [ResearchController::class, 's_delete']);
        });
        //Legal
        Route::group(['prefix' => 'legal'], function(){
            Route::get('', [ResearchController::class, 'l_index']);
            Route::get('/create', [ResearchController::class, 'l_add']);
            Route::post('', [ResearchController::class, 'l_create']);
            Route::get('/{id}', [ResearchController::class, 'l_view']);
            Route::get('/{id}/edit', [ResearchController::class, 'l_show']);
            Route::get('/{id}/dashboard', [ResearchController::class, 'l_content']);
            Route::patch('', [ResearchController::class, 'l_update']);
            Route::delete('/{id}', [ResearchController::class, 'l_delete']);
        });
    });
    //Activities
    Route::get('/free-activities', [App\Http\Controllers\Welcomer::class, 'activities']);
    Route::get('/condoms-for-facts-sake', [App\Http\Controllers\Welcomer::class, 'condoms']);
    Route::get('/explaining-abortion', [App\Http\Controllers\Welcomer::class, 'explain_abortion']);
    Route::get('/explaining-coronavirus', [App\Http\Controllers\Welcomer::class, 'explain_coronavirus']);
    Route::get('/menstruation-information-station', [App\Http\Controllers\Welcomer::class, 'menstruation']);

});
//// FRONT END STORE ////
    //Store
    Route::prefix('{path}')->group(function () {
        Route::prefix('/store')->group(function () {
            Route::get('', [App\Http\Controllers\Store\StorefrontController::class, 'store']);
            // Category routes MUST come before /{slug} to avoid conflicts
            Route::get('/curricula', [App\Http\Controllers\Store\StorefrontController::class, 'curricula']);
            Route::get('/books', [App\Http\Controllers\Store\StorefrontController::class, 'books']);
            Route::get('/games', [App\Http\Controllers\Store\StorefrontController::class, 'games']);
            Route::get('/swag', [App\Http\Controllers\Store\StorefrontController::class, 'swag']);
            Route::get('/tools', [App\Http\Controllers\Store\StorefrontController::class, 'tools']);
            Route::get('/trainings', [App\Http\Controllers\Store\StorefrontController::class, 'trainings']);
            // Utility routes
            Route::get('/available_products', [App\Http\Controllers\Store\StorefrontController::class, 'available_products']);
            Route::get('/{id}/calc_total_products', [App\Http\Controllers\Store\StorefrontController::class, 'calc_total_products']);
            Route::get('/{id}/calc_curriculum_price', [App\Http\Controllers\Store\StorefrontController::class, 'calc_curriculum_price']);
            // Catch-all product route MUST be last
            Route::get('/{slug}', [App\Http\Controllers\Store\StorefrontController::class, 'get_product']);
        });
        Route::get('/subscriptions/{id}/calc_total_subscriptions', [App\Http\Controllers\Store\StorefrontController::class, 'calc_total_subscriptions']);
    });
    
    // Stripe Webhook (public endpoint - Cashier handles signature verification)
    Route::post('/stripe/webhook',          [StripeWebhookController::class, 'handleWebhook']);
    //QuickBooks
    Route::get('{path}/quickbooks', [QuickBooksController::class, 'quickbooks']);
    //Shopping cart
    Route::prefix('{path}')->group(function () {
        //view both
        Route::get('/carts', [App\Http\Controllers\Store\CartSubscriptController::class, 'carts']);
        //subscriptions
        Route::get('/cart_subscriptions', [App\Http\Controllers\Store\CartSubscriptController::class, 'view_cart']);
        Route::get('/update_subs_list', [App\Http\Controllers\Store\CartSubscriptController::class, 'update_subs_list']);
        Route::post('/checkout_subscriptions', [App\Http\Controllers\Store\CartSubscriptController::class, 'subscription_checkout']);
        Route::post('/subscription_payment_process', [App\Http\Controllers\Store\CartSubscriptController::class, 'subscription_payment_process']);
        Route::get('/subscription/success', [App\Http\Controllers\Store\CartSubscriptController::class, 'subscriptionSuccess'])->name('subscription.success');
        Route::get('/subscription/create-organization', [App\Http\Controllers\Store\CartSubscriptController::class, 'showCreateOrganization'])->name('subscription.create-organization');
        Route::post('/subscription/create-organization', [App\Http\Controllers\Store\CartSubscriptController::class, 'storeOrganization'])->name('subscription.store-organization');
        Route::get('/cart_sub_login', [App\Http\Controllers\Store\CartSubscriptController::class, 'cart_sub_login'])->name('cart_sub_login');
        Route::get('/process_order', [App\Http\Controllers\Store\CartSubscriptController::class, 'process_order_public']);
        Route::get('/check_discount', [App\Http\Controllers\Store\CartSubscriptController::class, 'check_discount']);
        //Products
        Route::get('/cart_products', [App\Http\Controllers\Store\CartProductController::class, 'view_cart']);
        Route::get('/add_product_to_cart', [App\Http\Controllers\Store\CartProductController::class, 'add_product_to_cart']);
        Route::post('/cart/update', [App\Http\Controllers\Store\CartProductController::class, 'updateCart'])->name('cart.update');
        Route::get('/cart_login', [App\Http\Controllers\Store\CartProductController::class, 'cart_login'])->name('cart.login');
        //Shipping address and rates
        Route::get('/checkout_address', [App\Http\Controllers\Store\CartProductController::class, 'address'])->name('address');
        Route::post('/address/save', [App\Http\Controllers\Store\CartProductController::class, 'saveAddress'])->name('address.save');
        Route::post('/address/validate', [App\Http\Controllers\Store\CartProductController::class, 'validateAddress'])->name('address.validate');
        Route::get('/shipping/rates', [App\Http\Controllers\Store\CartProductController::class, 'getShippingRates'])->name('shipping.rates');
        Route::post('/shipping/select', [App\Http\Controllers\Store\CartProductController::class, 'selectShipping'])->name('shipping.select');
        
        Route::get('/check_discount', [App\Http\Controllers\Store\CartProductController::class, 'check_discount']);
        
        // Stripe Checkout Routes
        Route::match(['get', 'post'], '/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
        Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
        Route::post('/calculate-shipping', [CheckoutController::class, 'calculateShipping'])->name('checkout.calculate-shipping');
        Route::post('/update-payment-amount', [CheckoutController::class, 'updatePaymentIntentAmount'])->name('checkout.update-payment-amount');
        Route::post('/product_payment_process', [CheckoutController::class, 'processProductPayment'])->name('checkout.process-payment');
        Route::get('/exit_purchased', [App\Http\Controllers\Store\CartProductController::class, 'return'])->name('checkout.exit-purchased');
        
        // Subscription Management
        Route::get('/billing-portal', [SubscriptionController::class, 'billingPortal'])->name('billing.portal');
        
        //Return from Paypal
            //products
            Route::get('/canceled', [App\Http\Controllers\Store\CartProductController::class, 'canceled']);
            Route::get('/return', [App\Http\Controllers\Store\CartProductController::class, 'return']);
            //subscriptions
            Route::get('/subscribed', [App\Http\Controllers\Store\CartSubscriptController::class, 'subscribed']);
            Route::get('/donated', [App\Http\Controllers\Store\CartDonateController::class, 'donated']);
        Route::get('/donate', [App\Http\Controllers\Store\StorefrontController::class, 'donate']);
    });
    //Donate **without $path
    Route::get('/donate', [App\Http\Controllers\Store\StorefrontController::class, 'donate']);
    Route::post('/donate_checkout', [App\Http\Controllers\Store\CartDonateController::class, 'donate_checkout']);
    
    // Donation management routes
    Route::prefix('{path}/donations')->group(function () {
        Route::post('/cancel-subscription', [App\Http\Controllers\Store\CartDonateController::class, 'cancelSubscription'])->middleware('auth');
        Route::get('/manage/{subscription_id}', [App\Http\Controllers\Store\CartDonateController::class, 'manageSubscription'])->middleware('auth');
        Route::get('/{id}/receipt', [App\Http\Controllers\Backend\Finance\DonationsController::class, 'generateReceipt'])->middleware('auth');
    });

//// BACKEND CRUD ////
    Route::prefix('{path}')->group(function () {
        //Packages/Subscriptions
        Route::group(['middleware' => 'role:admin', 'prefix' => 'backend/packages'], function(){
            Route::get('', [App\Http\Controllers\Backend\PackagesController::class, 'index']);
            Route::get('/add', [App\Http\Controllers\Backend\PackagesController::class, 'add']);
            Route::post('', [App\Http\Controllers\Backend\PackagesController::class, 'create']);
            Route::patch('', [App\Http\Controllers\Backend\PackagesController::class, 'update']);
            Route::delete('/{id}', [App\Http\Controllers\Backend\PackagesController::class, 'delete']);
            Route::get('/{id}/view', [App\Http\Controllers\Backend\PackagesController::class, 'view']);
            Route::get('/{id}/edit', [App\Http\Controllers\Backend\PackagesController::class, 'show']);
            Route::get('/{id}/options', [App\Http\Controllers\Backend\PackagesController::class, 'options']);
            Route::post('/{id}/options', [App\Http\Controllers\Backend\PackagesController::class, 'options_create']);
            Route::patch('/{id}/options', [App\Http\Controllers\Backend\PackagesController::class, 'options_update']);
            Route::delete('/{id}/options/{option_id}', [App\Http\Controllers\Backend\PackagesController::class, 'option_delete']);
            Route::get('/{pid}/options/{id}/view', [App\Http\Controllers\Backend\PackagesController::class, 'options_view']);
            Route::get('/{id}/dashboard', [App\Http\Controllers\Backend\PackagesController::class, 'content']);
            Route::get('/{id}/dashboard/{unit_id}/edit', [App\Http\Controllers\Backend\PackagesController::class, 'get_unit']);
            Route::post('/{id}/dashboard/{unit_id}/edit', [App\Http\Controllers\Backend\PackagesController::class, 'update_unit']);
            Route::delete('/{id}/dashboard/{unit_id}/delete', [App\Http\Controllers\Backend\PackagesController::class, 'delete_unit']);
            Route::post('/{id}/dashboard/{unit_id}/add_session', [App\Http\Controllers\Backend\PackagesController::class, 'add_session']);
            Route::post('/{id}/add_unit', [App\Http\Controllers\Backend\PackagesController::class, 'add_unit']);
            Route::post('/{id}/edit', [App\Http\Controllers\Backend\PackagesController::class, 'edit_unit']);
            Route::delete('/{id}/dashboard/{unit_id}/delete', [App\Http\Controllers\Backend\PackagesController::class, 'delete_unit']);
            Route::get('/{id}/dashboard/{unit_id}/session/{session_id}/edit', [App\Http\Controllers\Backend\PackagesController::class, 'get_session']);
            Route::post('/{id}/dashboard/{unit_id}/session/{session_id}/edit', [App\Http\Controllers\Backend\PackagesController::class, 'update_session']);
            Route::delete('/{id}/dashboard/{unit_id}/session/{session_id}/delete', [App\Http\Controllers\Backend\PackagesController::class, 'delete_session']);
            Route::get('/{id}/fetch_sessions/{unit_id}', [App\Http\Controllers\Backend\PackagesController::class, 'fetch_sessions']);
            Route::post('/{id}/dashboard/{unit_id}/document/{session_id}/add', [App\Http\Controllers\Backend\PackagesController::class, 'add_document']);
            Route::get('/{id}/dashboard/{unit_id}/session/{session_id}/document/{document_id}/edit', [App\Http\Controllers\Backend\PackagesController::class, 'get_document']);
            Route::post('/{id}/dashboard/{unit_id}/session/{session_id}/document/{document_id}/edit', [App\Http\Controllers\Backend\PackagesController::class, 'update_document']);
            Route::delete('/{id}/dashboard/{unit_id}/session/{session_id}/document/{document_id}/delete', [App\Http\Controllers\Backend\PackagesController::class, 'delete_document']);
        });
        //NEW MASTER PRODUCTS
        Route::group(['middleware' => 'role:admin|team', 'prefix' => 'backend/products'], function(){
            Route::get('', [App\Http\Controllers\Store\ProductsController::class, 'products']);
            Route::post('/tags', [App\Http\Controllers\Store\ProductsController::class, 'save_tags']);
            Route::post('/tags/delete', [App\Http\Controllers\Store\ProductsController::class, 'delete_tag']);
            Route::get('/options/list', [App\Http\Controllers\Store\ProductsController::class, 'get_options']);
            Route::post('/options', [App\Http\Controllers\Store\ProductsController::class, 'save_options']);
            Route::post('/options/delete', [App\Http\Controllers\Store\ProductsController::class, 'delete_option']);
            Route::post('/options/delete-selected', [App\Http\Controllers\Store\ProductsController::class, 'delete_options']);
            Route::get('/create', [App\Http\Controllers\Store\ProductsController::class, 'create']);
            Route::post('', [App\Http\Controllers\Store\ProductsController::class, 'create_product']);
            Route::get('/{id}', [App\Http\Controllers\Store\ProductsController::class, 'get_product']);
            Route::get('/{id}/variants', [App\Http\Controllers\Store\ProductsController::class, 'get_variants']);
            Route::post('/variants', [App\Http\Controllers\Store\ProductsController::class, 'save_variants']);
            Route::post('/variants/delete', [App\Http\Controllers\Store\ProductsController::class, 'delete_variant']);
            Route::post('/update', [App\Http\Controllers\Store\ProductsController::class, 'update_product']);
            Route::get('/{id}/activity_d', [App\Http\Controllers\Store\ProductsController::class, 'activity_digital']);
            Route::post('/activities', [App\Http\Controllers\Store\ProductsController::class, 'save_activities']);
            Route::get('/{id}/images', [App\Http\Controllers\Store\ProductsController::class, 'get_images']);
            Route::post('/images', [App\Http\Controllers\Store\ProductsController::class, 'save_images']);
            Route::get('/{id}/descriptions', [App\Http\Controllers\Store\ProductsController::class, 'get_descriptions']);
            Route::post('/descriptions', [App\Http\Controllers\Store\ProductsController::class, 'save_descriptions']);
            Route::post('/availability', [App\Http\Controllers\Store\ProductsController::class, 'save_available']);
            Route::post('/availability/{id}', [App\Http\Controllers\Store\ProductsController::class, 'update_availablity']);
            Route::get('/{id}/pricing', [App\Http\Controllers\Store\ProductsController::class, 'get_pricing']);
            Route::post('/pricing', [App\Http\Controllers\Store\ProductsController::class, 'save_pricing']);
            Route::post('/{id}/delete', [App\Http\Controllers\Store\ProductsController::class, 'delete_product']);
            // Curriculum license pricing tiers
            Route::get('/{id}/license-pricing', [App\Http\Controllers\Backend\PackagesController::class, 'licensePricing']);
            Route::post('/{id}/license-pricing', [App\Http\Controllers\Backend\PackagesController::class, 'licensePricingCreate']);
            Route::get('/{id}/license-pricing/{tier_id}', [App\Http\Controllers\Backend\PackagesController::class, 'licensePricingItem']);
            Route::patch('/{id}/license-pricing/{tier_id}', [App\Http\Controllers\Backend\PackagesController::class, 'licensePricingUpdate']);
            Route::delete('/{id}/license-pricing/{tier_id}', [App\Http\Controllers\Backend\PackagesController::class, 'licensePricingDelete']);
            // Options master
            Route::get('/options/list', [App\Http\Controllers\Store\ProductsController::class, 'get_options']);
            // Option values
            Route::get('/values/list', [App\Http\Controllers\Store\ProductsController::class, 'get_option_values']);
            Route::post('/values', [App\Http\Controllers\Store\ProductsController::class, 'save_option_values']);
            Route::post('/values/delete', [App\Http\Controllers\Store\ProductsController::class, 'delete_option_value']);
            Route::post('/values/delete-selected', [App\Http\Controllers\Store\ProductsController::class, 'delete_option_values']);
            // Variant option assignments
            Route::get('/{id}/var-options', [App\Http\Controllers\Store\ProductsController::class, 'get_variant_option_assignments']);
            Route::post('/var-options/save', [App\Http\Controllers\Store\ProductsController::class, 'save_variant_option_assignments']);
        });
        Route::get('/backend/import_products', [App\Http\Controllers\Store\ProductsController::class, 'import_to_collection']);
    });
    //download file
    // Signed curriculum document delivery (private storage streaming)
    Route::get('/resource/{file}', [App\Http\Controllers\DownloadController::class, 'index'])
        ->name('resource.download')
        ->middleware('signed');

//// BACKEND DASHBOARD DIGITAL DELIVERY ////
    Route::prefix('{path}')->group(function () {
        //Head of School
        Route::get('/dashboard/trainings/head-school-session', [App\Http\Controllers\Trainings\SingleDayController::class, 'head'])->middleware('role:head|team|admin');
        //middleware
        Route::group(['middleware' => 'role:user|head|team|admin', 'prefix' => 'dashboard'], function(){
            //View purchased Packages/Subscriptions & Activites
            Route::get('/curricula', [App\Http\Controllers\Backend\PackagesController::class, 'backend_catalog']);
            Route::group(['prefix' => 'curricula'], function(){
                //K-12 curricula
                Route::get('/free-resources', [App\Http\Controllers\Backend\PackagesController::class, 'free']);
                Route::get('/elementary-school-curriculum', [App\Http\Controllers\Backend\PackagesController::class, 'elementary']);
                Route::get('/middle-school-curriculum', [App\Http\Controllers\Backend\PackagesController::class, 'middle']);
                Route::get('/high-school-curriculum', [App\Http\Controllers\Backend\PackagesController::class, 'high']);
                //unhushed at home
                Route::get('/unhushed-at-home-ages-12-15', [App\Http\Controllers\Backend\PackagesController::class, 'uahBundle'])->name('curricula.uah-bundle');
                Route::get('/getting-started', [App\Http\Controllers\Backend\PackagesController::class, 'start']); // Legacy redirect
                //activites
                Route::get('/{digital_slug}', [App\Http\Controllers\Backend\PackagesController::class, 'activity']);
            });
            //View purchased books
            Route::get('/books', [App\Http\Controllers\Backend\EbooksController::class, 'backend_catalog']);
            Route::get('/ebooks/{digital_slug}', [App\Http\Controllers\Backend\EbooksController::class, 'ebooks']);
            Route::get('/books/{digital_slug}', [App\Http\Controllers\Backend\EbooksController::class, 'books']);

            //View purchased trainings
            Route::get('/trainings', [App\Http\Controllers\Backend\TrainingController::class, 'back_catalog']);
            Route::group(['prefix' => 'trainings'], function(){
                //HS
                Route::get('/high-school-curriculum-training-alumni', [App\Http\Controllers\Trainings\HsController::class, 'alumni']);
                Route::get('/high-school-curriculum-training-2025', [App\Http\Controllers\Trainings\HsController::class, 'index']);
                Route::group(['prefix' => 'hs-curriculum-training'], function(){
                    Route::get('', [App\Http\Controllers\Trainings\HsController::class, 'index']);
                    Route::get('/1', [App\Http\Controllers\Trainings\HsController::class, 'one']);
                    Route::get('/2', [App\Http\Controllers\Trainings\HsController::class, 'two']);
                    Route::get('/3', [App\Http\Controllers\Trainings\HsController::class, 'three']);
                    Route::get('/4', [App\Http\Controllers\Trainings\HsController::class, 'four']);
                    Route::get('/5', [App\Http\Controllers\Trainings\HsController::class, 'five']);
                });
                //MS
                Route::get('/middle-school-curriculum-training-alumni', [App\Http\Controllers\Trainings\MsController::class, 'alumni']);
                Route::get('/middle-school-curriculum-training-2025', [App\Http\Controllers\Trainings\MsController::class, 'index']);
                Route::group(['prefix' => 'ms-curriculum-training'], function(){
                    Route::get('/1', [App\Http\Controllers\Trainings\MsController::class, 'one']);
                    Route::get('/2', [App\Http\Controllers\Trainings\MsController::class, 'two']);
                    Route::get('/3', [App\Http\Controllers\Trainings\MsController::class, 'three']);
                    Route::get('/4', [App\Http\Controllers\Trainings\MsController::class, 'four']);
                    Route::get('/5', [App\Http\Controllers\Trainings\MsController::class, 'five']);
                });
                //ES
                Route::get('/elementary-school-curriculum-training-alumni', [App\Http\Controllers\Trainings\EsController::class, 'alumni']);
                Route::get('/elementary-school-curriculum-training-2025', [App\Http\Controllers\Trainings\EsController::class, 'index']);
                Route::group(['prefix' => 'es-curriculum-training'], function(){
                    Route::get('/1', [App\Http\Controllers\Trainings\EsController::class, 'one']);
                    Route::get('/2', [App\Http\Controllers\Trainings\EsController::class, 'two']);
                    Route::get('/3', [App\Http\Controllers\Trainings\EsController::class, 'three']);
                    Route::get('/4', [App\Http\Controllers\Trainings\EsController::class, 'four']);
                    Route::get('/5', [App\Http\Controllers\Trainings\EsController::class, 'five']);
                });
                //SE&L
                Route::group(['prefix' => 'sex-ed-law'], function(){
                    Route::get('', [App\Http\Controllers\Trainings\SealController::class, 'index']);
                    Route::get('/1', [App\Http\Controllers\Trainings\SealController::class, 'one']);
                    Route::get('/2', [App\Http\Controllers\Trainings\SealController::class, 'two']);
                    Route::get('/3', [App\Http\Controllers\Trainings\SealController::class, 'three']);
                    Route::get('/4', [App\Http\Controllers\Trainings\SealController::class, 'four']);
                    Route::get('/5', [App\Http\Controllers\Trainings\SealController::class, 'five']);
                    Route::get('/6', [App\Http\Controllers\Trainings\SealController::class, 'six']);
                    Route::get('/7', [App\Http\Controllers\Trainings\SealController::class, 'seven']);
                    Route::get('/8', [App\Http\Controllers\Trainings\SealController::class, 'eight']);
                    Route::get('/9', [App\Http\Controllers\Trainings\SealController::class, 'nine']);
                    Route::get('/10', [App\Http\Controllers\Trainings\SealController::class, 'ten']);
                    Route::get('/11', [App\Http\Controllers\Trainings\SealController::class, 'eleven']);
                    Route::get('/12', [App\Http\Controllers\Trainings\SealController::class, 'twelve']);
                });
                //TSEO
                Route::group(['prefix' => 'teaching-sex-ed-online'], function(){
                    Route::get('', [App\Http\Controllers\Trainings\TseoController::class, 'index']);
                    Route::get('/1', [App\Http\Controllers\Trainings\TseoController::class, 'one']);
                    Route::get('/2', [App\Http\Controllers\Trainings\TseoController::class, 'two']);
                    Route::get('/3', [App\Http\Controllers\Trainings\TseoController::class, 'three']);
                    Route::get('/4', [App\Http\Controllers\Trainings\TseoController::class, 'four']);
                    Route::get('/5', [App\Http\Controllers\Trainings\TseoController::class, 'five']);
                    Route::get('/6', [App\Http\Controllers\Trainings\TseoController::class, 'six']);
                    Route::get('/7', [App\Http\Controllers\Trainings\TseoController::class, 'seven']);
                    Route::get('/8', [App\Http\Controllers\Trainings\TseoController::class, 'eight']);
                    Route::get('/9', [App\Http\Controllers\Trainings\TseoController::class, 'nine']);
                    Route::get('/10', [App\Http\Controllers\Trainings\TseoController::class, 'ten']);
                });
                Route::get('/bonus-tech-session', [App\Http\Controllers\Trainings\TseoController::class, 'bonus']);
                //EESL
                Route::group(['prefix' => 'ensenando-educacion-sexual-en-linea'], function(){
                    Route::get('', [App\Http\Controllers\Trainings\EeslController::class, 'index']);
                    Route::get('/1', [App\Http\Controllers\Trainings\EeslController::class, 'una']);
                    Route::get('/2', [App\Http\Controllers\Trainings\EeslController::class, 'dos']);
                    Route::get('/3', [App\Http\Controllers\Trainings\EeslController::class, 'tres']);
                    Route::get('/4', [App\Http\Controllers\Trainings\EeslController::class, 'cuatro']);
                    Route::get('/5', [App\Http\Controllers\Trainings\EeslController::class, 'cinco']);
                    Route::get('/6', [App\Http\Controllers\Trainings\EeslController::class, 'seis']);
                    Route::get('/7', [App\Http\Controllers\Trainings\EeslController::class, 'siete']);
                    Route::get('/8', [App\Http\Controllers\Trainings\EeslController::class, 'ocho']);
                    Route::get('/9', [App\Http\Controllers\Trainings\EeslController::class, 'nueve']);
                    Route::get('/10', [App\Http\Controllers\Trainings\EeslController::class, 'diez']);
                });
                //Single Day/Page Trainings
                Route::get('/child-welfare-providers', [App\Http\Controllers\Trainings\SingleDayController::class, 'hcwp']);
                Route::get('/mental-health-practitioners', [App\Http\Controllers\Trainings\SingleDayController::class, 'hmhp']);
                Route::get('/tiktoxic-masculinity-in-the-classroom', [App\Http\Controllers\Trainings\SingleDayController::class, 'tiktoxic']);
                Route::get('/tiktoxic-to-optimistic', [App\Http\Controllers\Trainings\SingleDayController::class, 'tiktoxic2']);
                Route::get('/writing-love-notes-to-policies', [App\Http\Controllers\Trainings\SingleDayController::class, 'wlntp']);
            });
        });
    });
//// GIVE & GET INVOLVED ////
Route::prefix('{path}')->group(function () {
    //Involved
    Route::get('/involved', [App\Http\Controllers\Welcomer::class, 'involved']);
    //Blog
    Route::get('/blog', function () { return Redirect::to('https://blog.unhushed.org/'); });
    Route::get('/blog/{slug}', [App\Http\Controllers\Blog\PostsController::class, 'view_post']);
    Route::get('/backend/blog', [App\Http\Controllers\Blog\PostsController::class, 'posts']);
    Route::get('/backend/blog/new-post', [App\Http\Controllers\Blog\PostsController::class, 'add']);
    Route::post('/backend/blog', [App\Http\Controllers\Blog\PostsController::class, 'create']);
    Route::get('/backend/blog/{id}/view', [App\Http\Controllers\Blog\PostsController::class, 'view'])->middleware('role:admin');
    Route::get('/backend/blog/post-{id}/edit', [App\Http\Controllers\Blog\PostsController::class, 'show'])->middleware('role:admin');
    Route::patch('/backend/blog', [App\Http\Controllers\Blog\PostsController::class, 'update'])->middleware('role:admin');
    Route::delete('/backend/blog/{id}', [App\Http\Controllers\Blog\PostsController::class, 'delete'])->middleware('role:admin|team');
    //comments
    Route::post('/blog/{slug}', [App\Http\Controllers\Blog\CommentsController::class, 'create']);
    //likes
    Route::post('/blog/{slug}', [App\Http\Controllers\Blog\LikesController::class, 'update'])->middleware('throttle:5');
    //Support Pages
    Route::get('/support', [App\Http\Controllers\SupportController::class, 'support']);
    Route::get('/contact', [App\Http\Controllers\SupportController::class, 'contact']);
    Route::get('/c-review', [App\Http\Controllers\SupportController::class, 'cReview']);
    Route::get('/bug', [App\Http\Controllers\SupportController::class, 'bug']);
    Route::get('/booked', [App\Http\Controllers\SupportController::class, 'booked']);
    //Give
    Route::get('/give', [App\Http\Controllers\Welcomer::class, 'give']);
    //News
    Route::get('/news', [App\Http\Controllers\Welcomer::class, 'news']);
    //Route::get('/subscribed', [App\Http\Controllers\Welcomer::class, 'subscribed']);
    //Other Sites
    Route::get('/other', [App\Http\Controllers\Welcomer::class, 'other']);

});
//Videos
Route::get('/{path}/its-brushing-time', [App\Http\Controllers\Welcomer::class, 'brushing_time']);
Route::get('/{path}/betsy-goes-to-the-doctor', [App\Http\Controllers\Welcomer::class, 'betsy']);
Route::get('/{path}/betsy-goes-to-the-doctor-w-commentary', [App\Http\Controllers\Welcomer::class, 'betsy2']);
// CSV UPLOAD FILE OR TEXT
Route::post('/backend/users/file_upload', [App\Http\Controllers\Backend\CSVFileController::class, 'upload'])->name('file_upload')->middleware('role:admin|head');
Route::post('/backend/users/action', [App\Http\Controllers\Backend\CSVFileController::class, 'action'])->middleware('role:admin');
Route::post('/backend/users/action_org', [App\Http\Controllers\Backend\CSVFileController::class, 'action_org'])->middleware('role:admin|head');
// Tax Exemption Management (Admin only)
Route::prefix('/{path}/backend/tax-exemptions')->middleware('role:admin|team')->group(function () {
    Route::get('/', [App\Http\Controllers\Finance\TaxExemptionController::class, 'index']);
    Route::get('/{orgId}', [App\Http\Controllers\Finance\TaxExemptionController::class, 'show']);
    Route::post('/{orgId}/upload-certificate', [App\Http\Controllers\Finance\TaxExemptionController::class, 'uploadCertificate']);
    Route::post('/{orgId}/approve', [App\Http\Controllers\Finance\TaxExemptionController::class, 'approve']);
    Route::post('/{orgId}/reject', [App\Http\Controllers\Finance\TaxExemptionController::class, 'reject']);
    Route::get('/{orgId}/certificate', [App\Http\Controllers\Finance\TaxExemptionController::class, 'downloadCertificate']);
    Route::post('/user/{userId}/exemption', [App\Http\Controllers\Finance\TaxExemptionController::class, 'updateUserExemption']);
});

//Image uploaders
Route::post('/upload',[App\Http\Controllers\HomeController::class, 'upload'])->middleware('role:user|head|team|admin'); //upload a new image
Route::post('/audio_upload',[App\Http\Controllers\References\DictionaryController::class, 'audio_upload'])->middleware('role:user|team|admin'); //upload a new audio file

//Redirects for top Google search 9-28-21
Route::get('/subscription-info', function () {
    return Redirect::to('https://unhushed.org/educators/subscription-info');
});
Route::get('/about', function () {
    return Redirect::to('https://unhushed.org/educators/about');
});
Route::get('/store', function () {
    return Redirect::to('https://unhushed.org/educators/store');
});
Route::get('/values', function () {
    return Redirect::to('https://unhushed.org/educators/values');
});
Route::get('/team', function () {
    return Redirect::to('https://unhushed.org/educators/team');
});
Route::get('/trainings', function () {
    return Redirect::to('https://unhushed.org/educators/trainings');
});
Route::get('/sex-ed-law', function () {
    return Redirect::to('https://unhushed.org/educators/sex-ed-law');
});

