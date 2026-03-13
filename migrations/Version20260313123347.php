<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260313123347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bus (id INT AUTO_INCREMENT NOT NULL, nom LONGTEXT NOT NULL, capacite INT NOT NULL, etat LONGTEXT NOT NULL, trajet_id INT DEFAULT NULL, INDEX IDX_2F566B69D12A823 (trajet_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE bus ADD CONSTRAINT FK_2F566B69D12A823 FOREIGN KEY (trajet_id) REFERENCES trajets (id)');
        $this->addSql('ALTER TABLE point ADD CONSTRAINT FK_B7A5F3248D271404 FOREIGN KEY (id_trajet_id) REFERENCES trajets (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bus DROP FOREIGN KEY FK_2F566B69D12A823');
        $this->addSql('DROP TABLE bus');
        $this->addSql('ALTER TABLE point DROP FOREIGN KEY FK_B7A5F3248D271404');
    }
}
