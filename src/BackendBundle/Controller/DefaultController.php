<?php


namespace BackendBundle\Controller;

use BackendBundle\Entity\Image;
use BackendBundle\Service\Image\ActionChain;
use Shardman\Symfony\Bundle\Service\ShardedRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;

class DefaultController extends Controller
{
    /**
     * Render an upload form
     */
    public function indexAction()
    {
        return $this->render('BackendBundle:Default:index.html.twig');
    }

    public function viewAction($originalId)
    {
        return $this->render('BackendBundle:Default:view.html.twig', [
            'image' => $this->loadOriginal($originalId),
        ]);
    }

    public function deleteAction($originalId, Request $request)
    {
        $original = $this->loadOriginal($originalId);
        $validKey = $original->getKey();
        $original->setKey(null);

        $form = $this->createFormBuilder($original)
                     ->add('key', 'text', [
                         'constraints' => [
                             new NotBlank(),
                             new EqualTo(['value' => $validKey , 'message' => 'Invalid key']),
                         ],
                     ])
                     ->add('delete', 'submit', ['label' => 'Delete image'])
                     ->getForm();

        $form->handleRequest($request);
        if ($form->isValid() && $this->get('backend.image_manager')->deleteAll($original)) {
            return $this->redirectToRoute('backend_index');
        }

        return $this->render('BackendBundle:Default:delete.html.twig', [
            'deleteForm' => $form->createView(),
            'image'      => $original,
        ]);
    }

    /**
     * @param $originalId
     * @return Image
     */
    protected function loadOriginal($originalId)
    {
        $original = $this->get('backend.image_manager')->loadOriginal($originalId);

        if (!$original) {
            throw $this->createNotFoundException('Image not found');
        }

        return $original;
    }

    /**
     * @return ShardedRegistry
     */
    protected function getShardedRegistry()
    {
        return $this->get('backend.sharded_registry');
    }
}