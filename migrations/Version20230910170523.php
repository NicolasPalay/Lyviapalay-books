<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230910170523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE authlog (id INT AUTO_INCREMENT NOT NULL, auth_attempt_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ipusers VARCHAR(255) DEFAULT NULL, email_entered VARCHAR(255) NOT NULL, is_successful_auth TINYINT(1) NOT NULL, end_of_black_listing DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_remember_mr_auth TINYINT(1) NOT NULL, de_authencated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', start_of_black_listing DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE authlog');
    }
}
