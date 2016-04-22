<?php


namespace BackendBundle\Service\Image\Variant;

use Imagine\Image\Box;
use Symfony\Component\HttpFoundation\File\File;

class ThumbnailVariant extends BaseVariant implements Variant
{
    private $width;
    private $height;

    public function __construct($width, $height)
    {
        $this->width  = $width;
        $this->height = $height;
    }

    public function generate(File $in, File $out)
    {
        $image = $this->createImage($in);
        $image->thumbnail(new Box($this->width, $this->height))
              ->save($out->getPathname());

        return new File($out->getRealPath());
    }

    public function getId()
    {
        return 'thumb-'.$this->width.'x'.$this->height;
    }
}