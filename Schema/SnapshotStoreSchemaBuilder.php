<?php

declare(strict_types=1);

namespace Andreo\EventSauce\Doctrine\Migration\Schema;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

final readonly class SnapshotStoreSchemaBuilder implements EventSauceSchemaBuilder
{
    public function __construct(private Schema $schema = new Schema())
    {
    }

    public function buildSchema(SchemaMetaDataProvider $schemaMetaDataProvider): Schema
    {
        $table = $this->schema->createTable($schemaMetaDataProvider->getTableName());
        $uuidType = $schemaMetaDataProvider->getUuidType();
        $uuidLength = $schemaMetaDataProvider->getUuidLength();

        $table->addColumn('id', Types::INTEGER, [
            'length' => 20,
            'unsigned' => true,
            'autoincrement' => true,
        ]);
        $table->addColumn('aggregate_root_id', $uuidType, [
            'length' => $uuidLength,
            'fixed' => true,
        ]);
        $table->addColumn('aggregate_root_version', Types::INTEGER, [
            'length' => 20,
            'unsigned' => true,
        ]);
        $table->addColumn('state', Types::STRING, [
            'length' => 16001,
        ]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(
            ['aggregate_root_id', 'aggregate_root_version'],
            'last_snapshot'
        );
        $table->addOption('charset', 'utf8mb4');
        $table->addOption('collation', 'utf8mb4_general_ci');

        return $this->schema;
    }
}