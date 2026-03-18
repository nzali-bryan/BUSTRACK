<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260318025845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE conducteurs');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_NOM ON administrateur');
        $this->addSql('DROP INDEX UNIQ_2F566B699587B5EA ON bus');
        $this->addSql('ALTER TABLE bus DROP conducteurs_id');
        $this->addSql('ALTER TABLE bus ADD CONSTRAINT FK_2F566B69D12A823 FOREIGN KEY (trajet_id) REFERENCES trajets (id)');
        $this->addSql('ALTER TABLE point ADD CONSTRAINT FK_B7A5F324D12A823 FOREIGN KEY (trajet_id) REFERENCES trajets (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE conducteurs (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, roles JSON NOT NULL, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, telephone INT NOT NULL, direction LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_NOM ON administrateur (nom)');
        $this->addSql('ALTER TABLE bus DROP FOREIGN KEY FK_2F566B69D12A823');
        $this->addSql('ALTER TABLE bus ADD conducteurs_id INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2F566B699587B5EA ON bus (conducteurs_id)');
        $this->addSql('ALTER TABLE point DROP FOREIGN KEY FK_B7A5F324D12A823');
    }
}
