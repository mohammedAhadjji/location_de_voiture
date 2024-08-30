<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240823150340 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image_profile ADD users_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image_profile ADD CONSTRAINT FK_1447FDD367B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_1447FDD367B3B43D ON image_profile (users_id)');
        $this->addSql('ALTER TABLE users ADD photo VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image_profile DROP FOREIGN KEY FK_1447FDD367B3B43D');
        $this->addSql('DROP INDEX IDX_1447FDD367B3B43D ON image_profile');
        $this->addSql('ALTER TABLE image_profile DROP users_id');
        $this->addSql('ALTER TABLE users DROP photo');
    }
}
