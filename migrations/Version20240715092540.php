<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240715092540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appel_offre (id INT AUTO_INCREMENT NOT NULL, appel_offre_type_id INT DEFAULT NULL, moyen_livraison_id INT DEFAULT NULL, pays_id INT DEFAULT NULL, organisme_demandeur_id INT DEFAULT NULL, appel_offre_devis INT NOT NULL, appel_offre_objet LONGTEXT NOT NULL, appel_offre_date_remise DATE NOT NULL, appel_offre_retire INT NOT NULL, appel_offre_participation INT NOT NULL, appel_offre_etat INT NOT NULL, UNIQUE INDEX UNIQ_BC56FD4781C4D085 (appel_offre_devis), INDEX IDX_BC56FD479815F3FC (appel_offre_type_id), INDEX IDX_BC56FD472985EF25 (moyen_livraison_id), INDEX IDX_BC56FD47A6E44244 (pays_id), INDEX IDX_BC56FD47E4993DCE (organisme_demandeur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appel_offre_type (id INT AUTO_INCREMENT NOT NULL, appel_offre_type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_6F65DEA76F65DEA7 (appel_offre_type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bailleurfond (bailleurFondId INT AUTO_INCREMENT NOT NULL, bailleurFondLibelle VARCHAR(255) DEFAULT NULL, bailleurFondAcronyme VARCHAR(10) DEFAULT NULL, PRIMARY KEY(bailleurFondId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (categorieId INT AUTO_INCREMENT NOT NULL, categorieLibelle VARCHAR(255) NOT NULL, categorieShort VARCHAR(255) NOT NULL, categorieCodeRef VARCHAR(255) NOT NULL, categorieCodeCouleur VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_497DD634147E737F (categorieLibelle), UNIQUE INDEX UNIQ_497DD63464E548EA (categorieShort), UNIQUE INDEX UNIQ_497DD634DD113742 (categorieCodeRef), UNIQUE INDEX UNIQ_497DD634E1BB45B (categorieCodeCouleur), PRIMARY KEY(categorieId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, nature_client_id INT DEFAULT NULL, personne_contact VARCHAR(255) NOT NULL, client_raison_sociale VARCHAR(255) NOT NULL, client_adresse VARCHAR(255) NOT NULL, client_telephone VARCHAR(20) NOT NULL, client_email VARCHAR(255) NOT NULL, INDEX IDX_C74404554AAD8E0 (nature_client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE continent (continentId INT AUTO_INCREMENT NOT NULL, continentName VARCHAR(254) DEFAULT NULL, PRIMARY KEY(continentId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devises (devisesId INT AUTO_INCREMENT NOT NULL, devisesLibelle VARCHAR(254) DEFAULT NULL, devisesAcronyme VARCHAR(10) DEFAULT NULL, PRIMARY KEY(devisesId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employe (id INT AUTO_INCREMENT NOT NULL, nationalite_id INT DEFAULT NULL, situation_familiale_id INT DEFAULT NULL, poste_id INT DEFAULT NULL, personne_contact VARCHAR(255) NOT NULL, employe_date_naissance DATE NOT NULL, employe_adresse VARCHAR(255) NOT NULL, employe_principale_qualification LONGTEXT NOT NULL, employe_formation VARCHAR(255) NOT NULL, employe_affiliation_des_associations_group_pro VARCHAR(255) NOT NULL, INDEX IDX_F804D3B91B063272 (nationalite_id), INDEX IDX_F804D3B9F11CEA43 (situation_familiale_id), INDEX IDX_F804D3B9A0905086 (poste_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employe_langue (employe_id INT NOT NULL, langue_id INT NOT NULL, INDEX IDX_FD716E771B65292 (employe_id), INDEX IDX_FD716E772AADBACD (langue_id), PRIMARY KEY(employe_id, langue_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employe_education (id INT AUTO_INCREMENT NOT NULL, employe_id INT DEFAULT NULL, employe_education_nature_etudes VARCHAR(255) NOT NULL, employe_education_etablissement VARCHAR(255) NOT NULL, employe_education_diplomes VARCHAR(255) NOT NULL, employe_education_annee_obtention DATE NOT NULL, INDEX IDX_1A2458841B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employe_experience (id INT AUTO_INCREMENT NOT NULL, employe_id INT DEFAULT NULL, employe_experience_poste VARCHAR(255) DEFAULT NULL, employe_experience_oragnisme_employeur VARCHAR(255) DEFAULT NULL, employe_experience_periode VARCHAR(255) DEFAULT NULL, employe_experience_fonction_occupe VARCHAR(255) DEFAULT NULL, INDEX IDX_87591BC41B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE environnementdeveloppement (environnementDeveloppementId INT AUTO_INCREMENT NOT NULL, environnementDeveloppementLibelle VARCHAR(254) DEFAULT NULL, environnementDeveloppementDescription VARCHAR(1000) DEFAULT NULL, PRIMARY KEY(environnementDeveloppementId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE langue (id INT AUTO_INCREMENT NOT NULL, langue_nom VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_9357758EE2452E91 (langue_nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lieu (lieuId INT AUTO_INCREMENT NOT NULL, lieuLibelle VARCHAR(255) NOT NULL, paysId INT DEFAULT NULL, UNIQUE INDEX UNIQ_2F577D5918F8200D (lieuLibelle), INDEX IDX_2F577D5935569A8D (paysId), PRIMARY KEY(lieuId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE methodologie (methodologieId INT AUTO_INCREMENT NOT NULL, methodologieLibelle VARCHAR(254) DEFAULT NULL, methodologieDescription VARCHAR(1000) DEFAULT NULL, PRIMARY KEY(methodologieId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE moyen_livraison (id INT AUTO_INCREMENT NOT NULL, moyen_livraison VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_348289E9348289E9 (moyen_livraison), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nationalite (id INT AUTO_INCREMENT NOT NULL, nationalite_libelle VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_9EC4D73F6585FED (nationalite_libelle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nature_client (id INT AUTO_INCREMENT NOT NULL, nature_client VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_F348A682F348A682 (nature_client), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, appel_offre_id INT DEFAULT NULL, message VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, isread TINYINT(1) NOT NULL, INDEX IDX_BF5476CA308E35F8 (appel_offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisme_demandeur (id INT AUTO_INCREMENT NOT NULL, organisme_demandeur_libelle VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_2F340854EE67A811 (organisme_demandeur_libelle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pays (paysId INT AUTO_INCREMENT NOT NULL, paysLibelle VARCHAR(254) NOT NULL, paysCapitale VARCHAR(254) NOT NULL, continentId INT DEFAULT NULL, UNIQUE INDEX UNIQ_349F3CAE23588E17 (paysLibelle), UNIQUE INDEX UNIQ_349F3CAE41BCE223 (paysCapitale), INDEX IDX_349F3CAEA08155E4 (continentId), PRIMARY KEY(paysId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poste (id INT AUTO_INCREMENT NOT NULL, poste_nom VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_7C890FAB3C18B63 (poste_nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projet (id INT AUTO_INCREMENT NOT NULL, lieu_id INT DEFAULT NULL, client_id INT DEFAULT NULL, projet_libelle VARCHAR(255) NOT NULL, projet_description LONGTEXT NOT NULL, projet_reference VARCHAR(255) NOT NULL, projet_date_demarrage DATE NOT NULL, projet_date_achevement DATE NOT NULL, projet_url_fonctionnel VARCHAR(255) NOT NULL, projet_description_service_effectivement_rendus LONGTEXT NOT NULL, INDEX IDX_50159CA96AB213CC (lieu_id), INDEX IDX_50159CA919EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projet_employe_poste (id INT AUTO_INCREMENT NOT NULL, employe_id INT DEFAULT NULL, projet_id INT DEFAULT NULL, poste_id INT DEFAULT NULL, duree VARCHAR(255) NOT NULL, INDEX IDX_1C5744961B65292 (employe_id), INDEX IDX_1C574496C18272 (projet_id), INDEX IDX_1C574496A0905086 (poste_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projet_preuve (id INT AUTO_INCREMENT NOT NULL, projet_id INT DEFAULT NULL, projet_preuve_libelle VARCHAR(255) NOT NULL, INDEX IDX_12449B6C18272 (projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE secteur_activite (secteurActiviteId INT AUTO_INCREMENT NOT NULL, secteurActiviteLibelle VARCHAR(254) DEFAULT NULL, secteurActiviteDescription VARCHAR(254) DEFAULT NULL, PRIMARY KEY(secteurActiviteId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE situation_familiale (id INT AUTO_INCREMENT NOT NULL, situation_familiale VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_6C0B418D6C0B418D (situation_familiale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technologie (technologieId INT AUTO_INCREMENT NOT NULL, referenceTechnologieLibelle VARCHAR(254) DEFAULT NULL, referenceTechnologieDescription VARCHAR(1000) DEFAULT NULL, PRIMARY KEY(technologieId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE upload_file (id INT AUTO_INCREMENT NOT NULL, projet_preuve_id INT DEFAULT NULL, file_name VARCHAR(255) NOT NULL, file_path VARCHAR(255) NOT NULL, INDEX IDX_81BB169BA2A570E (projet_preuve_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, reset_token VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE appel_offre ADD CONSTRAINT FK_BC56FD479815F3FC FOREIGN KEY (appel_offre_type_id) REFERENCES appel_offre_type (id)');
        $this->addSql('ALTER TABLE appel_offre ADD CONSTRAINT FK_BC56FD472985EF25 FOREIGN KEY (moyen_livraison_id) REFERENCES moyen_livraison (id)');
        $this->addSql('ALTER TABLE appel_offre ADD CONSTRAINT FK_BC56FD47A6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id)');
        $this->addSql('ALTER TABLE appel_offre ADD CONSTRAINT FK_BC56FD47E4993DCE FOREIGN KEY (organisme_demandeur_id) REFERENCES organisme_demandeur (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404554AAD8E0 FOREIGN KEY (nature_client_id) REFERENCES nature_client (id)');
        $this->addSql('ALTER TABLE employe ADD CONSTRAINT FK_F804D3B91B063272 FOREIGN KEY (nationalite_id) REFERENCES nationalite (id)');
        $this->addSql('ALTER TABLE employe ADD CONSTRAINT FK_F804D3B9F11CEA43 FOREIGN KEY (situation_familiale_id) REFERENCES situation_familiale (id)');
        $this->addSql('ALTER TABLE employe ADD CONSTRAINT FK_F804D3B9A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
        $this->addSql('ALTER TABLE employe_langue ADD CONSTRAINT FK_FD716E771B65292 FOREIGN KEY (employe_id) REFERENCES employe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employe_langue ADD CONSTRAINT FK_FD716E772AADBACD FOREIGN KEY (langue_id) REFERENCES langue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employe_education ADD CONSTRAINT FK_1A2458841B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE employe_experience ADD CONSTRAINT FK_87591BC41B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE lieu ADD CONSTRAINT FK_2F577D5935569A8D FOREIGN KEY (paysId) REFERENCES pays (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA308E35F8 FOREIGN KEY (appel_offre_id) REFERENCES appel_offre (id)');
        $this->addSql('ALTER TABLE pays ADD CONSTRAINT FK_349F3CAEA08155E4 FOREIGN KEY (continentId) REFERENCES continent (continentId)');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA96AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id)');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA919EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE projet_employe_poste ADD CONSTRAINT FK_1C5744961B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE projet_employe_poste ADD CONSTRAINT FK_1C574496C18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE projet_employe_poste ADD CONSTRAINT FK_1C574496A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
        $this->addSql('ALTER TABLE projet_preuve ADD CONSTRAINT FK_12449B6C18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE upload_file ADD CONSTRAINT FK_81BB169BA2A570E FOREIGN KEY (projet_preuve_id) REFERENCES projet_preuve (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appel_offre DROP FOREIGN KEY FK_BC56FD479815F3FC');
        $this->addSql('ALTER TABLE appel_offre DROP FOREIGN KEY FK_BC56FD472985EF25');
        $this->addSql('ALTER TABLE appel_offre DROP FOREIGN KEY FK_BC56FD47A6E44244');
        $this->addSql('ALTER TABLE appel_offre DROP FOREIGN KEY FK_BC56FD47E4993DCE');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404554AAD8E0');
        $this->addSql('ALTER TABLE employe DROP FOREIGN KEY FK_F804D3B91B063272');
        $this->addSql('ALTER TABLE employe DROP FOREIGN KEY FK_F804D3B9F11CEA43');
        $this->addSql('ALTER TABLE employe DROP FOREIGN KEY FK_F804D3B9A0905086');
        $this->addSql('ALTER TABLE employe_langue DROP FOREIGN KEY FK_FD716E771B65292');
        $this->addSql('ALTER TABLE employe_langue DROP FOREIGN KEY FK_FD716E772AADBACD');
        $this->addSql('ALTER TABLE employe_education DROP FOREIGN KEY FK_1A2458841B65292');
        $this->addSql('ALTER TABLE employe_experience DROP FOREIGN KEY FK_87591BC41B65292');
        $this->addSql('ALTER TABLE lieu DROP FOREIGN KEY FK_2F577D5935569A8D');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA308E35F8');
        $this->addSql('ALTER TABLE pays DROP FOREIGN KEY FK_349F3CAEA08155E4');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA96AB213CC');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA919EB6921');
        $this->addSql('ALTER TABLE projet_employe_poste DROP FOREIGN KEY FK_1C5744961B65292');
        $this->addSql('ALTER TABLE projet_employe_poste DROP FOREIGN KEY FK_1C574496C18272');
        $this->addSql('ALTER TABLE projet_employe_poste DROP FOREIGN KEY FK_1C574496A0905086');
        $this->addSql('ALTER TABLE projet_preuve DROP FOREIGN KEY FK_12449B6C18272');
        $this->addSql('ALTER TABLE upload_file DROP FOREIGN KEY FK_81BB169BA2A570E');
        $this->addSql('DROP TABLE appel_offre');
        $this->addSql('DROP TABLE appel_offre_type');
        $this->addSql('DROP TABLE bailleurfond');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE continent');
        $this->addSql('DROP TABLE devises');
        $this->addSql('DROP TABLE employe');
        $this->addSql('DROP TABLE employe_langue');
        $this->addSql('DROP TABLE employe_education');
        $this->addSql('DROP TABLE employe_experience');
        $this->addSql('DROP TABLE environnementdeveloppement');
        $this->addSql('DROP TABLE langue');
        $this->addSql('DROP TABLE lieu');
        $this->addSql('DROP TABLE methodologie');
        $this->addSql('DROP TABLE moyen_livraison');
        $this->addSql('DROP TABLE nationalite');
        $this->addSql('DROP TABLE nature_client');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE organisme_demandeur');
        $this->addSql('DROP TABLE pays');
        $this->addSql('DROP TABLE poste');
        $this->addSql('DROP TABLE projet');
        $this->addSql('DROP TABLE projet_employe_poste');
        $this->addSql('DROP TABLE projet_preuve');
        $this->addSql('DROP TABLE secteur_activite');
        $this->addSql('DROP TABLE situation_familiale');
        $this->addSql('DROP TABLE technologie');
        $this->addSql('DROP TABLE upload_file');
        $this->addSql('DROP TABLE `user`');
    }
}
