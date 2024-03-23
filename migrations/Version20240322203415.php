<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240322203415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projet_employe_poste ADD employe_id INT DEFAULT NULL, ADD projet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE projet_employe_poste ADD CONSTRAINT FK_1C5744961B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE projet_employe_poste ADD CONSTRAINT FK_1C574496C18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('CREATE INDEX IDX_1C5744961B65292 ON projet_employe_poste (employe_id)');
        $this->addSql('CREATE INDEX IDX_1C574496C18272 ON projet_employe_poste (projet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projet_employe_poste DROP FOREIGN KEY FK_1C5744961B65292');
        $this->addSql('ALTER TABLE projet_employe_poste DROP FOREIGN KEY FK_1C574496C18272');
        $this->addSql('DROP INDEX IDX_1C5744961B65292 ON projet_employe_poste');
        $this->addSql('DROP INDEX IDX_1C574496C18272 ON projet_employe_poste');
        $this->addSql('ALTER TABLE projet_employe_poste DROP employe_id, DROP projet_id');
    }
}
