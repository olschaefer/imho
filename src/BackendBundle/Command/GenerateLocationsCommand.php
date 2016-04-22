<?php


namespace BackendBundle\Command;


use Shardman\Service\Config\Config;
use Shardman\Service\Config\ConfigProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GenerateLocationsCommand extends Command implements ContainerAwareInterface
{
    /**
     * @var Container
     */
    private $container;

    protected function configure()
    {
        parent::configure();
        $this->setName('generatelocations');
        $this->addArgument('map');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var ConfigProvider $cp */
        $cp = $this->container->get('shard_manager.config_provider');
        /** @var Config $config */
        $config = $cp->getConfig($input->getArgument('map'));

        $ranges = '';
        $shardConfig = $config->getShardConfig();
        foreach ($shardConfig as $shardCfg) {
            foreach ($shardCfg['bucketRanges'] as $br) {
                $ranges .= str_replace(
                    [
                        '{BUCKET_START}',
                        '{BUCKET_END}',
                        '{SHARD_ID}'
                    ],
                    [
                        $br['start'],
                        $br['end'],
                        $shardCfg['id'],
                    ],
                    $this->rangesTemplate
                );

                $ranges .= "\n";
            }
        }


        $defaultShard = reset($shardConfig)['id'];
        $result = str_replace(
            [
                '{RANGES}',
                '{DEFAULT_SHARD_ID}',
            ],
            [
                $ranges,
                $defaultShard
            ],
            $this->template
        );

        $output->writeln(
            $result
        );
    }


    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    private $template =<<<'EOF'
local m, err = ngx.re.match(ngx.var.uri, "^/\\d\\d/(?<bucket>\\d+)/", "ajo");
if m then
    local bucket = tonumber(m["bucket"]);
{RANGES}
else
    return '';
end
EOF;

    private $rangesTemplate =<<<'EOF'
    if bucket >= {BUCKET_START} and bucket <= {BUCKET_END} then
        return '{SHARD_ID}';
    end
EOF;

}