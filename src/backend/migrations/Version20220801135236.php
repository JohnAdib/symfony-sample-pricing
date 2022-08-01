<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220801135236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX idx_model ON pricing (model)');
        $this->addSql('CREATE INDEX idx_brand ON pricing (brand)');
        $this->addSql('CREATE INDEX idx_ram ON pricing (ram)');
        $this->addSql('CREATE INDEX idx_ramtype ON pricing (ramtype)');
        $this->addSql('CREATE INDEX idx_storage ON pricing (storage)');
        $this->addSql('CREATE INDEX idx_storagetype ON pricing (storagetype)');
        $this->addSql('CREATE INDEX idx_storagetxt ON pricing (storagetxt)');
        $this->addSql('CREATE INDEX idx_location ON pricing (location)');
        $this->addSql('CREATE INDEX idx_city ON pricing (city)');
        $this->addSql('CREATE INDEX idx_currency ON pricing (currency)');
        $this->addSql('CREATE INDEX idx_price ON pricing (price)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_model ON pricing');
        $this->addSql('DROP INDEX idx_brand ON pricing');
        $this->addSql('DROP INDEX idx_ram ON pricing');
        $this->addSql('DROP INDEX idx_ramtype ON pricing');
        $this->addSql('DROP INDEX idx_storage ON pricing');
        $this->addSql('DROP INDEX idx_storagetype ON pricing');
        $this->addSql('DROP INDEX idx_storagetxt ON pricing');
        $this->addSql('DROP INDEX idx_location ON pricing');
        $this->addSql('DROP INDEX idx_city ON pricing');
        $this->addSql('DROP INDEX idx_currency ON pricing');
        $this->addSql('DROP INDEX idx_price ON pricing');
    }
}
