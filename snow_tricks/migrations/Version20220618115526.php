<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220618115526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment CHANGE status status ENUM(\'status.pending\', \'status.accepted\', \'status.rejected\')');
        $this->addSql('DROP INDEX UNIQ_4C289FC8D93D649 ON confirm_user_email_request');
        $this->addSql('ALTER TABLE confirm_user_email_request ADD user_id INT DEFAULT NULL, DROP user');
        $this->addSql('ALTER TABLE confirm_user_email_request ADD CONSTRAINT FK_4C289FCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_4C289FCA76ED395 ON confirm_user_email_request (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE confirm_user_email_request DROP FOREIGN KEY FK_4C289FCA76ED395');
        $this->addSql('DROP INDEX IDX_4C289FCA76ED395 ON confirm_user_email_request');
        $this->addSql('ALTER TABLE confirm_user_email_request ADD user VARCHAR(255) NOT NULL, DROP user_id');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4C289FC8D93D649 ON confirm_user_email_request (user)');
    }
}
