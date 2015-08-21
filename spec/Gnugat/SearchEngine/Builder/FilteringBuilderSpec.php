<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\SearchEngine\Builder;

use Gnugat\SearchEngine\Builder\FilteringBuilderStrategy;
use Gnugat\SearchEngine\Criteria\Filtering;
use Gnugat\SearchEngine\ResourceDefinition;
use Gnugat\SearchEngine\Builder\QueryBuilder;
use PhpSpec\ObjectBehavior;

class FilteringBuilderSpec extends ObjectBehavior
{
    const FIELD = 'field';
    const VALUE = 'value';

    function it_sorts_registered_strategies(
        Filtering $filtering,
        FilteringBuilderStrategy $middlePriority,
        FilteringBuilderStrategy $lowestPriority,
        FilteringBuilderStrategy $highestPriority,
        QueryBuilder $queryBuilder,
        ResourceDefinition $resourceDefinition
    ) {
        $filtering->getFields()->willReturn(array(self::FIELD => self::VALUE));

        $middlePriority->supports($resourceDefinition, self::FIELD, self::VALUE)->willReturn(true);
        $lowestPriority->supports($resourceDefinition, self::FIELD, self::VALUE)->willReturn(true);
        $highestPriority->supports($resourceDefinition, self::FIELD, self::VALUE)->willReturn(true);

        $middlePriority->build($queryBuilder, $resourceDefinition, self::FIELD, self::VALUE)->shouldNotBeCalled();
        $lowestPriority->build($queryBuilder, $resourceDefinition, self::FIELD, self::VALUE)->shouldNotBeCalled();
        $highestPriority->build($queryBuilder, $resourceDefinition, self::FIELD, self::VALUE)->shouldBeCalled();

        $this->add($middlePriority, 10);
        $this->add($lowestPriority, 0);
        $this->add($highestPriority, 20);

        $this->build($queryBuilder, $resourceDefinition, $filtering);
    }

    function it_uses_the_appropriate_filtering_builder_strategy(
        Filtering $filtering,
        FilteringBuilderStrategy $inappropriate,
        FilteringBuilderStrategy $appropriate,
        FilteringBuilderStrategy $anotherInappropriate,
        QueryBuilder $queryBuilder,
        ResourceDefinition $resourceDefinition
    ) {
        $filtering->getFields()->willReturn(array(self::FIELD => self::VALUE));

        $inappropriate->supports($resourceDefinition, self::FIELD, self::VALUE)->willReturn(false);
        $appropriate->supports($resourceDefinition, self::FIELD, self::VALUE)->willReturn(true);
        $anotherInappropriate->supports($resourceDefinition, self::FIELD, self::VALUE)->shouldNotBeCalled();

        $inappropriate->build($queryBuilder, $resourceDefinition, self::FIELD, self::VALUE)->shouldNotBeCalled();
        $appropriate->build($queryBuilder, $resourceDefinition, self::FIELD, self::VALUE)->shouldBeCalled();
        $anotherInappropriate->build($queryBuilder, $resourceDefinition, self::FIELD, self::VALUE)->shouldNotBeCalled();

        $this->add($inappropriate);
        $this->add($appropriate);
        $this->add($anotherInappropriate);

        $this->build($queryBuilder, $resourceDefinition, $filtering);
    }
}