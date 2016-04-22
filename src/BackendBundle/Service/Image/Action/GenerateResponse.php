<?php


namespace BackendBundle\Service\Image\Action;


use BackendBundle\Entity\Image;
use BackendBundle\EventListener\UploadResultEvent;
use BackendBundle\Service\Image\ActionContext;
use BackendBundle\Service\Image\BaseAction;
use BackendBundle\Service\UrlGenerator;
use Oneup\UploaderBundle\Uploader\Response\FineUploaderResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class GenerateResponse extends BaseAction
{
    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    /**
     * @var Router
     */
    private $router;

    public function __construct(UrlGenerator $urlGenerator, Router $router)
    {
        $this->urlGenerator = $urlGenerator;
        $this->router       = $router;
    }
    /**
     * @param ActionContext $context
     * @return void
     */
    public function process(ActionContext $context)
    {
        $response = $context->getResponse();
        $responseResult = [
            'original' => $this->getImageItem($context->getOriginal()),
            'variants' => [],
        ];

        foreach ($context->getVariants() as $id => $image) {
            $responseResult['variants'][$id] = $this->getImageItem($image);
        }

        $response['result'] = $responseResult;
    }

    public function getImageItem(Image $image)
    {
        return [
            'id'         => $image->getId(),
            'originalId' => $image->getOriginalId(),
            'url'        => $this->urlGenerator->getDirectUrl($image),
            'viewUrl'    => $this->urlGenerator->getViewUrl($image),
            'deleteUrl'  => $this->urlGenerator->getDeleteUrl($image),
            'deleteKey'  => $image->getKey(),
        ];
    }

    public function onUploadFail(UploadResultEvent $event)
    {
        /** @var FineUploaderResponse $response */
        $response = $event->getContext()->getResponse();
        $response->setSuccess(false);
        $response['result'] = [];
        $response->setError($event->getException()->getMessage());
    }
}