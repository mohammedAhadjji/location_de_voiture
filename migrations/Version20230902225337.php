<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230902225337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blogs (id INT AUTO_INCREMENT NOT NULL, auth_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT NULL, INDEX IDX_F41BCA708082819C (auth_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blogs ADD CONSTRAINT FK_F41BCA708082819C FOREIGN KEY (auth_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blogs DROP FOREIGN KEY FK_F41BCA708082819C');
        $this->addSql('DROP TABLE blogs');
    }
}
