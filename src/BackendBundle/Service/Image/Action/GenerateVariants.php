<?php


namespace BackendBundle\Service\Image\Action;


use BackendBundle\Factory\ImageFactory;
use BackendBundle\Service\Image\ActionContext;
use BackendBundle\Service\Image\Action;
use BackendBundle\Service\Image\BaseAction;
use BackendBundle\Service\Image\Variant\Variant;
use Symfony\Component\HttpFoundation\File\File;

class GenerateVariants extends BaseAction
{
    private $variants = [];
    private $imageFactory;

    public function __construct(ImageFactory $imageFactory, array $variants) {
        $this->imageFactory = $imageFactory;
        $this->setVariants($variants);
    }

    public function setVariants(array $variants)
    {
        $this->variants = $variants;
    }

    /**
     * @param ActionContext $context
     * @return void
     */
    public function process(ActionContext $context)
    {
        foreach($this->generate($context->getOriginal()->getFile()) as $id => $variant) {
            $context->addVariant($id, $this->imageFactory->fromFile($variant));
        }
    }

    /**
     * @param File $file
     * @return File[]
     */
    public function generate(File $file)
    {
        /** @var Variant $variant */
        $result = [];
        foreach ($this->variants as $variant) {
            $result[$variant->getId()] = $variant->generate(
                $file,
                $this->createOutfile($file, $variant)
            );
        }

        return $result;
    }

    protected function createOutfile(File $in, Variant $variant)
    {
        $pathInfo = pathinfo($in->getRealPath());
        $path     = $pathInfo['dirname'].'/'.$pathInfo['filename'].'_'.$variant->getId().'.'.$pathInfo['extension'];

        return new File($path, false);
    }
}