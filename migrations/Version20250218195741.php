<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218195741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE boards (id VARCHAR(22) NOT NULL, owner_id VARCHAR(22) NOT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , PRIMARY KEY(id), CONSTRAINT FK_F3EE4D137E3C61F9 FOREIGN KEY (owner_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_F3EE4D137E3C61F9 ON boards (owner_id)');
        $this->addSql('CREATE TABLE lists (id VARCHAR(22) NOT NULL, board_id VARCHAR(22) NOT NULL, title VARCHAR(255) NOT NULL, position INTEGER NOT NULL, is_completed BOOLEAN NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , PRIMARY KEY(id), CONSTRAINT FK_8269FA5E7EC5785 FOREIGN KEY (board_id) REFERENCES boards (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_8269FA5E7EC5785 ON lists (board_id)');
        $this->addSql('CREATE TABLE tasks (id VARCHAR(22) NOT NULL, list_id VARCHAR(22) NOT NULL, title VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, position INTEGER NOT NULL, is_completed BOOLEAN NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , PRIMARY KEY(id), CONSTRAINT FK_505865973DAE168B FOREIGN KEY (list_id) REFERENCES lists (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_505865973DAE168B ON tasks (list_id)');
        $this->addSql('CREATE TABLE users (id VARCHAR(22) NOT NULL, username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, is_active BOOLEAN NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON users (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE boards');
        $this->addSql('DROP TABLE lists');
        $this->addSql('DROP TABLE tasks');
        $this->addSql('DROP TABLE users');
    }
}
