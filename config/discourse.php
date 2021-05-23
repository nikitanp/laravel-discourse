<?php

return [

    // API token.
    'token' => env('DISCOURSE_TOKEN'),

    'sso_enabled' => env('DISCOURSE_SSO_ENABLED', false),

    // Middleware for the SSO login route to use
    'middleware' => ['web', 'auth'],

    // The route's URI that acts as the entry point for Discourse to start the SSO process.
    // Used by Discourse to route incoming logins.
    'route' => 'discourse/sso',
    'logout' => 'discourse/sso/logout',

    // Secret string used to encrypt/decrypt SSO information,
    // be sure that it is 10 chars or longer
    'secret' => env('DISCOURSE_SECRET'),

    // Disable Discourse from sending welcome message
    'suppress_welcome_message' => 'true',

    // Where the Discourse forum lives
    'url' => env('DISCOURSE_URL', ''),

    // User-specific items
    // NOTE: The 'email' & 'external_id' are the only 2 required fields
    'user' => [
        // Check to see if the user has forum access & should be logged in via SSO
        'access' => null,

        // Discourse Groups to make sure that the user is part of in a comma-separated string
        // NOTE: Groups cannot have spaces in their names & must already exist in Discourse
        'add_groups' => null,

        // Boolean for making the user a Discourse admin. Leave null to ignore
        'admin' => 'discourse_admin',

        // Full path to user's avatar image
        'avatar_url' => null,

        // The avatar is cached, so this triggers an update
        'avatar_force_update' => false,

        // Content of the user's bio
        'bio' => null,

        // Verified email address (see "require_activation" if not verified)
        'email' => 'email',

        // Unique string for the user that will never change
        'external_id' => 'id',

        // Boolean for making user a Discourse moderator. Leave null to ignore
        'moderator' => 'discourse_moderator',

        // Full name on Discourse if the user is new or
        // if SiteSetting.sso_overrides_name is set
        'name' => 'name',

        // Discourse Groups to make sure that the user is *NOT* part of in a comma-separated string.
        // NOTE: Groups cannot have spaces in their names & must already exist in Discourse
        // There is not a way to specify the exact list of groups that a user is in, so
        // you may want to send the inverse of the 'add_groups'
        'remove_groups' => null,

        // If the email has not been verified, set this to true
        'require_activation' => false,

        // username on Discourse if the user is new or
        // if SiteSetting.sso_overrides_username is set
        'username' => 'email',
    ],
];
