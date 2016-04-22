<?php


namespace BackendBundle\EventListener;


use BackendBundle\Factory\ImageFactory;
use BackendBundle\Service\Image\ActionChain;
use BackendBundle\Service\Image\ActionContext;
use Oneup\UploaderBundle\Event\PostUploadEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UploadEventListener
{
    const EVENT_SUCCESS = 'backend.upload.success';
    const EVENT_FAIL    = 'backend.upload.fail';
    const EVENT_END     = 'backend.upload.end';

    /** @var ActionChain */
    private $actionChain;

    /** @var EventDispatcherInterface */
    private $dispatcher;

    /** @var ImageFactory */
    private $imageFactory;

    public function __construct(ActionChain $actionChain, EventDispatcherInterface $dispatcher, ImageFactory$imageFactory)
    {
        $this->actionChain  = $actionChain;
        $this->dispatcher   = $dispatcher;
        $this->imageFactory = $imageFactory;
    }

    public function onUpload(PostUploadEvent $e)
    {
        $original = $this->imageFactory->fromFile($e->getFile());
        $context  = new ActionContext($original, $e->getRequest(), $e->getResponse());
        try {
            $this->actionChain->process($context);
            $this->dispatcher->dispatch(self::EVENT_SUCCESS, new UploadResultEvent($context));
        } catch (\Exception $e) {
            $this->dispatcher->dispatch(self::EVENT_FAIL, new UploadResultEvent($context, $e));
        } finally {
            $this->dispatcher->dispatch(self::EVENT_END, new UploadResultEvent($context));
        }
    }
}