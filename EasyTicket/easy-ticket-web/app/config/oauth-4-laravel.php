<?php
return array( 

    /*
    |--------------------------------------------------------------------------
    | oAuth Config
    |--------------------------------------------------------------------------
    */

    /**
     * Storage
     */
    'storage' => 'Session', 

    /**
     * Consumers
     */
    'consumers' => array(

        /**
         * Facebook
         */
        'Facebook' => array(
            'client_id'     => '803304743059352',//'357016617783229',
            'client_secret' => '90abe504555d0a6a46805a584cbe8c23',//'f6af19e4bf503410f5ea427a46882b4c',
            'scope'         => array('email','manage_pages','publish_actions','user_activities', 'user_about_me'),
        ), 

        /**
         * Google
         */  
        'Google' => array(
            'client_id'     => '697307204497-pda9ipo17vbku1eq2uveu45q9i56cftj.apps.googleusercontent.com',
            'client_secret' => 'SqJDBcYbi_qu6Uty68X7xNon',
            'scope'         => array('userinfo_email', 'userinfo_profile'),
        ),  
        /*
         * Twitter
         */ 

        'Twitter' => array(
            'client_id'     => 'qMEEIartbzlLLdvLbmbRZExMd',
            'client_secret' => '7BrXj3gBYWWW9e9VTejei1gsKUbhmwmP4unT1ottfFoe4VZADF',
            // No scope - oauth1 doesn't need scope
        ),

        /*
         *
         */

        'Linkedin' => array(
            'client_id'     => '757gmxjgd9fwn0',
            'client_secret' => 'Nq5P0E8qbLDWGfEh',
        ),  

    )

);