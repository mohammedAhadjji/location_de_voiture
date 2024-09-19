<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240919095553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brand ADD sitting_generale_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE brand ADD CONSTRAINT FK_1C52F9587F389DF5 FOREIGN KEY (sitting_generale_id) REFERENCES sitting_generale (id)');
        $this->addSql('CREATE INDEX IDX_1C52F9587F389DF5 ON brand (sitting_generale_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brand DROP FOREIGN KEY FK_1C52F9587F389DF5');
        $this->addSql('DROP INDEX IDX_1C52F9587F389DF5 ON brand');
        $this->addSql('ALTER TABLE brand DROP sitting_generale_id');
    }
}
