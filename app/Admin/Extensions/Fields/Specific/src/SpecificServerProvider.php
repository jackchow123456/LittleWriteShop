<?php

namespace JackChow;

use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Illuminate\Support\ServiceProvider;

class SpecificServerProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(Specific $extension)
    {
        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'specific');
        }

        Admin::booting(function () {
            Form::extend('specific', SpecificField::class);
        });
    }
}