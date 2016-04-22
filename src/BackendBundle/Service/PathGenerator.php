<?php


namespace BackendBundle\Service;


use BackendBundle\Entity\Image;

class PathGenerator
{
    public function getPath(Image $image)
    {
        $bucket    = (string) $image->getStorageBucket();
        $bucketLen = strlen($bucket);
        $id        = $image->getId();

        return   '/'.(isset($bucket[$bucketLen-2]) ? $bucket[$bucketLen-2] : '').$bucket[$bucketLen-1]
                .'/'.$bucket
                .'/'.$id[0].$id[1].$id[2]
                .'/'.$id[3].$id[4].$id[5]
                .'/'.$id.'.'.$image->getFile()->getExtension();
    }
}