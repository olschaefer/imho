<?php


namespace BackendBundle\Service;


class BBCodeGenerator
{
    public function url($url, $content)
    {
        return "[URL={$url}]{$content}[/URL]";
    }

    public function img($src)
    {
        return "[IMG]{$src}[/IMG]";
    }
}