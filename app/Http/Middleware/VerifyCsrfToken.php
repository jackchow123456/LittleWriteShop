<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '*/api/createGoodsAttr',
        '*/fileManager',
        '*/fileManager/image',
        '*/fileManager/folders',
        '*/fileManager/multiUpload',
        '*/fileManager/listFolders',
        '*/fileManager/files',
        '*/fileManager/create',
        '*/fileManager/create',
        '*/fileManager/directory',
        '*/fileManager/delete',
        '*/fileManager/move',
        '*/fileManager/copy',
        '*/fileManager/rename',
        '*/fileManager/upload',
    ];
}
