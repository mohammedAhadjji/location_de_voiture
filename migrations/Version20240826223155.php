<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240826223155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE location_brand (location_id INT NOT NULL, brand_id INT NOT NULL, INDEX IDX_6A0EBC7D64D218E (location_id), INDEX IDX_6A0EBC7D44F5D008 (brand_id), PRIMARY KEY(location_id, brand_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE location_brand ADD CONSTRAINT FK_6A0EBC7D64D218E FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE location_brand ADD CONSTRAINT FK_6A0EBC7D44F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location_brand DROP FOREIGN KEY FK_6A0EBC7D64D218E');
        $this->addSql('ALTER TABLE location_brand DROP FOREIGN KEY FK_6A0EBC7D44F5D008');
        $this->addSql('DROP TABLE location_brand');
    }
}
