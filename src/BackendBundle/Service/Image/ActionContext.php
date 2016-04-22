<?php


namespace BackendBundle\Service\Image;


use BackendBundle\Entity\Image;
use Oneup\UploaderBundle\Uploader\Response\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;

class ActionContext
{
    /**
     * @var Image
     */
    private $original;

    /**
     * @var  Request
     */
    private $request;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var Image[]
     */
    private $variants = [];

    public function __construct(Image $original, Request $request, ResponseInterface $response)
    {
        $this->original = $original;
        $this->request  = $request;
        $this->response = $response;
    }

    /**
     * @return Image
     */
    public function getOriginal()
    {
        return $this->original;
    }

    /**
     * @return \BackendBundle\Entity\Image[]
     */
    public function getVariants()
    {
        return $this->variants;
    }

    public function addVariant($id, Image $imageVariant)
    {
        $this->variants[$id] = $imageVariant;
    }

    public function getAll()
    {
        $result = $this->getVariants();
        array_unshift($result, $this->getOriginal());

        return $result;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    public function getRequest()
    {
        return $this->request;
    }
}