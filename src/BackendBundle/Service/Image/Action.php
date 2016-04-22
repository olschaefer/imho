<?php


namespace BackendBundle\Service\Image;


interface Action
{
    /**
     * @param ActionContext $context
     * @return void
     */
    public function process(ActionContext $context);

    /**
     * @return string
     */
    public function getLabel();
}