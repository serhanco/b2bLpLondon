<?php
// admin_config.example.php
// Copy to admin_config.php (not tracked by git) to turn on the lead panel at /admin.
//
// Passwords are stored only as hashes. To get a hash, open /admin while this
// file is missing or has no users: the page offers a hash generator. Paste the
// generated line into 'users' below.
return [
    // username => password_hash(...)
    'users' => [
        // 'serhan' => '$2y$12$....',
    ],

    // Optional: only these IP addresses may open the panel. Empty = any IP.
    'allowed_ips' => [],
];
