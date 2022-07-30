<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220730112730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE pricing_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE pricing (id INT NOT NULL, model VARCHAR(100) NOT NULL, brand VARCHAR(50) NOT NULL, ram INT NOT NULL, ramtype VARCHAR(50) NOT NULL, storage INT NOT NULL, storagetype VARCHAR(50) NOT NULL, storagetxt VARCHAR(100) NOT NULL, location VARCHAR(10) NOT NULL, city VARCHAR(50) NOT NULL, currency VARCHAR(10) NOT NULL, price NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE pricing_id_seq CASCADE');
        $this->addSql('DROP TABLE pricing');
    }
}
