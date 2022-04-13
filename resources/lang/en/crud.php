<?php

return [
    'common' => [
        'actions' => 'Actions',
        'create' => 'Create',
        'edit' => 'Edit',
        'update' => 'Update',
        'new' => 'New',
        'cancel' => 'Cancel',
        'save' => 'Save',
        'delete' => 'Delete',
        'delete_selected' => 'Delete selected',
        'search' => 'Search...',
        'back' => 'Back to Index',
        'are_you_sure' => 'Are you sure?',
        'no_items_found' => 'No items found',
        'created' => 'Successfully created',
        'saved' => 'Saved successfully',
        'removed' => 'Successfully removed',
        'errors' => 'Failed to create / update',
    ],

    'permissions' => [
        'name' => 'Permissions',
        'index_title' => 'Permissions List',
        'create_title' => 'Create Permission',
        'edit_title' => 'Edit Permission',
        'show_title' => 'Show Permission',
        'inputs' => [
            'no' => 'No',
            'name' => 'Name',
        ],
    ],

    'roles' => [
        'name' => 'Roles',
        'index_title' => 'Roles List',
        'create_title' => 'Create Role',
        'edit_title' => 'Edit Role',
        'show_title' => 'Show Role',
        'inputs' => [
            'no' => 'No',
            'name' => 'Name',
        ],
    ],

    'users' => [
        'name' => 'Users',
        'index_title' => 'Users List',
        'new_title' => 'New User',
        'create_title' => 'Create User',
        'edit_title' => 'Edit User',
        'show_title' => 'Show User',
        'inputs' => [
            'no' => 'No',
            'name' => 'Name',
            'slug' => 'Slug',
            'avatar' => 'Avatar',
            'email' => 'Email',
            'password' => 'Password',
        ],
    ],

    'kriteria' => [
        'name' => 'Kriteria',
        'index_title' => 'Kriteria List',
        'new_title' => 'New Kriteria',
        'create_title' => 'Create Kriteria',
        'edit_title' => 'Edit Kriteria',
        'show_title' => 'Show Kriteria',
        'inputs' => [
            'no' => 'No',
            'nama' => 'Nama',
        ],
    ],

    'subkriteria' => [
        'name' => 'Sub Kriteria',
        'index_title' => 'Sub Kriteria List',
        'new_title' => 'New Sub Kriteria',
        'create_title' => 'Create Sub Kriteria',
        'edit_title' => 'Edit Sub Kriteria',
        'show_title' => 'Show Sub Kriteria',
        'inputs' => [
            'no' => 'No',
            'kriteria' => 'Kriteria',
            'nama' => 'Subkriteria',
        ],
    ],

    'statuskumuh' => [
        'name' => 'Status Kumuh',
        'index_title' => 'Status Kumuh List',
        'new_title' => 'New Status Kumuh',
        'create_title' => 'Create Status Kumuh',
        'edit_title' => 'Edit Status Kumuh',
        'show_title' => 'Show Status Kumuh',
        'inputs' => [
            'no' => 'No',
            'nama' => 'Nama',
            'warna' => 'Warna',
        ],
    ],

    'settings' => [
        'name' => 'Settings',
        'index_title' => 'Site Setting',
        'inputs' => [
            'name' => 'Site Name',
            'description' => 'Site Description',
            'email' => 'Site Email',
            'phone' => 'Site Phone',
            'address' => 'Site Address',
            'facebook' => 'Site Facebook',
            'twitter' => 'Site Twitter',
            'instagram' => 'Site Instagram',
        ],
    ],

    'province' => [
        'name' => 'Province',
        'index_title' => 'Province List',
        'new_title' => 'New Province',
        'create_title' => 'Create Province',
        'edit_title' => 'Edit Province',
        'show_title' => 'Show Province',
        'inputs' => [
            'no' => 'No',
            'code' => 'Code',
            'name' => 'Name',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
        ],
    ],

    'city' => [
        'name' => 'City',
        'index_title' => 'City List',
        'new_title' => 'New City',
        'create_title' => 'Create City',
        'edit_title' => 'Edit City',
        'show_title' => 'Show City',
        'inputs' => [
            'no' => 'No',
            'province' => 'Province',
            'name' => 'City',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
        ],
    ],

    'districts' => [
        'name' => 'Districts',
        'index_title' => 'Districts List',
        'new_title' => 'New Districts',
        'create_title' => 'Create Districts',
        'edit_title' => 'Edit Districts',
        'show_title' => 'Show Districts',
        'inputs' => [
            'no' => 'No',
            'province' => 'Province',
            'city' => 'City',
            'name' => 'Districts',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
        ],
    ],

    'village' => [
        'name' => 'Village',
        'index_title' => 'Village List',
        'new_title' => 'New Village',
        'create_title' => 'Create Village',
        'edit_title' => 'Edit Village',
        'show_title' => 'Show Village',
        'inputs' => [
            'no' => 'No',
            'province' => 'Province',
            'city' => 'City',
            'districts' => 'Districts',
            'name' => 'Village',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
        ],
    ],
];
