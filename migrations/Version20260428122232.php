<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260428122232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bus ADD temps_restant INT DEFAULT NULL, CHANGE `current_time` `current_time` DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE bus ADD CONSTRAINT FK_2F566B69D12A823 FOREIGN KEY (trajet_id) REFERENCES trajets (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE bus ADD CONSTRAINT FK_2F566B697EE5403C FOREIGN KEY (administrateur_id) REFERENCES administrateur (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432451BDEFF FOREIGN KEY (trajets_id) REFERENCES trajets (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C4327EE5403C FOREIGN KEY (administrateur_id) REFERENCES administrateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE point ADD CONSTRAINT FK_B7A5F324D12A823 FOREIGN KEY (trajet_id) REFERENCES trajets (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bus DROP FOREIGN KEY FK_2F566B69D12A823');
        $this->addSql('ALTER TABLE bus DROP FOREIGN KEY FK_2F566B697EE5403C');
        $this->addSql('ALTER TABLE bus DROP temps_restant, CHANGE `current_time` `current_time` INT DEFAULT NULL');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432451BDEFF');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C4327EE5403C');
        $this->addSql('ALTER TABLE point DROP FOREIGN KEY FK_B7A5F324D12A823');
    }
}
