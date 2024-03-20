<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240319204209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie_projet (categorie_id INT NOT NULL, projet_id INT NOT NULL, INDEX IDX_D6333690BCF5E72D (categorie_id), INDEX IDX_D6333690C18272 (projet_id), PRIMARY KEY(categorie_id, projet_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categorie_projet ADD CONSTRAINT FK_D6333690BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categorie_projet ADD CONSTRAINT FK_D6333690C18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie_projet DROP FOREIGN KEY FK_D6333690BCF5E72D');
        $this->addSql('ALTER TABLE categorie_projet DROP FOREIGN KEY FK_D6333690C18272');
        $this->addSql('DROP TABLE categorie_projet');
    }
}
