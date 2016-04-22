<?php

namespace BackendBundle\Entity;

use BackendBundle\Service\Id\Generator\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Image
 *
 * @ORM\Table("image")
 * @ORM\Entity(repositoryClass="BackendBundle\Entity\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Image
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="id", type="string", length=36, options={"fixed":true})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="original_id", type="string", length=36, options={"fixed":true}, nullable=true)
     */
    private $originalId;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=100)
     */
    private $path;

    /**
     * @var integer
     *
     * @ORM\Column(name="size", type="integer")
     */
    private $size;

    /**
     * Secret key to manage this image
     * @var string
     * @ORM\Column(name="key", type="string", length=36, options={"fixed":true})
     */
    private $key;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * Bucket id in database
     * @var int
     * @ORM\Column(name="bucket", type="integer", nullable=false)
     */
    private $bucket;

    /**
     * Bucket id in file storage
     * @var int
     * @ORM\Column(name="storage_bucket", type="integer", nullable=false)
     */
    private $storageBucket;

    /**
     * Exists only right after upload
     * @var File
     */
    private $file;

    /**
     * Field is set by UrlGenerator on every image load.
     * @see Resources/config/services.yml
     * @var
     */
    private $url;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getOriginalId()
    {
        return $this->originalId;
    }

    /**
     * @param mixed $originalId
     */
    public function setOriginalId($originalId)
    {
        $this->originalId = $originalId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Image
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->setCreatedAt(new \DateTime());
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set size
     *
     * @param integer $size
     * @return Image
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer 
     */
    public function getSize()
    {
        return $this->size;
    }


    /**
     * @return int
     */
    public function getShardingKey()
    {
        return $this->getOriginalId();
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
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

    /**
     * @return int
     */
    public function getStorageBucket()
    {
        return $this->storageBucket;
    }

    /**
     * @param int $storageBucket
     */
    public function setStorageBucket($storageBucket)
    {
        $this->storageBucket = $storageBucket;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param File $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    public function isOriginal()
    {
        return $this->getId() === $this->getOriginalId();
    }

}
