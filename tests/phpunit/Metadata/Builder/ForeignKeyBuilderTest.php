<?php

declare(strict_types=1);

namespace Keboola\DbExtractor\TableResultFormat\Tests\Metadata\Builder;

use Keboola\DbExtractor\TableResultFormat\Metadata\Builder\ForeignKeyBuilder;
use Keboola\DbExtractor\TableResultFormat\Metadata\ValueObject\ForeignKey;

class ForeignKeyBuilderTest extends BaseBuilderTest
{
    public function createBuilder(array $additionalRequiredProperties = []): ForeignKeyBuilder
    {
        return ForeignKeyBuilder::create($additionalRequiredProperties);
    }

    public function getAllProperties(): array
    {
        return [
            'name',
            'refSchema',
            'refTable',
            'refColumn',
        ];
    }

    public function getAlwaysRequiredProperties(): array
    {
        return ForeignKeyBuilder::ALWAYS_REQUIRED_PROPERTIES;
    }

    public function getOptionalRequiredProperties(): array
    {
        return ForeignKeyBuilder::OPTIONAL_REQUIRED_PROPERTIES;
    }

    public function getNullableProperties(): array
    {
        return [];
    }

    public function getEmptyStringNotAllowedProperties(): array
    {
        return [
            'name',
            'refSchema',
            'refTable',
            'refColumn',
        ];
    }

    public function getEmptyStringConvertToNullProperties(): array
    {
        return [];
    }

    public function getDefaultValues(): array
    {
        return [];
    }

    public function getSetCallbacks(): array
    {
        return [
            'name' => function (ForeignKeyBuilder $builder, $v) {
                return $builder->setName($v);
            },
            'refSchema' => function (ForeignKeyBuilder $builder, $v) {
                return $builder->setRefSchema($v);
            },
            'refTable' => function (ForeignKeyBuilder $builder, $v) {
                return $builder->setRefTable($v);
            },
            'refColumn' => function (ForeignKeyBuilder $builder, $v) {
                return $builder->setRefColumn($v);
            },
        ];
    }

    public function getHasCallbacks(): array
    {
        return [
            'name' => function (ForeignKey $column) {
                return $column->hasName();
            },
            'refSchema' => function (ForeignKey $column) {
                return $column->hasRefSchema();
            },
        ];
    }

    public function getGetCallbacks(): array
    {
        return [
            'name' => function (ForeignKey $column) {
                return $column->getName();
            },
            'refSchema' => function (ForeignKey $column) {
                return $column->getRefSchema();
            },
            'refTable' => function (ForeignKey $column) {
                return $column->getRefTable();
            },
            'refColumn' => function (ForeignKey $column) {
                return $column->getRefColumn();
            },
        ];
    }

    public function getValidInputs(): array
    {
        return [
            [
                'name' => 'name',
                'refSchema' => 'schema',
                'refTable' => 'table',
                'refColumn' => 'column',
            ],
        ];
    }
}
