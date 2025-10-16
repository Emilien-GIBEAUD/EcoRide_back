<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251004102245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE INDEX idx_travel_status_date ON travel (status, dep_date_time)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX idx_travel_status_date ON travel');
    }
}
