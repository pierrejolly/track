<?php

namespace AppBundle\Statistic;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Tag statistic.
 *
 * @author Pierre Jolly <pie.jolly@gmail.com>
 */
class TagStatistic
{
    /**
     * @var ObjectManager
     */
    protected $om;

    /**
     * @param $om ObjectManager
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     *
     */
    public function get(
        ArrayCollection $users = null,
        ArrayCollection $projects = null,
        ArrayCollection $tags = null,
        \DateTime $fromDate = null,
        \DateTime $toDate = null
    ) {
        return $this->om->getRepository('AppBundle:Tag')
            ->get($users, $projects, $tags, $fromDate, $toDate);
    }
}
