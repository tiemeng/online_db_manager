<?php

namespace App\Handlers;


class ImageUploadHandler
{
    // 只允许以下后缀名的图片文件上传
    protected $allowed_ext = ["png", "jpg", "gif", 'jpeg'];

    /**
     * @param $file  文件名称
     * @param $folder 空间名称
     * @return array|bool
     */
    public function save($file, $folder)
    {

        $folder_name = "uploads/images/$folder/" . date("Ym", time()) . '/'.date("d", time()).'/';

        $upload_path = public_path() . '/' . $folder_name;

        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        $filename = time() . '_' . str_random(10) . '.' . $extension;

        // 如果上传的不是图片将终止操作
        if ( ! in_array($extension, $this->allowed_ext)) {
            return false;
        }

        // 将图片移动到我们的目标存储路径中
        $file->move($upload_path, $filename);

        return [
            'path' => "/$folder_name/$filename"
        ];
    }
}