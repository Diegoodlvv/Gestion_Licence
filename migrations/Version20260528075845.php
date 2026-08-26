<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260528075845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout de la table type_contrat et de la relation avec instructor';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE type_contrat (id INT AUTO_INCREMENT NOT NULL, libelle LONGTEXT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE instructor ADD type_contrat_id INT NOT NULL');
        $this->addSql('ALTER TABLE instructor ADD CONSTRAINT FK_31FC43DD520D03A FOREIGN KEY (type_contrat_id) REFERENCES type_contrat (id)');
        $this->addSql('CREATE INDEX IDX_31FC43DD520D03A ON instructor (type_contrat_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE instructor DROP FOREIGN KEY FK_31FC43DD520D03A');
        $this->addSql('DROP INDEX IDX_31FC43DD520D03A ON instructor');
        $this->addSql('ALTER TABLE instructor DROP type_contrat_id');
        $this->addSql('DROP TABLE type_contrat');
    }
}
