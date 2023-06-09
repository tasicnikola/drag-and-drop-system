<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230531205622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'This migration creates Space and Desk entity, with realtions';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE desks (guid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', space CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(64) NOT NULL, position JSON NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_BFDA65425E237E06 (name), INDEX IDX_BFDA65422972C13A (space), PRIMARY KEY(guid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spaces (guid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(64) NOT NULL, dimensions JSON NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_DD2B64785E237E06 (name), PRIMARY KEY(guid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE desks ADD CONSTRAINT FK_BFDA65422972C13A FOREIGN KEY (space) REFERENCES spaces (guid)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE desks DROP FOREIGN KEY FK_BFDA65422972C13A');
        $this->addSql('DROP TABLE desks');
        $this->addSql('DROP TABLE spaces');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
