<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240923170614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, locality VARCHAR(255) DEFAULT NULL, street VARCHAR(255) NOT NULL, number VARCHAR(10) NOT NULL, post_code INT NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_subscription (id INT AUTO_INCREMENT NOT NULL, user_subscriptor_id INT DEFAULT NULL, event_subscripted_id INT DEFAULT NULL, subscription_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', number_participants INT NOT NULL, INDEX IDX_4ED56E203CECDD17 (user_subscriptor_id), INDEX IDX_4ED56E20B7EFA4DF (event_subscripted_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gaming_place (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, place_max INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_subscription ADD CONSTRAINT FK_4ED56E203CECDD17 FOREIGN KEY (user_subscriptor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event_subscription ADD CONSTRAINT FK_4ED56E20B7EFA4DF FOREIGN KEY (event_subscripted_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event ADD recurrence_type VARCHAR(255) NOT NULL, ADD recurrence_end DATETIME DEFAULT NULL, ADD recurrence_count INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_subscription DROP FOREIGN KEY FK_4ED56E203CECDD17');
        $this->addSql('ALTER TABLE event_subscription DROP FOREIGN KEY FK_4ED56E20B7EFA4DF');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE event_subscription');
        $this->addSql('DROP TABLE gaming_place');
        $this->addSql('ALTER TABLE event DROP recurrence_type, DROP recurrence_end, DROP recurrence_count');
    }
}
