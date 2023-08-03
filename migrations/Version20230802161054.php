<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230802161054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE picture_product DROP FOREIGN KEY FK_CE6CF07F4584665A');
        $this->addSql('ALTER TABLE picture_product DROP FOREIGN KEY FK_CE6CF07FEE45BDBF');
        $this->addSql('DROP TABLE picture_product');
        $this->addSql('ALTER TABLE picture ADD product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F894584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_16DB4F894584665A ON picture (product_id)');
        $this->addSql('ALTER TABLE product ADD picture VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE picture_product (picture_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_CE6CF07F4584665A (product_id), INDEX IDX_CE6CF07FEE45BDBF (picture_id), PRIMARY KEY(picture_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE picture_product ADD CONSTRAINT FK_CE6CF07F4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE picture_product ADD CONSTRAINT FK_CE6CF07FEE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product DROP picture');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F894584665A');
        $this->addSql('DROP INDEX IDX_16DB4F894584665A ON picture');
        $this->addSql('ALTER TABLE picture DROP product_id');
    }
}
