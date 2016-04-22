<?php


namespace BackendBundle\Service\Image;


use BackendBundle\Service\Image\Action;

abstract class BaseAction implements Action
{
    public function getLabel()
    {
        $className = static::class;
        if (false !== $pos = strrpos($className, '\\')) {
            $className = substr($className, $pos + 1);
        }

        return lcfirst($className);
    }
}