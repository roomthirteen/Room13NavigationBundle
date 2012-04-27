<?php

namespace Room13\NavigationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Room13\NavigationBundle\Entity\NavigationNode
 * @ORM\Entity
 * @ORM\Table(name="room13_navigation_node")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Tree(type="nested")
 */
class NavigationNode
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $title
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string $tooltip
     *
     * @ORM\Column(name="tooltip", type="string", length=512, nullable=true)
     */
    private $tooltip;

    /**
     * @var string $link
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="link", type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @var string $link
     *
     * @ORM\Column(name="visible_for", type="array", nullable=true)
     */

    private $visibleFor;

    /**
     * @var string $slug
     *
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=255)
     * @Gedmo\TreePathSource
     */
    private $slug;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="_root", type="integer", nullable=true)
     */
    private $root;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="_left", type="integer", nullable=true)
     */
    private $left;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="_right", type="integer", nullable=true)
     */
    private $right;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="_level", type="integer", nullable=true)
     */
    private $level;

    /**
     * @var NavigationNode
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="NavigationNode", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="NavigationNode", mappedBy="parent")
     * @ORM\OrderBy({"left" = "ASC"})
     */
    private $children;



    public function __construct()
    {
        $this->path=null;
        $this->tooltip = null;
        $this->link = null;
        $this->visibleFor = null;
    }



    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set tooltip
     *
     * @param string $tooltip
     */
    public function setTooltip($tooltip)
    {
        $this->tooltip = $tooltip;
    }

    /**
     * Get tooltip
     *
     * @return string 
     */
    public function getTooltip()
    {
        return $this->tooltip;
    }

    /**
     * Set link
     *
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    public function setChildren($children)
    {
        $this->children = $children;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setVisibleFor(array $groups)
    {
        $this->visibleFor=$groups;
    }

    public function getVisibleFor()
    {
        return $this->visibleFor;
    }




    public static function fromArray(\Doctrine\ORM\EntityManager $em, array $def,NavigationNode $parent=null)
    {
        $list = array();
        foreach($def as $nodeDef)
        {
            $title      = $nodeDef[0];
            $link       = isset($nodeDef[1]) ? $nodeDef[1] : '';
            $tooltip    = isset($nodeDef[2]) ? $nodeDef[2] : '';
            $children   = isset($nodeDef[3]) ? $nodeDef[3] : null;
            $userGroups = isset($nodeDef[4]) ? $nodeDef[4] : null;

            $node = new self;

            $node->setTitle($title);
            $node->setLink($link);
            $node->setTooltip($tooltip);

            $em->persist($node);

            if($parent!==null)
            {
                $node->setParent($parent);
            }

            if(is_array($children))
            {
                $list = array_merge($list,self::fromArray($em,$children,$node));
            }

            if(is_array($userGroups))
            {
                $node->setVisibleFor($userGroups);
            }

            $em->flush();

            $list[]=$node;

        }

        return $list;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    public function setLevel($level)
    {
        $this->level = $level;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getPath()
    {

        return $this->path;
    }


}