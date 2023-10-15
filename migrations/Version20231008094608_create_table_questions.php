<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20231008094608_create_table_questions extends AbstractMigration
{
    private const TABLE_NAME = 'questions';

    public function getDescription(): string
    {
        return 'Добавляет таблицу с вопросами';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable(self::TABLE_NAME);
        $column = $table->addColumn('id', Types::INTEGER);
        $column->setAutoincrement(true);
        $table->addColumn('text', Types::STRING);
        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable(self::TABLE_NAME);
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
