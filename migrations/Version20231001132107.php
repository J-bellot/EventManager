<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231001132107 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE eventattendee (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_E04F9DB5A76ED395 (user_id), INDEX IDX_E04F9DB571F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE eventattendee ADD CONSTRAINT FK_E04F9DB5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE eventattendee ADD CONSTRAINT FK_E04F9DB571F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event_attendees DROP FOREIGN KEY FK_4E5C55183C76898B');
        $this->addSql('ALTER TABLE event_attendees DROP FOREIGN KEY FK_4E5C551871F7E88B');
        $this->addSql('DROP TABLE event_attendees');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_attendees (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, attendees_id INT NOT NULL, UNIQUE INDEX UNIQ_4E5C55183C76898B (attendees_id), UNIQUE INDEX UNIQ_4E5C551871F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE event_attendees ADD CONSTRAINT FK_4E5C55183C76898B FOREIGN KEY (attendees_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE event_attendees ADD CONSTRAINT FK_4E5C551871F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE eventattendee DROP FOREIGN KEY FK_E04F9DB5A76ED395');
        $this->addSql('ALTER TABLE eventattendee DROP FOREIGN KEY FK_E04F9DB571F7E88B');
        $this->addSql('DROP TABLE eventattendee');
    }
}
