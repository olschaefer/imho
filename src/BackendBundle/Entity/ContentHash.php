<?php


namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 *
 * @ORM\Table("content_hash")
 * @ORM\Entity(repositoryClass="BackendBundle\Entity\Repository\ContentHashRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ContentHash
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="content_hash", type="string", length=32, options={"fixed":true})
     */
    private $contentHash;

    /**
     * @var string
     * @ORM\Column(name="file_id", type="string", length=36, options={"fixed":true})
     */
    private $fileId;

    /**
     * Bucket id in database
     * @var int
     * @ORM\Column(name="bucket", type="integer", nullable=false)
     */
    private $bucket;

    /**
     * @var Image
     */
    private $image;

    /**
     * @return string
     */
    public function getContentHash()
    {
        return $this->contentHash;
    }

    /**
     * @param string $contentHash
     */
    public function setContentHash($contentHash)
    {
        $this->contentHash = $contentHash;
    }

    /**
     * @return mixed
     */
    public function getFileId()
    {
        return $this->fileId;
    }

    /**
     * @param mixed $fileId
     */
    public function setFileId($fileId)
    {
        $this->fileId = $fileId;
    }

    /**
     * @return int
     */
    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * @param int $bucket
     */
    public function setBucket($bucket)
    {
        $this->bucket = $bucket;
    }

    public function getShardingKey()
    {
        return $this->getContentHash();
    }

    /**
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param Image $image
     */
    public function setImage($image)
    {
        $this->image  = $image;
        $this->fileId = $image->getId();

        if ($image->getFile()) {
            $this->setContentHash(md5_file($image->getFile()->getRealPath()));
        }
    }
}
