<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251209183520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appointment (id INT AUTO_INCREMENT NOT NULL, date_heure DATETIME NOT NULL, statut VARCHAR(50) NOT NULL, patient_id INT NOT NULL, doctor_id INT NOT NULL, INDEX IDX_FE38F8446B899279 (patient_id), INDEX IDX_FE38F84487F4FB17 (doctor_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE prescription (id INT AUTO_INCREMENT NOT NULL, contenu LONGTEXT NOT NULL, fichier_pdf VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, appointment_id INT NOT NULL, UNIQUE INDEX UNIQ_1FBFB8D9E5B533F9 (appointment_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, commentaire LONGTEXT NOT NULL, note INT NOT NULL, created_at DATETIME NOT NULL, patient_id INT NOT NULL, doctor_id INT NOT NULL, INDEX IDX_794381C66B899279 (patient_id), INDEX IDX_794381C687F4FB17 (doctor_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE specialty (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F8446B899279 FOREIGN KEY (patient_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F84487F4FB17 FOREIGN KEY (doctor_id) REFERENCES docteur (id)');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D9E5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C66B899279 FOREIGN KEY (patient_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C687F4FB17 FOREIGN KEY (doctor_id) REFERENCES docteur (id)');
        $this->addSql('ALTER TABLE docteur ADD specialty_id INT NOT NULL');
        $this->addSql('ALTER TABLE docteur ADD CONSTRAINT FK_83A7A4399A353316 FOREIGN KEY (specialty_id) REFERENCES specialty (id)');
        $this->addSql('CREATE INDEX IDX_83A7A4399A353316 ON docteur (specialty_id)');
        $this->addSql('ALTER TABLE user ADD roles JSON NOT NULL, DROP role, CHANGE phone phone VARCHAR(20) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F8446B899279');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F84487F4FB17');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D9E5B533F9');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C66B899279');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C687F4FB17');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('DROP TABLE prescription');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE specialty');
        $this->addSql('ALTER TABLE docteur DROP FOREIGN KEY FK_83A7A4399A353316');
        $this->addSql('DROP INDEX IDX_83A7A4399A353316 ON docteur');
        $this->addSql('ALTER TABLE docteur DROP specialty_id');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON `user`');
        $this->addSql('ALTER TABLE `user` ADD role LONGTEXT NOT NULL, DROP roles, CHANGE phone phone VARCHAR(255) NOT NULL');
    }
}
