<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211027220217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE car (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, color VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, features LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', quantity INT DEFAULT NULL, price INT DEFAULT NULL, rentable INT DEFAULT NULL, date_created DATETIME NOT NULL, date_modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', first_name VARCHAR(255) NOT NULL, middle_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) NOT NULL, phone_numbers LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', emails LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', date_created DATETIME NOT NULL, date_modified DATETIME NOT NULL, UNIQUE INDEX UNIQ_34DCD176A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rental (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', car_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', rented_by_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, status VARCHAR(255) NOT NULL, price INT NOT NULL, date_created DATETIME NOT NULL, date_modified DATETIME NOT NULL, INDEX IDX_1619C27DC3C6F69F (car_id), INDEX IDX_1619C27D86A9EE76 (rented_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, date_created DATETIME NOT NULL, date_modified DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rental ADD CONSTRAINT FK_1619C27DC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
        $this->addSql('ALTER TABLE rental ADD CONSTRAINT FK_1619C27D86A9EE76 FOREIGN KEY (rented_by_id) REFERENCES person (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rental DROP FOREIGN KEY FK_1619C27DC3C6F69F');
        $this->addSql('ALTER TABLE rental DROP FOREIGN KEY FK_1619C27D86A9EE76');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176A76ED395');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE rental');
        $this->addSql('DROP TABLE user');
    }
}
