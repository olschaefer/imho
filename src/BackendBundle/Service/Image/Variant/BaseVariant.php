<?php


namespace BackendBundle\Service\Image\Variant;


use Imagine\Imagick\Imagine;
use Symfony\Component\HttpFoundation\File\File;

abstract class BaseVariant
{
    protected function createImage(File $file)
    {
        $im = new Imagine();

        return $im->open($file->getRealPath());
    }
}