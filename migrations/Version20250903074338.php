<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250903074338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE travel (id INT AUTO_INCREMENT NOT NULL, car_id INT NOT NULL, eco TINYINT(1) NOT NULL, travel_place SMALLINT NOT NULL, available_place SMALLINT NOT NULL, price SMALLINT NOT NULL, status VARCHAR(255) NOT NULL, dep_date_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', dep_address VARCHAR(255) NOT NULL, dep_geo_x DOUBLE PRECISION NOT NULL, dep_geo_y DOUBLE PRECISION NOT NULL, arr_date_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', arr_address VARCHAR(255) NOT NULL, arr_geo_x DOUBLE PRECISION NOT NULL, arr_geo_y DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_2D0B6BCEC3C6F69F (car_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE travel_user (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, travel_id INT NOT NULL, travel_role VARCHAR(255) NOT NULL, INDEX IDX_46CB35E4A76ED395 (user_id), INDEX IDX_46CB35E4ECAB15B3 (travel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE travel ADD CONSTRAINT FK_2D0B6BCEC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
        $this->addSql('ALTER TABLE travel_user ADD CONSTRAINT FK_46CB35E4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE travel_user ADD CONSTRAINT FK_46CB35E4ECAB15B3 FOREIGN KEY (travel_id) REFERENCES travel (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE travel DROP FOREIGN KEY FK_2D0B6BCEC3C6F69F');
        $this->addSql('ALTER TABLE travel_user DROP FOREIGN KEY FK_46CB35E4A76ED395');
        $this->addSql('ALTER TABLE travel_user DROP FOREIGN KEY FK_46CB35E4ECAB15B3');
        $this->addSql('DROP TABLE travel');
        $this->addSql('DROP TABLE travel_user');
    }
}
