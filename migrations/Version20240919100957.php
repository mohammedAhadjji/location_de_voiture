<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240919100957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location ADD sitting_generale_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB7F389DF5 FOREIGN KEY (sitting_generale_id) REFERENCES sitting_generale (id)');
        $this->addSql('CREATE INDEX IDX_5E9E89CB7F389DF5 ON location (sitting_generale_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB7F389DF5');
        $this->addSql('DROP INDEX IDX_5E9E89CB7F389DF5 ON location');
        $this->addSql('ALTER TABLE location DROP sitting_generale_id');
    }
}
