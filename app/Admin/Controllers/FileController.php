<?php
namespace App\Admin\Controllers;

use Dcat\Admin\Traits\HasUploadedFile;

class FileController
{
    use HasUploadedFile;

    public function handle()
    {
        $disk = $this->disk('admin');

        // 判断是否是删除文件请求
        if ($this->isDeleteRequest()) {
            // 删除文件并响应
            return $this->deleteFileAndResponse($disk);
        }

        // 获取上传的文件
        $file = $this->file();

        // 获取上传的字段名称
        $column = $this->uploader()->upload_column;

        $dir = 'my-images';
//        $newName = $column.'-我的文件名称.'.$file->getClientOriginalExtension();

        $result = $disk->putFile($dir, $file);

//        $path = "{$dir}/$result";

        return $result
            ? $this->responseUploaded($result, $disk->url($result))
            : $this->responseErrorMessage('文件上传失败');
    }
}