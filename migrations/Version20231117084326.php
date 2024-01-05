<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231117084326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE config (id INT AUTO_INCREMENT NOT NULL, clientid VARCHAR(255) DEFAULT NULL, storekey VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, client_id VARCHAR(255) NOT NULL, fullname VARCHAR(255) NOT NULL, companyname VARCHAR(255) DEFAULT NULL, amount VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, pays VARCHAR(255) NOT NULL, province VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, reference VARCHAR(255) DEFAULT NULL, lighne_order LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', status VARCHAR(255) DEFAULT NULL, source_update VARCHAR(255) NOT NULL, user_id VARCHAR(255) DEFAULT NULL, date DATETIME NOT NULL, date_update DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE redection');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE redection (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, reduction INT NOT NULL, is_active TINYINT(1) NOT NULL, min_day INT NOT NULL, max_day INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE config');
        $this->addSql('DROP TABLE `order`');
    }
}
