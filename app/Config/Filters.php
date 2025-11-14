<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to make reading things nicer and simpler.
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'auth'          => \App\Filters\AuthFilter::class,
        'roleauth'      => \App\Filters\RoleAuth::class, // Role-based access control
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     */
    public array $globals = [
        'before' => [
            // 'honeypot',
            // 'csrf',
            // 'invalidchars',
        ],
        'after' => [
            'toolbar',
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     */
    public array $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     * 
     * Added RoleAuth filter for role-based access control
     * Order matters: auth runs first (login check), then roleauth (permission check)
     */
    public array $filters = [
        // Step 1: Check if user is logged in (AuthFilter)
        'auth' => [
            'before' => [
                'admin/*',
                'teacher/*',
                'announcements*',
                'enrollments*',
                'student/*',
            ],
        ],
        
        // Step 2: Check if user has correct role (RoleAuth)
        'roleauth' => [
            'before' => [
                'admin/*',      // Only admin can access
                'teacher/*',    // Teacher and admin can access
            ],
        ],
    ];
}