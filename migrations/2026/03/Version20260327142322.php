<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260327142322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // dummy data for testing pagination and reply counts
        //DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND)
        $this->addSql("INSERT INTO comments (name, email, comment, date_created, date_updated) VALUES 
            ('Alice', 'alice@example.com', 'Parent 1', DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND), DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND)),
            ('Bob', 'bob@example.com', 'Parent 2', DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND), DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND)),
            ('Charlie', 'charlie@example.com', 'Parent 3', DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND), DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND)),
            ('David', 'david@example.com', 'Parent 4', DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND), DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND)),
            ('Eve', 'eve@example.com', 'Parent 5', DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND), DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND)),
            ('Frank', 'frank@example.com', 'Parent 6', DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND), DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND)),
            ('Grace', 'grace@example.com', 'Parent 7', DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND), DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND)),
            ('Heidi', 'heidi@example.com', 'Parent 8', DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND), DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND)),
            ('Ivan', 'ivan@example.com', 'Parent 9', DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND), DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND)),
            ('Judy', 'judy@example.com', 'Parent 10', DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND), DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND)),
            ('Mallory', 'mallory@example.com', 'Parent 11', DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND), DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND)),
            ('Niaj', 'niaj@example.com', 'Parent 12', DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND), DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND)),
            ('Oscar', 'oscar@example.com', 'Parent 13', DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND), DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND)),
            ('Peggy', 'peggy@example.com', 'Parent 14', DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND), DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND)),
            ('Sybil', 'sybil@example.com', 'Parent 15', DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND), DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND))
        ");

        $this->addSql("
            INSERT INTO comments (name, email, comment, parent_comment_id, date_created, date_updated)
            SELECT 
                CONCAT('Replier_', n.num),
                CONCAT('reply', n.num, '@example.com'),
                CONCAT('Generic reply number ', n.num),
                p.id,
                DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND),
                DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 2592000) SECOND)
            FROM (
                SELECT id FROM comments 
                WHERE parent_comment_id IS NULL 
                ORDER BY id ASC LIMIT 15
            ) AS p
            CROSS JOIN (
                -- Generates a virtual table of 15 rows to multiply by our 15 parents
                SELECT 1 AS num UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 
                UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10
                UNION SELECT 11 UNION SELECT 12 UNION SELECT 13 UNION SELECT 14 UNION SELECT 15
            ) AS n
        ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("TRUNCATE TABLE comments");

    }
}
