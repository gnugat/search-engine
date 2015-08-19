<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\SearchEngine;

use Gnugat\SearchEngine\Criteria\Embeding;
use Gnugat\SearchEngine\Criteria\Filtering;
use Gnugat\SearchEngine\Criteria\Paginating;

class Criteria
{
    /**
     * @var string
     */
    private $resourceName;

    /**
     * @var Embeding
     */
    private $embeding;

    /**
     * @var Filtering
     */
    private $filtering;

    /**
     * @var Paginating
     */
    private $paginating;

    /**
     * @var array
     */
    private $orderings;

    private function __construct()
    {
    }

    /**
     * @param string     $resourceName
     * @param Embeding   $embeding
     * @param Filtering  $filtering
     * @param Paginating $paginating
     * @param array      $orderings
     */
    public static function forSearchEngine(
        $resourceName,
        Embeding $embeding,
        Filtering $filtering,
        Paginating $paginating,
        array $orderings
    ) {
        $criteria = new self();

        $criteria->resourceName = $resourceName;
        $criteria->embeding = $embeding;
        $criteria->filtering = $filtering;
        $criteria->paginating = $paginating;
        $criteria->orderings = $orderings;

        return $criteria;
    }

    /**
     * @return string
     */
    public function getResourceName()
    {
        return $this->resourceName;
    }

    /**
     * @return Embeding
     */
    public function getEmbeding()
    {
        return $this->embeding;
    }

    /**
     * @return Filtering
     */
    public function getFiltering()
    {
        return $this->filtering;
    }

    /**
     * @return Paginating
     */
    public function getPaginating()
    {
        return $this->paginating;
    }

    /**
     * @return array
     */
    public function getOrderings()
    {
        return $this->orderings;
    }
}
