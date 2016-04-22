<?php


namespace BackendBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ImageActionChainCompilerPass. Creates action chain from tagged services
 * @package BackendBundle\DependencyInjection\Compiler
 */
class ImageActionChainCompilerPass implements CompilerPassInterface
{

    private $chainServiceId = '';
    private $tag     = '';

    function __construct($chainServiceId, $tag)
    {
        $this->chainServiceId = $chainServiceId;
        $this->tag = $tag;
    }

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->chainServiceId)) {
            return;
        }

        $definition     = $container->getDefinition($this->chainServiceId);
        $taggedServices = $container->findTaggedServiceIds($this->tag);
        foreach ($taggedServices as $id => $tags) {
            if (count($tags) !== 1) {
                throw new \LogicException("Service can't be tagged twice with the {$this->tag} tag.");
            }

            $attributes = reset($tags);
            if (!isset($attributes['position'])) {
                $definition->addMethodCall(
                    'addAction',
                    array(new Reference($id))
                );
            } else {
                list($method, $targetLabel) = explode('.', $attributes['position']);
                $definition->addMethodCall(
                    $method,
                    array($targetLabel, new Reference($id))
                );
            }
        }

        $definition->addMethodCall('finalize');
    }
}