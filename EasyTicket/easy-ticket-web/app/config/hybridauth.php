<?php
return array(

    // 'base_url' => URL::route(Config::get('anvard::routes.login')),

    'providers' => array (

        "Google" => array (
            "enabled" => true,
            "keys"    => array ( "id" => "697307204497-pda9ipo17vbku1eq2uveu45q9i56cftj.apps.googleusercontent.com", "secret" => "SqJDBcYbi_qu6Uty68X7xNon" ),
            "scope"   => "https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email" // optional
        ),

        'Facebook' => array (
            'enabled' => true,
            'keys'    => array ( 'id' => '803304743059352', 'secret' => '90abe504555d0a6a46805a584cbe8c23' ),
            "scope"   => "email, user_about_me, user_birthday, user_hometown, user_website, offline_access, read_stream, publish_stream, read_friendlists, manage", // optional
        ),

        'Twitter' => array (
            'enabled' => true,
            'keys'    => array ( 'key' => '', 'secret' => '' )
        ),

        'LinkedIn' => array (
            'enabled' => true,
            'keys'    => array ( 'key' => '', 'secret' => '' )
        ),
    )
);