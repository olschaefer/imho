<?php

namespace AdminBundle\Service;

use BackendBundle\Entity\Image;
use Shardman\Symfony\Bundle\Service\ShardedRegistry;

class StatsCollector
{
    /**
     * @var ShardedRegistry
     */
    private $sr;

    public function __construct(ShardedRegistry $sr)
    {
        $this->sr = $sr;
    }

    public function getStats()
    {
        $stats = $this->sr->getShardManager()->execAll(
        function($shardId) {
            return $this->sr->getRepository(Image::class, $shardId)->countTotals();
        },
        function ($stats) {
            $stats['summary'] = array_reduce($stats, function ($carry, $item) {
                foreach ($item as $k => $v) {
                    if (!isset($carry[$k])) {
                        $carry[$k] = $item[$k];
                    } else {
                        $carry[$k] += $item[$k];
                    }
                }

                return $carry;
            }, []);

            return $stats;
        });



        return $stats;
    }
}