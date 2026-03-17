<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260316142620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrateur ADD username VARCHAR(180) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_NOM ON administrateur (nom)');
        $this->addSql('ALTER TABLE bus ADD CONSTRAINT FK_2F566B69D12A823 FOREIGN KEY (trajet_id) REFERENCES trajets (id)');
        $this->addSql('ALTER TABLE bus ADD CONSTRAINT FK_2F566B699587B5EA FOREIGN KEY (conducteurs_id) REFERENCES conducteurs (id)');
        $this->addSql('ALTER TABLE point ADD CONSTRAINT FK_B7A5F324D12A823 FOREIGN KEY (trajet_id) REFERENCES trajets (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_NOM ON administrateur');
        $this->addSql('ALTER TABLE administrateur DROP username');
        $this->addSql('ALTER TABLE bus DROP FOREIGN KEY FK_2F566B69D12A823');
        $this->addSql('ALTER TABLE bus DROP FOREIGN KEY FK_2F566B699587B5EA');
        $this->addSql('ALTER TABLE point DROP FOREIGN KEY FK_B7A5F324D12A823');
    }
}
