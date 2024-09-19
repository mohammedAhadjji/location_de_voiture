<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240912155227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories ADD sitting_generale_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF346687F389DF5 FOREIGN KEY (sitting_generale_id) REFERENCES sitting_generale (id)');
        $this->addSql('CREATE INDEX IDX_3AF346687F389DF5 ON categories (sitting_generale_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF346687F389DF5');
        $this->addSql('DROP INDEX IDX_3AF346687F389DF5 ON categories');
        $this->addSql('ALTER TABLE categories DROP sitting_generale_id');
    }
}
