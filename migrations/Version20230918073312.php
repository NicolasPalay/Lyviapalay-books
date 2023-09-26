<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230918073312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog ADD dedication_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143ABD8EC32 FOREIGN KEY (dedication_id) REFERENCES decication (id)');
        $this->addSql('CREATE INDEX IDX_C0155143ABD8EC32 ON blog (dedication_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C0155143ABD8EC32');
        $this->addSql('DROP INDEX IDX_C0155143ABD8EC32 ON blog');
        $this->addSql('ALTER TABLE blog DROP dedication_id');
    }
}
