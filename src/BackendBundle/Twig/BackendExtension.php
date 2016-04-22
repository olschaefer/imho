<?php


namespace BackendBundle\Twig;


use BackendBundle\Entity\Image;
use BackendBundle\Service\BBCodeGenerator;
use BackendBundle\Service\UrlGenerator;

class BackendExtension extends \Twig_Extension
{
    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    /**
     * @var BBCodeGenerator
     */
    private $bbcodeGenerator;

    public function __construct($urlGenerator, $bbcodeGenerator)
    {
        $this->urlGenerator = $urlGenerator;
        $this->bbcodeGenerator = $bbcodeGenerator;
    }


    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('img_direct_url', function(Image $image) {
                return $this->urlGenerator->getDirectUrl($image);
            }),

            new \Twig_SimpleFunction('img_view_url', function(Image $image) {
                return $this->urlGenerator->getViewUrl($image);
            }),

            new \Twig_SimpleFunction('img_delete_url', function(Image $image) {
                return $this->urlGenerator->getDeleteUrl($image);
            }),

            new \Twig_SimpleFunction('bb_url', function($url, $content) {
                return $this->bbcodeGenerator->url($url, $content);
            }),

            new \Twig_SimpleFunction('bb_img', function($src) {
                return $this->bbcodeGenerator->img($src);
            }),
        ];
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'backend_extension';
    }
}