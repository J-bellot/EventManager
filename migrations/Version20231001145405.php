<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231001145405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_attendee (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_attendee_event (event_attendee_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_ECDF59661774ABAA (event_attendee_id), INDEX IDX_ECDF596671F7E88B (event_id), PRIMARY KEY(event_attendee_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_attendee_user (event_attendee_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_F112F9A81774ABAA (event_attendee_id), INDEX IDX_F112F9A8A76ED395 (user_id), PRIMARY KEY(event_attendee_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_attendee_event ADD CONSTRAINT FK_ECDF59661774ABAA FOREIGN KEY (event_attendee_id) REFERENCES event_attendee (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_attendee_event ADD CONSTRAINT FK_ECDF596671F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_attendee_user ADD CONSTRAINT FK_F112F9A81774ABAA FOREIGN KEY (event_attendee_id) REFERENCES event_attendee (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_attendee_user ADD CONSTRAINT FK_F112F9A8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE eventattendee DROP FOREIGN KEY FK_E04F9DB571F7E88B');
        $this->addSql('ALTER TABLE eventattendee DROP FOREIGN KEY FK_E04F9DB5A76ED395');
        $this->addSql('DROP TABLE eventattendee');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE eventattendee (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_E04F9DB571F7E88B (event_id), INDEX IDX_E04F9DB5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE eventattendee ADD CONSTRAINT FK_E04F9DB571F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE eventattendee ADD CONSTRAINT FK_E04F9DB5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE event_attendee_event DROP FOREIGN KEY FK_ECDF59661774ABAA');
        $this->addSql('ALTER TABLE event_attendee_event DROP FOREIGN KEY FK_ECDF596671F7E88B');
        $this->addSql('ALTER TABLE event_attendee_user DROP FOREIGN KEY FK_F112F9A81774ABAA');
        $this->addSql('ALTER TABLE event_attendee_user DROP FOREIGN KEY FK_F112F9A8A76ED395');
        $this->addSql('DROP TABLE event_attendee');
        $this->addSql('DROP TABLE event_attendee_event');
        $this->addSql('DROP TABLE event_attendee_user');
    }
}
