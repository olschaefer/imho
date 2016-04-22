<?php


namespace BackendBundle\Service\Id;


class Local implements IdService {
    /** @var  Generator */
    public $generator;

    public function __construct(Generator $generator) {
        $this->generator = $generator;
    }

    public function getId() {
        return $this->generator->generate();
    }
}