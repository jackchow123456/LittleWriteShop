<?php

namespace JackChow;

use Dcat\Admin\Extension;

class Specific extends Extension
{
    const NAME = 'specific';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';
}