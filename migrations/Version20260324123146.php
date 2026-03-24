<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260324123146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favoris (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, trajets_id INT NOT NULL, administrateur_id INT DEFAULT NULL, INDEX IDX_8933C432451BDEFF (trajets_id), INDEX IDX_8933C4327EE5403C (administrateur_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432451BDEFF FOREIGN KEY (trajets_id) REFERENCES trajets (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C4327EE5403C FOREIGN KEY (administrateur_id) REFERENCES administrateur (id)');
        $this->addSql('ALTER TABLE bus ADD CONSTRAINT FK_2F566B69D12A823 FOREIGN KEY (trajet_id) REFERENCES trajets (id)');
        $this->addSql('ALTER TABLE bus ADD CONSTRAINT FK_2F566B697EE5403C FOREIGN KEY (administrateur_id) REFERENCES administrateur (id)');
        $this->addSql('ALTER TABLE point ADD CONSTRAINT FK_B7A5F324D12A823 FOREIGN KEY (trajet_id) REFERENCES trajets (id)');
        $this->addSql('ALTER TABLE trajets CHANGE etat etat VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432451BDEFF');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C4327EE5403C');
        $this->addSql('DROP TABLE favoris');
        $this->addSql('ALTER TABLE bus DROP FOREIGN KEY FK_2F566B69D12A823');
        $this->addSql('ALTER TABLE bus DROP FOREIGN KEY FK_2F566B697EE5403C');
        $this->addSql('ALTER TABLE point DROP FOREIGN KEY FK_B7A5F324D12A823');
        $this->addSql('ALTER TABLE trajets CHANGE etat etat VARCHAR(255) DEFAULT \'Actif\' NOT NULL');
    }
}
