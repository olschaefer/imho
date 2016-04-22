<?php

namespace BackendBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151114071153 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql("CREATE EXTENSION \"uuid-ossp\"");
        $this->addSql('CREATE TABLE content_hash (content_hash CHAR(32) NOT NULL, file_id CHAR(36) NOT NULL, PRIMARY KEY(content_hash))');
        $this->addSql('CREATE TABLE image (
                              id CHAR(36) NOT NULL,
                              original_id CHAR(36),
                              path VARCHAR(100) NOT NULL,
                              size INT NOT NULL,
                              key CHAR(36) NOT NULL,
                              user_id INT NOT NULL,
                              created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                              bucket INT NOT NULL,
                              storage_bucket INT NOT NULL,
                          PRIMARY KEY(id)
                      )');

        $this->addSql('CREATE INDEX image_created_at_idx ON image(created_at)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE content_hash');
        $this->addSql('DROP TABLE image');
        $this->addSql("DROP EXTENSION \"uuid-ossp\"");
    }
}
