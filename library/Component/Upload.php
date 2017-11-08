<?php 

namespace Skinny\Component;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use FileSystem;

class Upload 
{
    public function __construct($rootPath = ATTACHMENT_DIR)
    {
        $this->rootPath = $this->makeDirectory($rootPath);
    }

    public function action(UploadedFile $file)
    {
        $tempPath = $file->getRealPath();
        $originalName = $file->getClientOriginalName();
        $ext = $file->getClientOriginalExtension();
        $filename = md5($originalName) . '.' . $ext;
        $path = rtrim($this->rootPath, '/') . '/' . date('Ymd');
        $path = $this->makeDirectory($path);
        $file->move($path, $filename);
        // 返回上传文件信息
        $info['originalName'] = $originalName;
        $info['savefileName'] = $filename;
        $info['savefileDir'] = $path;
        $info['savefilePath'] = $path . '/' . $filename;
        if(strpos($path, PUBLIC_DIR) !== false)
        {
            $path = '/' . trim(str_replace(PUBLIC_DIR, '', $path), '/') . '/';
            $info['savefilePath'] = $path . $filename;
        }

        return $info;

    }

    protected function makeDirectory($path)
    {
        if(! file_exists($path))
        {
            $flag = (new FileSystem())->makeDirectory($path, 0755, true);
            if(! $flag)
            {
                throw new \InvalidArgumentException('create dir ' . $path . ' error.');
            }
        }

        return $path;
    }
}
