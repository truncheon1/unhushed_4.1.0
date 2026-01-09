<?php

return [
    // Canonical curriculum product names used across the site
    'names' => [
        'free' => 'Free Curriculum Resources',
        'elementary' => 'Elementary School Curriculum',
        'middle' => 'Middle School Curriculum',
        'high' => 'High School Curriculum',
    ],
    // Optional hard bindings for product IDs (populate as they become stable)
    'ids' => [
        'free' => null,
        'elementary' => null, // formerly 2
        'middle' => null,
        'high' => null,
    ],

    // Policy: require an assignment record for FREE curriculum pages?
    // false => any authenticated user may view free curriculum content
    // true  => must have explicit ProductAssignments (category = 1)
    'free_requires_assignment' => false,
];
