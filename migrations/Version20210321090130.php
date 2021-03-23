<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210321090130 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, status_id_id INT NOT NULL, client_name VARCHAR(50) NOT NULL, client_phone VARCHAR(25) DEFAULT NULL, _order VARCHAR(400) NOT NULL, notes VARCHAR(1000) DEFAULT NULL, price INT NOT NULL, paid INT DEFAULT NULL, shipping_details VARCHAR(1000) NOT NULL, shipping INT NOT NULL, order_created_at DATETIME NOT NULL, instagram VARCHAR(255) NOT NULL, ttn VARCHAR(255) NOT NULL, INDEX IDX_E52FFDEE881ECFA7 (status_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orser_statuses (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE881ECFA7 FOREIGN KEY (status_id_id) REFERENCES orser_statuses (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE881ECFA7');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE orser_statuses');
    }
}
