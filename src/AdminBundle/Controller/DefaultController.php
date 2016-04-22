<?php

namespace AdminBundle\Controller;

use AdminBundle\Service\StatsCollector;
use BackendBundle\Entity\Image;
use BackendBundle\Service\ImageManager;
use Shardman\Symfony\Bundle\Service\ShardedRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DefaultController extends Controller
{
    public function indexAction()
    {
        /** @var ShardedRegistry $sr */
        $sr = $this->get('backend.sharded_registry');

        // function to get latest images from every shard
        $fun = function ($shardId) use ($sr) {
            return $sr->getRepository(Image::class, $shardId)->getLatest(20);
        };

        // function to merge separate results from shards
        $finalize = function($result) {
            return array_reduce($result, function($carry, $item) {
                return array_merge($carry, $item);
            }, []);
        };

        /** @var StatsCollector $sc */
        $sc  = $this->get('admin.stats_collector');

        return $this->render('AdminBundle:Default:index.html.twig', [
            'stats'        => $sc->getStats(),
            'latestImages' => $sr->getShardManager()->execAll($fun, $finalize),
        ]);
    }

    public function deleteAction(Request $r)
    {
        $originalId = $r->request->get('originalId');
        /** @var ImageManager $im */
        $im = $this->get('backend.image_manager');
        $original = $im->loadOriginal($originalId);
        if ($original) {
            $im->deleteAll($original);
        }

        return $this->redirectToRoute('admin_homepage');
    }
}
