<?php


namespace BackendBundle\Tests\DependencyInjection\Compiler;


use BackendBundle\DependencyInjection\Compiler\ImageActionChainCompilerPass;
use BackendBundle\Service\Image\Action;
use BackendBundle\Service\Image\ActionChain;
use BackendBundle\Tests\BaseTest;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class ImageActionChainCompilerPassTest extends BaseTest
{
    protected function getNotCompiledContainer()
    {
        $mockAction = $this->getMockForAbstractClass(Action::class);

        $container = new ContainerBuilder();
        $actionDefinition = new Definition(get_class($mockAction));
        $actionDefinition->addTag('testTag');
        $container->setDefinition('testAction', $actionDefinition);

        $chainDefinition = new Definition(ActionChain::class);
        $container->setDefinition('testChain', $chainDefinition);
        $container->addCompilerPass(new ImageActionChainCompilerPass('testChain', 'testTag'));

        return $container;
    }

    public function testNormalOperation()
    {
        $container = $this->getNotCompiledContainer();
        $container->compile();
        /** @var ActionChain $chain */
        $chain = $container->get('testChain');
        $this->assertSame(1, $chain->count());
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Service can't be tagged twice with the testTag tag.
     */
    public function testLogicException()
    {
        $container = $this->getNotCompiledContainer();
        $container->getDefinition('testAction')->addTag('testTag');
        $container->addCompilerPass(new ImageActionChainCompilerPass('testChain', 'testTag'));
        $container->compile();
    }
}