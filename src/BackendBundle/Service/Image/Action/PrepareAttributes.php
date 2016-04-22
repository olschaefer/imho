<?php


namespace BackendBundle\Service\Image\Action;

use BackendBundle\Entity\Image;
use BackendBundle\Service\Id\IdService;
use BackendBundle\Service\Image\ActionContext;
use BackendBundle\Service\Image\BaseAction;
use BackendBundle\Service\PathGenerator;
use Shardman\Service\ShardManager;

/**
 * Class PrepareAttributes. Prepares file for storing, sets all the necessary attributes from UploadedFile
 * @package BackendBundle\Service
 */
class PrepareAttributes extends BaseAction
{
    /**
     * @var IdService
     */
    private $idGenerator;
    /**
     * @var ShardManager
     */
    private $dbShardManager;
    /**
     * @var ShardManager
     */
    private $storageShardManager;
    /**
     * @var PathGenerator
     */
    private $pathGenerator;


    public function __construct(IdService     $idGenerator,
                                ShardManager  $dbShardManager,
                                ShardManager  $storageShardManager,
                                PathGenerator $pathGenerator)
    {
        $this->idGenerator         = $idGenerator;
        $this->dbShardManager      = $dbShardManager;
        $this->storageShardManager = $storageShardManager;
        $this->pathGenerator       = $pathGenerator;
    }
    public function process(ActionContext $context)
    {
        /** @var Image[] $images */
        $images = array_merge([$context->getOriginal()], $context->getVariants());
        foreach ($images as $image) {
            if (!$image->getId()) {
                $image->setId($this->idGenerator->getId());
            }
            $image->setOriginalId($context->getOriginal()->getId());
            $image->setKey($this->idGenerator->getId());

            $dbShardResult = $this->dbShardManager->getByKey($image->getShardingKey());
            $image->setBucket($dbShardResult->getBucket());

            $storageShardResult = $this->storageShardManager->getByKey($image->getShardingKey());
            $image->setStorageBucket($storageShardResult->getBucket());
            $image->setPath($this->generatePath($image));

        }
    }

    public function generatePath(Image $image)
    {
        return $this->pathGenerator->getPath($image);
    }
}