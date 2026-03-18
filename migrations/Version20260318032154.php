<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260318032154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrateur ADD telephone NUMERIC(12, 0) DEFAULT NULL');
        $this->addSql('ALTER TABLE bus ADD administrateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bus ADD CONSTRAINT FK_2F566B69D12A823 FOREIGN KEY (trajet_id) REFERENCES trajets (id)');
        $this->addSql('ALTER TABLE bus ADD CONSTRAINT FK_2F566B697EE5403C FOREIGN KEY (administrateur_id) REFERENCES administrateur (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2F566B697EE5403C ON bus (administrateur_id)');
        $this->addSql('ALTER TABLE point ADD CONSTRAINT FK_B7A5F324D12A823 FOREIGN KEY (trajet_id) REFERENCES trajets (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrateur DROP telephone');
        $this->addSql('ALTER TABLE bus DROP FOREIGN KEY FK_2F566B69D12A823');
        $this->addSql('ALTER TABLE bus DROP FOREIGN KEY FK_2F566B697EE5403C');
        $this->addSql('DROP INDEX UNIQ_2F566B697EE5403C ON bus');
        $this->addSql('ALTER TABLE bus DROP administrateur_id');
        $this->addSql('ALTER TABLE point DROP FOREIGN KEY FK_B7A5F324D12A823');
    }
}
