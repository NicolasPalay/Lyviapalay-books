<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230802153121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, url_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture_product (picture_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_CE6CF07FEE45BDBF (picture_id), INDEX IDX_CE6CF07F4584665A (product_id), PRIMARY KEY(picture_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE picture_product ADD CONSTRAINT FK_CE6CF07FEE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE picture_product ADD CONSTRAINT FK_CE6CF07F4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product DROP picture');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE picture_product DROP FOREIGN KEY FK_CE6CF07FEE45BDBF');
        $this->addSql('ALTER TABLE picture_product DROP FOREIGN KEY FK_CE6CF07F4584665A');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE picture_product');
        $this->addSql('ALTER TABLE product ADD picture VARCHAR(255) NOT NULL');
    }
}
