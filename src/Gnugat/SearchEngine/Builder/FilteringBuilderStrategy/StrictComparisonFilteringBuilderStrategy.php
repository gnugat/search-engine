<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\SearchEngine\Builder\FilteringBuilderStrategy;

use Gnugat\SearchEngine\Builder\FilteringBuilderStrategy;
use Gnugat\SearchEngine\Builder\QueryBuilder;
use Gnugat\SearchEngine\ResourceDefinition;
use Gnugat\SearchEngine\Service\TypeSanitizer;

class StrictComparisonFilteringBuilderStrategy implements FilteringBuilderStrategy
{
    /**
     * @var TypeSanitizer
     */
    private $typeSanitizer;

    /**
     * @param TypeSanitizer $typeSanitizer
     */
    public function __construct(TypeSanitizer $typeSanitizer)
    {
        $this->typeSanitizer = $typeSanitizer;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ResourceDefinition $resourceDefinition, $field, $value)
    {
        return $resourceDefinition->hasField($field);
    }

    /**
     * {@inheritdoc}
     */
    public function build(QueryBuilder $queryBuilder, ResourceDefinition $resourceDefinition, $field, $value)
    {
        $parameterName = ":$field";
        $type = $resourceDefinition->getFieldType($field);
        $value = $this->typeSanitizer->sanitize($value, $type);
        $queryBuilder->addWhere("$field = $parameterName");
        $queryBuilder->addParameter($parameterName, $value, $type);
    }
}
