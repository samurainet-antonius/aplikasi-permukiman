<?php

namespace App\Models;

use Spatie\LaravelSettings\Settings;

class SiteSetting extends Settings
{
    public string $site_name;
    public string $site_logo;
    public string $site_description;
    public string $site_address;
    public string $site_email;
    public string $site_phone;
    public string $site_fax_email;

    public static function group(): string
    {
        return 'general';
    }
}