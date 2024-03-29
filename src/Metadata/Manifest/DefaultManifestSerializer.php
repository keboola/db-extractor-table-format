<?php

declare(strict_types=1);

namespace Keboola\DbExtractor\TableResultFormat\Metadata\Manifest;

use Keboola\Datatype\Definition\Common;
use Keboola\Datatype\Definition\DefinitionInterface;
use Keboola\Datatype\Definition\GenericStorage;
use Keboola\DbExtractor\TableResultFormat\Metadata\ValueObject\Column;
use Keboola\DbExtractor\TableResultFormat\Metadata\ValueObject\Table;

class DefaultManifestSerializer implements ManifestSerializer
{
    public function serializeTable(Table $table): array
    {
        $values = [
            'name' => $table->getName(),
            'sanitizedName' => $table->getSanitizedName(),
            'description' => $table->hasDescription() ? $table->getDescription() : null,
            'schema' => $table->hasSchema() ? $table->getSchema() : null,
            'catalog' => $table->hasCatalog() ? $table->getCatalog() : null,
            'tablespaceName' => $table->hasTablespaceName() ? $table->getTablespaceName() : null,
            'owner' => $table->hasOwner() ? $table->getOwner() : null,
            'type' => $table->hasType() ? $table->getType() : null,
            'rowCount' => $table->hasRowCount() ? $table->getRowCount() : null,
            'datatype.backend' => $table->hasDatatypeBackend() ? $table->getDatatypeBackend() : null,
        ];

        $metadata = [];
        foreach ($values as $key => $value) {
            if ($value === null) {
                // Skip null value
                continue;
            }

            $metadata[] = [
                'key' => 'KBC.' . $key,
                'value' => $value,
            ];
        }

        return $metadata;
    }

    public function serializeColumn(Column $column): array
    {
        // Datatype metadata
        $options = [
            'type' => $column->getType(),
            'length' => $column->hasLength() ? $column->getLength() : null,
            'nullable' => $column->hasNullable() ? $column->isNullable() : null,
            'default' => $column->hasDefault() ? (string) $column->getDefault() : null,
        ];
        $options = array_filter($options, fn($value) => $value !== null); // remove null values
        $datatype = $this->columnToDatatype($column, $options);
        $columnMetadata = $datatype->toMetadata();

        // Non-datatype metadata
        $nonDatatypeMetadata = [
            'sourceName' => $column->getName(),
            'sanitizedName' => $column->getSanitizedName(),
            'primaryKey' => $column->isPrimaryKey(),
            'uniqueKey' => $column->isUniqueKey(),
            'ordinalPosition' => $column->hasOrdinalPosition() ? $column->getOrdinalPosition() : null,
            'autoIncrement' => $column->isAutoIncrement() ?: null,
            'autoIncrementValue' => $column->hasAutoIncrementValue() ? $column->getAutoIncrementValue() : null,
            'description' => $column->hasDescription() ? $column->getDescription() : null,
        ];

        // Foreign key
        if ($column->hasForeignKey()) {
            $fk = $column->getForeignKey();
            $nonDatatypeMetadata['foreignKey'] = true;
            $nonDatatypeMetadata['foreignKeyName'] = $fk->hasName() ? $fk->getName() : null;
            $nonDatatypeMetadata['foreignKeyRefSchema'] = $fk->hasRefSchema() ? $fk->getRefSchema() : null;
            $nonDatatypeMetadata['foreignKeyRefTable'] = $fk->getRefTable();
            $nonDatatypeMetadata['foreignKeyRefColumn'] = $fk->getRefColumn();
        }

        foreach ($nonDatatypeMetadata as $key => $value) {
            if ($value === null) {
                // Skip null value
                continue;
            }

            $columnMetadata[] = [
                'key' => 'KBC.' . $key,
                'value' => $value,
            ];
        }

        // Constraints
        foreach ($column->getConstraints() as $constraint) {
            $columnMetadata[] = [
                'key' => 'KBC.constraintName',
                'value' => $constraint,
            ];
        }

        return $columnMetadata;
    }

    protected function columnToDatatype(Column $column, array $options): Common
    {
        return new GenericStorage($column->getType(), $options);
    }
}
