<?php


namespace BackendBundle\Factory;


use BackendBundle\Entity\Image;
use Symfony\Component\HttpFoundation\File\File;

class ImageFactory
{
    public function fromFile(File $file)
    {
        $image = $this->create();
        $image->setFile($file);
        $image->setSize($file->getSize());

        return $image;
    }

    public function create()
    {
        return new Image();
    }
}