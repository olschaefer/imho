<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            //new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new BackendBundle\BackendBundle(),
            new Shardman\Symfony\Bundle\ShardmanBundle(),
            new Oneup\FlysystemBundle\OneupFlysystemBundle(),
            new Oneup\UploaderBundle\OneupUploaderBundle(),
            new AdminBundle\AdminBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }

    public function getCacheDir()
    {
        $env = $this->getEnvParameters();
        $key = 'app_cache_dir_prefix';
        return  isset($env[$key]) ? rtrim($env[$key], "/").'/'.$this->environment : parent::getCacheDir();
    }

    public function getLogDir()
    {
        $env = $this->getEnvParameters();
        $key = 'app_log_dir';
        return  isset($env[$key]) ? rtrim($env[$key], '/') : parent::getLogDir();
    }
}
