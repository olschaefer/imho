<?php

namespace BackendBundle\Service\Id;

/**
 * Interface of an ID generation service
 * @package BackendBundle\IdService\IdService
 */
interface IdService {
    /**
     * Returns new ID obtained from generator
     * @return string
     */
    public function getId();
}