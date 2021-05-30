<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210530074319 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE dislikes_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE likes_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE "dislike_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "like_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "dislike" (id INT NOT NULL, meme_id INT NOT NULL, from_user_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FE3BECAADB6EC45D ON "dislike" (meme_id)');
        $this->addSql('CREATE INDEX IDX_FE3BECAA2130303A ON "dislike" (from_user_id)');
        $this->addSql('CREATE TABLE "like" (id INT NOT NULL, meme_id INT NOT NULL, from_user_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AC6340B3DB6EC45D ON "like" (meme_id)');
        $this->addSql('CREATE INDEX IDX_AC6340B32130303A ON "like" (from_user_id)');
        $this->addSql('ALTER TABLE "dislike" ADD CONSTRAINT FK_FE3BECAADB6EC45D FOREIGN KEY (meme_id) REFERENCES meme (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "dislike" ADD CONSTRAINT FK_FE3BECAA2130303A FOREIGN KEY (from_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "like" ADD CONSTRAINT FK_AC6340B3DB6EC45D FOREIGN KEY (meme_id) REFERENCES meme (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "like" ADD CONSTRAINT FK_AC6340B32130303A FOREIGN KEY (from_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE dislikes');
        $this->addSql('DROP TABLE likes');
        $this->addSql('ALTER TABLE meme DROP likes');
        $this->addSql('ALTER TABLE meme DROP dislikes');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "dislike_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "like_id_seq" CASCADE');
        $this->addSql('CREATE SEQUENCE dislikes_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE likes_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE dislikes (id INT NOT NULL, from_user_id INT NOT NULL, meme_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_2df3be112130303a ON dislikes (from_user_id)');
        $this->addSql('CREATE INDEX idx_2df3be11db6ec45d ON dislikes (meme_id)');
        $this->addSql('CREATE TABLE likes (id INT NOT NULL, from_user_id INT NOT NULL, meme_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_49ca4e7ddb6ec45d ON likes (meme_id)');
        $this->addSql('CREATE INDEX idx_49ca4e7d2130303a ON likes (from_user_id)');
        $this->addSql('ALTER TABLE dislikes ADD CONSTRAINT fk_2df3be112130303a FOREIGN KEY (from_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dislikes ADD CONSTRAINT fk_2df3be11db6ec45d FOREIGN KEY (meme_id) REFERENCES meme (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT fk_49ca4e7d2130303a FOREIGN KEY (from_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT fk_49ca4e7ddb6ec45d FOREIGN KEY (meme_id) REFERENCES meme (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE "dislike"');
        $this->addSql('DROP TABLE "like"');
        $this->addSql('ALTER TABLE meme ADD likes INT NOT NULL');
        $this->addSql('ALTER TABLE meme ADD dislikes INT NOT NULL');
    }
}
