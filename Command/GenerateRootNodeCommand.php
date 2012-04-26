<?php
namespace Room13\NavigationBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Translation;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Translation\MessageCatalogue;

use Room13\NavigationBundle\Entity\NavigationNode;


class GenerateRootNodeCommand extends \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand
{


    protected function configure()
    {
        $this
            ->setName('room13:navigation:create-root-node')
            ->setDescription('Creates a new navigation root node for a new navigation tree')
            ->addArgument('title',InputArgument::REQUIRED,'Title of the new node');

    }



    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $node = new NavigationNode();
        $node->setTitle($input->getArgument('title'));

        $em->persist($node);
        $em->flush();
    }

}