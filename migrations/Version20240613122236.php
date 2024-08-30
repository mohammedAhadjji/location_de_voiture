<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240613122236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonces (id INT AUTO_INCREMENT NOT NULL, voiture_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, descriptions LONGTEXT DEFAULT NULL, prix_locat INT NOT NULL, voiture_locale VARCHAR(255) NOT NULL, brand_voiture VARCHAR(255) NOT NULL, occur INT DEFAULT NULL, INDEX IDX_CB988C6F181A8BA (voiture_id), FULLTEXT INDEX annonces_ids (title, descriptions), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blogs (id INT AUTO_INCREMENT NOT NULL, auth_id INT DEFAULT NULL, categorie_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, slug LONGTEXT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT NULL, INDEX IDX_F41BCA708082819C (auth_id), INDEX IDX_F41BCA70BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, img_brand VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_3AF34668727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE config (id INT AUTO_INCREMENT NOT NULL, clientid VARCHAR(255) DEFAULT NULL, storekey VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images_blogs (id INT AUTO_INCREMENT NOT NULL, blogs_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, INDEX IDX_D9232BCC89C05BBC (blogs_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images_voiture (id INT AUTO_INCREMENT NOT NULL, voiture_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, INDEX IDX_769F9F6D181A8BA (voiture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, locale VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE modele (id INT AUTO_INCREMENT NOT NULL, brand_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_1002855844F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, client_id VARCHAR(255) NOT NULL, fullname VARCHAR(255) DEFAULT NULL, companyname VARCHAR(255) DEFAULT NULL, amount VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL, pays VARCHAR(255) DEFAULT NULL, province VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, reference VARCHAR(255) DEFAULT NULL, lighne_order JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', status VARCHAR(255) DEFAULT NULL, source_update VARCHAR(255) DEFAULT NULL, user_id VARCHAR(255) DEFAULT NULL, date DATETIME DEFAULT NULL, date_update DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reduction (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, reduction INT NOT NULL, is_active TINYINT(1) NOT NULL, min_day INT NOT NULL, max_day INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, voiture_id INT DEFAULT NULL, title VARCHAR(100) NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, description LONGTEXT DEFAULT NULL, all_day TINYINT(1) DEFAULT NULL, background_color VARCHAR(7) NOT NULL, border_color VARCHAR(7) NOT NULL, text_color VARCHAR(7) NOT NULL, INDEX IDX_42C8495519EB6921 (client_id), INDEX IDX_42C84955181A8BA (voiture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE season (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, taux INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sitting_generale (id INT AUTO_INCREMENT NOT NULL, logo_img VARCHAR(255) DEFAULT NULL, favicon_img VARCHAR(255) DEFAULT NULL, theme_color VARCHAR(7) DEFAULT NULL, footer_adrss VARCHAR(255) DEFAULT NULL, footer_mail VARCHAR(255) DEFAULT NULL, footer_phone VARCHAR(255) DEFAULT NULL, custome_listing_option TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, name VARCHAR(100) NOT NULL, firstname VARCHAR(100) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voiture (id INT AUTO_INCREMENT NOT NULL, modele_id INT DEFAULT NULL, type_id INT DEFAULT NULL, location_id INT DEFAULT NULL, annee DATE NOT NULL, desponibilite TINYINT(1) DEFAULT NULL, INDEX IDX_E9E2810FAC14B70A (modele_id), INDEX IDX_E9E2810FC54C8C93 (type_id), INDEX IDX_E9E2810F64D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6F181A8BA FOREIGN KEY (voiture_id) REFERENCES voiture (id)');
        $this->addSql('ALTER TABLE blogs ADD CONSTRAINT FK_F41BCA708082819C FOREIGN KEY (auth_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE blogs ADD CONSTRAINT FK_F41BCA70BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668727ACA70 FOREIGN KEY (parent_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE images_blogs ADD CONSTRAINT FK_D9232BCC89C05BBC FOREIGN KEY (blogs_id) REFERENCES blogs (id)');
        $this->addSql('ALTER TABLE images_voiture ADD CONSTRAINT FK_769F9F6D181A8BA FOREIGN KEY (voiture_id) REFERENCES voiture (id)');
        $this->addSql('ALTER TABLE modele ADD CONSTRAINT FK_1002855844F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495519EB6921 FOREIGN KEY (client_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955181A8BA FOREIGN KEY (voiture_id) REFERENCES voiture (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810FAC14B70A FOREIGN KEY (modele_id) REFERENCES modele (id)');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810FC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810F64D218E FOREIGN KEY (location_id) REFERENCES location (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6F181A8BA');
        $this->addSql('ALTER TABLE blogs DROP FOREIGN KEY FK_F41BCA708082819C');
        $this->addSql('ALTER TABLE blogs DROP FOREIGN KEY FK_F41BCA70BCF5E72D');
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF34668727ACA70');
        $this->addSql('ALTER TABLE images_blogs DROP FOREIGN KEY FK_D9232BCC89C05BBC');
        $this->addSql('ALTER TABLE images_voiture DROP FOREIGN KEY FK_769F9F6D181A8BA');
        $this->addSql('ALTER TABLE modele DROP FOREIGN KEY FK_1002855844F5D008');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495519EB6921');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955181A8BA');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY FK_E9E2810FAC14B70A');
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY FK_E9E2810FC54C8C93');
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY FK_E9E2810F64D218E');
        $this->addSql('DROP TABLE annonces');
        $this->addSql('DROP TABLE blogs');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE config');
        $this->addSql('DROP TABLE images_blogs');
        $this->addSql('DROP TABLE images_voiture');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE modele');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE reduction');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE season');
        $this->addSql('DROP TABLE sitting_generale');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE voiture');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
