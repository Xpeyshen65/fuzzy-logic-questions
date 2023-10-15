<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231008100055_fill_tables extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Заполняет таблицы данными';
    }

    public function up(Schema $schema): void
    {
        $this->connection->executeStatement(
            <<<'SQL'
            insert into questions (id, text) values
                (1, '1 + 1 = '),
                (2, '2 + 2 = '),
                (3, '3 + 3 = '),
                (4, '4 + 4 = '),
                (5, '5 + 5 = '),
                (6, '6 + 6 = '),
                (7, '7 + 7 = '),
                (8, '8 + 8 = '),
                (9, '9 + 9 = '),
                (10, '10 + 10 = ');
            SQL,
        );

        $this->connection->executeStatement(
            <<<'SQL'
            insert into answers (id, text, question_id, is_right) values
                (1, '3', 1, false),
                (2, '2', 1, true),
                (3, '0', 1, false),

                (4, '4', 2, true),
                (5, '3 + 1', 2, true),
                (6, '10', 2, false),

                (7, '1 + 5', 3, true),
                (8, '1', 3, false),
                (9, '6', 3, true),
                (10, '2 + 4', 3, true),
                
                (11, '8', 4, true),
                (12, '4', 4, false),
                (13, '0', 4, false),
                (14, '0 + 8', 4, true),
                
                (15, '6', 5, false),
                (16, '18', 5, false),
                (17, '10', 5, true),
                (18, '9', 5, false),
                (19, '0', 5, false),
                
                (20, '3', 6, false),
                (21, '9', 6, false),
                (22, '0', 6, false),
                (23, '12', 6, true),
                (24, '5 + 7', 6, true),
                
                (25, '5', 7, false),
                (26, '14', 7, true),
                
                (27, '16', 8, true),
                (28, '12', 8, false),
                (29, '9', 8, false),
                (30, '5', 8, false),
                
                (31, '18', 9, true),
                (32, '9', 9, false),
                (33, '17 + 1', 9, true),
                (34, '2 + 16', 9, true),
                
                (35, '0', 10, false),
                (36, '2', 10, false),
                (37, '8', 10, false),
                (38, '20', 10, true);
            SQL,
        );
    }

    public function down(Schema $schema): void
    {
        $this->connection->executeStatement(
            <<<'SQL'
            truncate table questions;
            SQL
        );

        $this->connection->executeStatement(
            <<<'SQL'
            truncate table answers;
            SQL
        );
    }
}
