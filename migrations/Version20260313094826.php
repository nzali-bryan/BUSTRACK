<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260313094826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE point ADD id_trajet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE point ADD CONSTRAINT FK_B7A5F3248D271404 FOREIGN KEY (id_trajet_id) REFERENCES trajets (id)');
        $this->addSql('CREATE INDEX IDX_B7A5F3248D271404 ON point (id_trajet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE point DROP FOREIGN KEY FK_B7A5F3248D271404');
        $this->addSql('DROP INDEX IDX_B7A5F3248D271404 ON point');
        $this->addSql('ALTER TABLE point DROP id_trajet_id');
    }
}
