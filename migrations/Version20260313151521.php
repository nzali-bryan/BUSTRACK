<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260313151521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bus ADD CONSTRAINT FK_2F566B69D12A823 FOREIGN KEY (trajet_id) REFERENCES trajets (id)');
        $this->addSql('DROP INDEX IDX_B7A5F3248D271404 ON point');
        $this->addSql('ALTER TABLE point CHANGE id_trajet_id trajet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE point ADD CONSTRAINT FK_B7A5F324D12A823 FOREIGN KEY (trajet_id) REFERENCES trajets (id)');
        $this->addSql('CREATE INDEX IDX_B7A5F324D12A823 ON point (trajet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bus DROP FOREIGN KEY FK_2F566B69D12A823');
        $this->addSql('ALTER TABLE point DROP FOREIGN KEY FK_B7A5F324D12A823');
        $this->addSql('DROP INDEX IDX_B7A5F324D12A823 ON point');
        $this->addSql('ALTER TABLE point CHANGE trajet_id id_trajet_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_B7A5F3248D271404 ON point (id_trajet_id)');
    }
}
