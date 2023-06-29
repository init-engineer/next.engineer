<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function boot(): void
    {
        $this->registerCaptcha();
    }

    /**
     * Register the locale blade extensions.
     * See: App\Rules\Captcha for implementation
     * See LoginController/RegisterController for usage.
     */
    protected function registerCaptcha(): void
    {
        Blade::directive('captcha', function ($lang) {
            $sitekey = config('captcha.site_key');
            $location = config('captcha.options.location');
            $hidden = config('captcha.options.hidden') ? 'display:none;!important' : '';
            $lang = $lang ? "?hl=$lang" : '';

            return view('includes.partials.captcha')
                ->with('sitekey', $sitekey)
                ->with('location', $location)
                ->with('hidden', $hidden)
                ->with('lang', $lang);
        });
    }
}
