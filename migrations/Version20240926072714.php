<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240926072714 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contacto ADD provincia_id INT NOT NULL, ADD provincia_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE contacto ADD CONSTRAINT FK_2741493C4E7121AF FOREIGN KEY (provincia_id) REFERENCES provincia (id)');
        $this->addSql('ALTER TABLE contacto ADD CONSTRAINT FK_2741493C1FF88B6A FOREIGN KEY (provincia_id_id) REFERENCES provincia (id)');
        $this->addSql('CREATE INDEX IDX_2741493C4E7121AF ON contacto (provincia_id)');
        $this->addSql('CREATE INDEX IDX_2741493C1FF88B6A ON contacto (provincia_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contacto DROP FOREIGN KEY FK_2741493C4E7121AF');
        $this->addSql('ALTER TABLE contacto DROP FOREIGN KEY FK_2741493C1FF88B6A');
        $this->addSql('DROP INDEX IDX_2741493C4E7121AF ON contacto');
        $this->addSql('DROP INDEX IDX_2741493C1FF88B6A ON contacto');
        $this->addSql('ALTER TABLE contacto DROP provincia_id, DROP provincia_id_id');
    }
}
