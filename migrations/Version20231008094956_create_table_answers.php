<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20231008094956_create_table_answers extends AbstractMigration
{
    private const TABLE_NAME = 'answers';

    public function getDescription(): string
    {
        return 'Добавляет таблицу с ответами на вопросы';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable(self::TABLE_NAME);
        $table->addColumn('id', Types::INTEGER);
        $table->addColumn('text', Types::STRING);
        $table->addColumn('question_id', Types::INTEGER);
        $table->addColumn('is_right', Types::BOOLEAN);
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
