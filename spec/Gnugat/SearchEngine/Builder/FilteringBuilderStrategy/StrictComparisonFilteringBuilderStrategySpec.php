<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\SearchEngine\Builder\FilteringBuilderStrategy;

use Gnugat\SearchEngine\Builder\QueryBuilder;
use Gnugat\SearchEngine\ResourceDefinition;
use Gnugat\SearchEngine\Service\TypeSanitizer;
use PhpSpec\ObjectBehavior;

class StrictComparisonFilteringBuilderStrategySpec extends ObjectBehavior
{
    function let(TypeSanitizer $typeSanitizer)
    {
        $this->beConstructedWith($typeSanitizer);
    }

    function it_is_a_filtering_builder_strategy()
    {
        $this->shouldImplement('Gnugat\SearchEngine\Builder\FilteringBuilderStrategy');
    }

    function it_supports_existing_fields(ResourceDefinition $resourceDefinition)
    {
        $resourceDefinition->hasField('field')->willReturn(true);

        $this->supports($resourceDefinition, 'field', 'value')->shouldBe(true);
    }

    function it_builds_in_statements_with_single_values(
        QueryBuilder $queryBuilder,
        ResourceDefinition $resourceDefinition,
        TypeSanitizer $typeSanitizer
    ) {
        $resourceDefinition->getFieldType('field')->willReturn(ResourceDefinition::TYPE_BOOLEAN);
        $typeSanitizer->sanitize('true', ResourceDefinition::TYPE_BOOLEAN)->willReturn(true);
        $queryBuilder->addWhere('field = :field')->shouldBeCalled();
        $queryBuilder->addParameter(':field', true, ResourceDefinition::TYPE_BOOLEAN)->shouldBeCalled();

        $this->build($queryBuilder, $resourceDefinition, 'field', 'true');
    }
}
