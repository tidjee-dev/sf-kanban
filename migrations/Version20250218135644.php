<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218135644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE boards (id VARCHAR(22) NOT NULL, owner_id VARCHAR(22) NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_F3EE4D137E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lists (id VARCHAR(22) NOT NULL, board_id VARCHAR(22) NOT NULL, title VARCHAR(255) NOT NULL, position INT NOT NULL, is_completed TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8269FA5E7EC5785 (board_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tasks (id VARCHAR(22) NOT NULL, list_id VARCHAR(22) NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, position INT NOT NULL, is_completed TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_505865973DAE168B (list_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id VARCHAR(22) NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE boards ADD CONSTRAINT FK_F3EE4D137E3C61F9 FOREIGN KEY (owner_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE lists ADD CONSTRAINT FK_8269FA5E7EC5785 FOREIGN KEY (board_id) REFERENCES boards (id)');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_505865973DAE168B FOREIGN KEY (list_id) REFERENCES lists (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boards DROP FOREIGN KEY FK_F3EE4D137E3C61F9');
        $this->addSql('ALTER TABLE lists DROP FOREIGN KEY FK_8269FA5E7EC5785');
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_505865973DAE168B');
        $this->addSql('DROP TABLE boards');
        $this->addSql('DROP TABLE lists');
        $this->addSql('DROP TABLE tasks');
        $this->addSql('DROP TABLE users');
    }
}
