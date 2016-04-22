<?php


namespace BackendBundle\Service\Image\Variant;


use Symfony\Component\HttpFoundation\File\File;

interface Variant
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @param File $in
     * @return File new file
     */
    public function generate(File $in, File $out);
}