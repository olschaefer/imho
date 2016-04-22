<?php


namespace BackendBundle\Service;


use BackendBundle\Entity\Image;
use BackendBundle\Service\Id\IdService;
use League\Flysystem\Filesystem;
use Shardman\Symfony\Bundle\Service\ShardedRegistry;
use League\Flysystem\FileNotFoundException;

class ImageManager
{
    /**
     * @var ShardedRegistry
     */
    private $sr;

    /** @var  Filesystem */
    private $storage;

    /** @var IdService */
    private $idGenerator;

    public function __construct(ShardedRegistry $sr,
                                Filesystem $storage,
                                IdService $idGenerator)
    {
        $this->sr                  = $sr;
        $this->storage             = $storage;
        $this->idGenerator         = $idGenerator;
    }

    /**
     * @param $originalId
     * @return Image|null
     */
    public function loadOriginal($originalId)
    {
        $images = $this->sr->findByIds(Image::class, [$originalId => $originalId]);
        return $images ? reset($images) : null;
    }

    public function save(Image $image)
    {
        $em = $this->sr->getManagerBySk($image->getShardingKey());

        try {
            $em->beginTransaction();
            $em->persist($image);
            $em->flush();

            if ($this->storeFile($image)) {
                $em->commit();
            } else {
                $em->rollback();
            }
        } catch(\Exception $e) {
            $em->rollback();
        }

        return $image;
    }

    protected function storeFile(Image $image)
    {
        return $this->storage->put(
            $image->getPath(),
            file_get_contents($image->getFile()->getRealPath())
        );
    }

    protected function deleteFile(Image $image)
    {
        return $this->storage->delete($image->getPath());
    }

    public function deleteAll(Image $original)
    {
        $repo   = $this->sr->getRepositoryBySk(Image::class, $original->getShardingKey());
        $images = $repo->findBy(['originalId' => $original->getOriginalId()]);
        $result = true;
        foreach ($images as $image) {
            $result = $this->delete($image) && $result;
        }

        return $result;
    }

    public function delete(Image $image)
    {
        $em = $this->sr->getManagerBySk($image->getShardingKey());
        try {
            $em->beginTransaction();
            $em->remove($image);
            $em->flush();

            if ($this->deleteFile($image)) {
                $em->commit();
                return true;
            } else {
                $em->rollback();
                return false;
            }

        } catch (FileNotFoundException $e) {
            $em->commit();
            return true;
        } catch (\Exception $e) {
            $em->rollback();
            return false;
        }
    }


    public function getProcessors()
    {
        return [];
    }
}