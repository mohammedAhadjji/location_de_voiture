<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230904152701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE voiture ADD location_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810F64D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('CREATE INDEX IDX_E9E2810F64D218E ON voiture (location_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY FK_E9E2810F64D218E');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP INDEX IDX_E9E2810F64D218E ON voiture');
        $this->addSql('ALTER TABLE voiture DROP location_id');
    }
}
