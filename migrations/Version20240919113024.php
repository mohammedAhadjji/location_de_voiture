<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240919113024 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blogs DROP FOREIGN KEY FK_F41BCA70BCF5E72D');
        $this->addSql('DROP INDEX IDX_F41BCA70BCF5E72D ON blogs');
        $this->addSql('ALTER TABLE blogs DROP categorie_id');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A89C05BBC');
        $this->addSql('DROP INDEX IDX_5F9E962A89C05BBC ON comments');
        $this->addSql('ALTER TABLE comments ADD blog_id INT NOT NULL, DROP blogs_id');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962ADAE07E97 FOREIGN KEY (blog_id) REFERENCES blogs (id)');
        $this->addSql('CREATE INDEX IDX_5F9E962ADAE07E97 ON comments (blog_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blogs ADD categorie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blogs ADD CONSTRAINT FK_F41BCA70BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categories (id)');
        $this->addSql('CREATE INDEX IDX_F41BCA70BCF5E72D ON blogs (categorie_id)');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962ADAE07E97');
        $this->addSql('DROP INDEX IDX_5F9E962ADAE07E97 ON comments');
        $this->addSql('ALTER TABLE comments ADD blogs_id INT DEFAULT NULL, DROP blog_id');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A89C05BBC FOREIGN KEY (blogs_id) REFERENCES blogs (id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A89C05BBC ON comments (blogs_id)');
    }
}
