<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240830095446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image_brand ADD brand_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image_brand ADD CONSTRAINT FK_B177DF144F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('CREATE INDEX IDX_B177DF144F5D008 ON image_brand (brand_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image_brand DROP FOREIGN KEY FK_B177DF144F5D008');
        $this->addSql('DROP INDEX IDX_B177DF144F5D008 ON image_brand');
        $this->addSql('ALTER TABLE image_brand DROP brand_id');
    }
}
