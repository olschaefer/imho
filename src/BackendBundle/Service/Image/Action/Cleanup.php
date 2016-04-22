<?php


namespace BackendBundle\Service\Image\Action;

use BackendBundle\EventListener\UploadResultEvent;
use BackendBundle\Service\Image\ActionContext;
use BackendBundle\Service\Image\BaseAction;

class Cleanup extends BaseAction
{
    /**
     * @param ActionContext $context
     * @return void
     */
    public function process(ActionContext $context)
    {
        foreach ($context->getAll() as $image) {
            if ($image->getFile()) {
                unlink($image->getFile()->getRealPath());
            }
        }
    }

    public function onUploadFail(UploadResultEvent $event)
    {
        $this->process($event->getContext());
    }
}