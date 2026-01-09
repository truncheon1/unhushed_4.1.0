<?php
return [
    //Temp folder to hold all uploads
    'hold' => base_path().env('PUBLIC_PATH').'/uploads/temps',

    //Constants for dashboards in HomeController.php
    'avatar' => [
        'upload' => base_path().env('PUBLIC_PATH').'/uploads/avatars',
        'path' => '/uploads/avatars/',
    ],
    
    //Constants for fundraisers in Fundraisers/BellyprojectController.php
    'belly' => [
        'upload' => base_path().env('PUBLIC_PATH').'/uploads/thebellyproject',
        'path' => '/uploads/thebellyproject/',
    ],

    //Constants for fundraisers in Fundraisers/BellyprojectController.php
    'blog' => [
        'upload' => base_path().env('PUBLIC_PATH').'/uploads/blog',
        'path' => '/uploads/blog/',
    ],

    //Constants for csv upload in Backend/CSVFileController.php
    'csv' => [
        'upload' => base_path().env('PUBLIC_PATH').'/uploads/temps',
    ],

    //Constants for dictionaries in References/DictionaryController.php
    'dict' => [
        'upload' => base_path().env('PUBLIC_PATH').'/uploads/audio',
        'path' => '/uploads/audio/',
    ],
    
    //Constants for PackagesController.php
    'file' => base_path().env('PUBLIC_PATH').'/uploads/subscriptions',
    
    //Constants for standards in About/StandardsController.php
    'standards' => [
        'upload' => base_path().env('PUBLIC_PATH').'/uploads/standards',
        'path' => '/uploads/standards/',
    ],

    //Constants for teams in About/TeamController.php
    'team' => [
        'upload' => base_path().env('PUBLIC_PATH').'/uploads/team',
        'path' => '/uploads/team/',
    ],


    //Constants for import users in User/ImportUsersController.php
    'users' => [
        'import' => base_path().env('PUBLIC_PATH').'/import.csv',
        'path' => '/import.csv',
    ],

    //Constants for products in Store/ProductsController.php
    'categories' => [
        'curriculum'=>1,
        'activity'=>2,
        'book'=>3,
        'game'=>4,
        'swag'=>5,
        'toolkit'=>6,
        'training'=>7,
    ],
    'product' => [
        'upload' => base_path().env('PUBLIC_PATH').'/uploads/products',
        'upload_temp' => base_path().env('PUBLIC_PATH').'/uploads/temps',
        'path' => '/uploads/products/',
        'path_temp' => base_path().env('PUBLIC_PATH').'/uploads/temps',
    ],
];

