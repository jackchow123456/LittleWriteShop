<?php

namespace JackChow;

use Dcat\Admin\Form\Field;

class SpecificField extends Field
{
    protected static $js = [
        'vendors/dcat-admin/vendors/js/forms/select/select2.full.min.js',
//        'vendors/jackchow/layer/layer.js',
        'vendors/jackchow/jstree/dist/jstree.min.js',
        'vendors/jackchow/test/test.js',
        'vendors/jackchow/viewer/js/viewer.js',
        'vendors/jackchow/viewer/js/jquery-viewer.js',
        'vendors/jackchow/bootstrap-fileinput/js/fileinput.min.js',
        'vendors/jackchow/specific/specific.js',
    ];

    protected $view = 'specific::specific_field';

    protected static $css = [
//        'vendors/jackchow/font-awesome-4.7.0/css/font-awesome.min.css',
//        'vendors/jackchow/google-fonts/fonts.css',
        'vendors/dcat-admin/vendors/css/forms/select/select2.min.css',
        'vendors/jackchow/jstree/dist/themes/default/style.min.css',
        'vendors/jackchow/test/test.css',
        'vendors/jackchow/viewer/css/viewer.css',
        'vendors/jackchow/bootstrap-fileinput/css/fileinput.min.css',
        'vendors/jackchow/specific/specific.css',
    ];

    public function render()
    {
        $this->script = <<< EOF
window.DemoSpecific = new JackChowSpecific('{$this->getElementClassSelector()}')
EOF;
        return parent::render();
    }
}