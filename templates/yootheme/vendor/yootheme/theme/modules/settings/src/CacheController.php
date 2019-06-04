<?php

namespace YOOtheme\Theme;

use YOOtheme\ContainerTrait;

class CacheController
{
    use ContainerTrait;

    public function index($request, $response)
    {
        return $response->withJson(['files' => iterator_count($this->getFiles())]);
    }

    public function clear($request, $response)
    {
        foreach ($this->getFiles() as $file) {
            if ($file->isFile()) {
                unlink($file->getRealPath());
            } elseif ($file->isDir()) {
                rmdir($file->getRealPath());
            }
        }

        return $response->withJson(['message' => 'success']);
    }

    protected function getFiles()
    {
        return new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->app['path.cache'], \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::FOLLOW_SYMLINKS), \RecursiveIteratorIterator::CHILD_FIRST);
    }
}
