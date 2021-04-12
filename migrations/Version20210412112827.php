<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210412112827 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE dislikes_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE dislikes (id INT NOT NULL, from_user_id INT NOT NULL, meme_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2DF3BE112130303A ON dislikes (from_user_id)');
        $this->addSql('CREATE INDEX IDX_2DF3BE11DB6EC45D ON dislikes (meme_id)');
        $this->addSql('ALTER TABLE dislikes ADD CONSTRAINT FK_2DF3BE112130303A FOREIGN KEY (from_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dislikes ADD CONSTRAINT FK_2DF3BE11DB6EC45D FOREIGN KEY (meme_id) REFERENCES meme (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE dislikes_id_seq CASCADE');
        $this->addSql('DROP TABLE dislikes');
    }
}
