<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240919113956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blogs ADD categorie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blogs ADD CONSTRAINT FK_F41BCA70BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categories (id)');
        $this->addSql('CREATE INDEX IDX_F41BCA70BCF5E72D ON blogs (categorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blogs DROP FOREIGN KEY FK_F41BCA70BCF5E72D');
        $this->addSql('DROP INDEX IDX_F41BCA70BCF5E72D ON blogs');
        $this->addSql('ALTER TABLE blogs DROP categorie_id');
    }
}
