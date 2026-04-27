<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260427094927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course_period (id INT AUTO_INCREMENT NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, school_year_id INT DEFAULT NULL, INDEX IDX_6B80DF83D2EECC3F (school_year_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE instructor (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_31FC43DDA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE instructor_module (instructor_id INT NOT NULL, module_id INT NOT NULL, INDEX IDX_739CF9748C4FC193 (instructor_id), INDEX IDX_739CF974AFC2B591 (module_id), PRIMARY KEY (instructor_id, module_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE intervention (id INT AUTO_INCREMENT NOT NULL, title TINYTEXT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, remotely TINYINT NOT NULL, course_period_id INT DEFAULT NULL, intervention_type_id INT DEFAULT NULL, module_id INT DEFAULT NULL, INDEX IDX_D11814AB6E2A3A72 (course_period_id), INDEX IDX_D11814AB8EA2F8F6 (intervention_type_id), INDEX IDX_D11814ABAFC2B591 (module_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE intervention_instructor (intervention_id INT NOT NULL, instructor_id INT NOT NULL, INDEX IDX_730139D08EAE3863 (intervention_id), INDEX IDX_730139D08C4FC193 (instructor_id), PRIMARY KEY (intervention_id, instructor_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE intervention_type (id INT AUTO_INCREMENT NOT NULL, name LONGTEXT NOT NULL, description LONGTEXT NOT NULL, color LONGTEXT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, code TINYTEXT NOT NULL, name TINYTEXT NOT NULL, description LONGTEXT DEFAULT NULL, hours_count INT NOT NULL, capstone_project TINYINT NOT NULL, parent_id INT DEFAULT NULL, teaching_block_id INT DEFAULT NULL, INDEX IDX_C242628727ACA70 (parent_id), INDEX IDX_C24262867845236 (teaching_block_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE school_year (id INT AUTO_INCREMENT NOT NULL, year TINYTEXT NOT NULL, saison TINYTEXT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE teaching_block (id INT AUTO_INCREMENT NOT NULL, code TINYTEXT NOT NULL, name TINYTEXT NOT NULL, description LONGTEXT DEFAULT NULL, hours_count DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_4A04950E77153098 (code), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE course_period ADD CONSTRAINT FK_6B80DF83D2EECC3F FOREIGN KEY (school_year_id) REFERENCES school_year (id)');
        $this->addSql('ALTER TABLE instructor ADD CONSTRAINT FK_31FC43DDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE instructor_module ADD CONSTRAINT FK_739CF9748C4FC193 FOREIGN KEY (instructor_id) REFERENCES instructor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE instructor_module ADD CONSTRAINT FK_739CF974AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814AB6E2A3A72 FOREIGN KEY (course_period_id) REFERENCES course_period (id)');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814AB8EA2F8F6 FOREIGN KEY (intervention_type_id) REFERENCES intervention_type (id)');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814ABAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE intervention_instructor ADD CONSTRAINT FK_730139D08EAE3863 FOREIGN KEY (intervention_id) REFERENCES intervention (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intervention_instructor ADD CONSTRAINT FK_730139D08C4FC193 FOREIGN KEY (instructor_id) REFERENCES instructor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C242628727ACA70 FOREIGN KEY (parent_id) REFERENCES module (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C24262867845236 FOREIGN KEY (teaching_block_id) REFERENCES teaching_block (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_period DROP FOREIGN KEY FK_6B80DF83D2EECC3F');
        $this->addSql('ALTER TABLE instructor DROP FOREIGN KEY FK_31FC43DDA76ED395');
        $this->addSql('ALTER TABLE instructor_module DROP FOREIGN KEY FK_739CF9748C4FC193');
        $this->addSql('ALTER TABLE instructor_module DROP FOREIGN KEY FK_739CF974AFC2B591');
        $this->addSql('ALTER TABLE intervention DROP FOREIGN KEY FK_D11814AB6E2A3A72');
        $this->addSql('ALTER TABLE intervention DROP FOREIGN KEY FK_D11814AB8EA2F8F6');
        $this->addSql('ALTER TABLE intervention DROP FOREIGN KEY FK_D11814ABAFC2B591');
        $this->addSql('ALTER TABLE intervention_instructor DROP FOREIGN KEY FK_730139D08EAE3863');
        $this->addSql('ALTER TABLE intervention_instructor DROP FOREIGN KEY FK_730139D08C4FC193');
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C242628727ACA70');
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C24262867845236');
        $this->addSql('DROP TABLE course_period');
        $this->addSql('DROP TABLE instructor');
        $this->addSql('DROP TABLE instructor_module');
        $this->addSql('DROP TABLE intervention');
        $this->addSql('DROP TABLE intervention_instructor');
        $this->addSql('DROP TABLE intervention_type');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE school_year');
        $this->addSql('DROP TABLE teaching_block');
        $this->addSql('DROP TABLE user');
    }
}
