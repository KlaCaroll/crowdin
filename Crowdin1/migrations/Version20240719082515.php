<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240719082515 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE langues (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nom VARCHAR(255) NOT NULL, niveau VARCHAR(255) DEFAULT NULL, INDEX IDX_119D3659A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE traductions (id INT AUTO_INCREMENT NOT NULL, source_id INT NOT NULL, contenu LONGTEXT NOT NULL, langue VARCHAR(255) NOT NULL, traducteur INT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C3CC68D2953C1C61 (source_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE langues ADD CONSTRAINT FK_119D3659A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE traductions ADD CONSTRAINT FK_C3CC68D2953C1C61 FOREIGN KEY (source_id) REFERENCES sources (id)');
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A49D86650F');
        $this->addSql('DROP INDEX IDX_5C93B3A49D86650F ON projects');
        $this->addSql('ALTER TABLE projects ADD languetraduction1 VARCHAR(255) NOT NULL, ADD languetraduction2 VARCHAR(255) DEFAULT NULL, ADD languetraduction3 VARCHAR(255) DEFAULT NULL, DROP slug, DROP emailcreator, CHANGE user_id_id user_id INT NOT NULL, CHANGE user_email langueoriginale VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A4A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_5C93B3A4A76ED395 ON projects (user_id)');
        $this->addSql('ALTER TABLE sources DROP traduction, CHANGE clée clef VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE users ADD prenom VARCHAR(255) NOT NULL, CHANGE profil profil VARCHAR(30) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE langues DROP FOREIGN KEY FK_119D3659A76ED395');
        $this->addSql('ALTER TABLE traductions DROP FOREIGN KEY FK_C3CC68D2953C1C61');
        $this->addSql('DROP TABLE langues');
        $this->addSql('DROP TABLE traductions');
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A4A76ED395');
        $this->addSql('DROP INDEX IDX_5C93B3A4A76ED395 ON projects');
        $this->addSql('ALTER TABLE projects ADD user_email VARCHAR(255) NOT NULL, ADD slug VARCHAR(255) DEFAULT NULL, ADD emailcreator VARCHAR(255) DEFAULT NULL, DROP langueoriginale, DROP languetraduction1, DROP languetraduction2, DROP languetraduction3, CHANGE user_id user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A49D86650F FOREIGN KEY (user_id_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_5C93B3A49D86650F ON projects (user_id_id)');
        $this->addSql('ALTER TABLE sources ADD traduction VARCHAR(255) DEFAULT NULL, CHANGE clef clée VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE users DROP prenom, CHANGE profil profil VARCHAR(20) NOT NULL');
    }
}
