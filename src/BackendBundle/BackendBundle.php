<?php

namespace BackendBundle;

use BackendBundle\DependencyInjection\Compiler\ImageActionChainCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BackendBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new ImageActionChainCompilerPass(
            'backend.image.action_chain',
            'image.action_chain_item'
        ));
    }
}