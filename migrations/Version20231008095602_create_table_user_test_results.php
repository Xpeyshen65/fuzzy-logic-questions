<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use OpsWay\Doctrine\DBAL\Types\Types as ExtraTypes;
use Doctrine\Migrations\AbstractMigration;

final class Version20231008095602_create_table_user_test_results extends AbstractMigration
{
    public const TABLE_NAME = 'user_test_results';

    public function getDescription(): string
    {
        return 'Добавляет таблицу с результатами тестов пользователей';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable(self::TABLE_NAME);
        $table->addColumn('user_id', Types::STRING);
        $table->addColumn('question_ids', ExtraTypes::ARRAY_INT);
        $table->addColumn('answer_ids', ExtraTypes::ARRAY_INT);
        $table->addColumn('created_at', Types::DATETIME_IMMUTABLE);
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
