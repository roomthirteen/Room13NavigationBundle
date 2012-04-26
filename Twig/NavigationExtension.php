<?php

namespace Room13\NavigationBundle\Twig;

use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Room13\NavigationBundle\Entity\NavigationNode;
use Symfony\Component\DependencyInjection\Container;

class NavigationExtension extends \Twig_Extension
{
    /**
     * @var Container
     */
    private $container;


    /**
     * @var EntityManager
     */
    private $em;


    /**
     * @var Request
     */
    private $request;

    public function __construct(EntityManager $em, Container $container)
    {
        $this->em = $em;
        $this->container = $container;

    }


    public function listNodes($parentSlug=1)
    {

        $nodeRep = $this->em->getRepository('Room13NavigationBundle:NavigationNode');
        $root = $nodeRep->findOneBySlug($parentSlug);

        if($root===null)
        {
            return array();
        }

        return $this->em
          ->createQueryBuilder()
          ->select('n')
          ->from('Room13NavigationBundle:NavigationNode','n')
          ->where('n.parent = '.$root->getId())
          ->getQuery()->execute();
        ;
    }

    public function isNodeActive(NavigationNode $node)
    {
        if($this->request === null)
        {
            $this->request = $this->container->get('request');
        }

        $path = $this->request->getPathInfo();

        return $path === $node->getLink();
    }

    public function getFunctions()
    {
        return array(
            'room13_navigation_list' => new \Twig_Function_Method($this,'listNodes'),
            'room13_navigation_node_active' => new \Twig_Function_Method($this,'isNodeActive'),
        );
    }

    public function getName()
    {
        return 'room13_navigation';
    }
}