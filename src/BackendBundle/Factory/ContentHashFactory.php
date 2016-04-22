<?php


namespace BackendBundle\Factory;


use BackendBundle\Entity\ContentHash;

class ContentHashFactory
{
    /**
     * @param Image $image
     * @return ContentHash
     */
    public function fromImage(Image $image)
    {
        $hash = $this->create();
        $hash->setImage($image);

        return $hash;
    }

    /**
     * @return ContentHash
     */
    public function create()
    {
        return new ContentHash();
    }
}