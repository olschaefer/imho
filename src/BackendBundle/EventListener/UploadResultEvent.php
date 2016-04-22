<?php


namespace BackendBundle\EventListener;

use BackendBundle\Service\Image\ActionContext;
use Symfony\Component\EventDispatcher\Event;

class UploadResultEvent extends Event
{
    /**
     * @var ActionContext
     */
    private $context;

    /**
     * @var \Exception
     */
    private $exception;

    public function __construct(ActionContext $context, \Exception $exception = null)
    {
        $this->context   = $context;
        $this->exception = $exception;
    }

    /**
     * @return ActionContext
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return \Exception
     */
    public function getException()
    {
        return $this->exception;
    }
}