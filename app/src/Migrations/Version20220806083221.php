<?php

declare(strict_types=1);

namespace Arslav\Newbot\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220806083221 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE commandlog_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE command_log_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE command_log (id INT NOT NULL, from_id INT NOT NULL, chat_id INT DEFAULT NULL, command VARCHAR(255) NOT NULL, created_at INT NOT NULL, updated_at INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE commandlog');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE command_log_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE commandlog_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE commandlog (id INT NOT NULL, user_id INT NOT NULL, command VARCHAR(255) NOT NULL, created_at INT NOT NULL, updated_at INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE command_log');
    }
}
