<?php


namespace BackendBundle\Service\Image\Action;


use BackendBundle\Service\Image\ActionContext;
use BackendBundle\Service\Image\BaseAction;
use BackendBundle\Service\ImageManager;

class Save extends BaseAction
{
    /** @var ImageManager */
    private $imageManager;

    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    /**
     * @param ActionContext $context
     * @return void
     */
    public function process(ActionContext $context)
    {
        foreach ($context->getAll() as $image) {
            $this->imageManager->save($image);
        }
    }
}