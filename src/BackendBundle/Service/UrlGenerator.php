<?php


namespace BackendBundle\Service;


use BackendBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class UrlGenerator
{
    /**
     * @var string
     */
    private $storageBaseUri;

    /**
     * @var Router
     */
    private $router;

    public function __construct($storageUri, Router $router)
    {
        $this->storageBaseUri = rtrim($storageUri, '/');
        $this->router  = $router;
    }

    public function getDirectUrl(Image $image)
    {
        $path = $image->getPath();
        return $this->storageBaseUri . '/' . ltrim($path, '/');
    }

    public function getViewUrl(Image $image)
    {
        return $this->router->generate('backend_view', ['originalId' => $image->getOriginalId()], Router::ABSOLUTE_URL);
    }

    public function getDeleteUrl(Image $image)
    {
        return $this->router->generate('backend_delete', ['originalId' => $image->getOriginalId()], Router::ABSOLUTE_URL);
    }
}