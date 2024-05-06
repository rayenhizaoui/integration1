<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240503144109 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY fk_id_jeu');
        $this->addSql('ALTER TABLE equipe CHANGE id_jeu id_jeu INT DEFAULT NULL');
        $this->addSql('DROP INDEX fk_id_jeu ON equipe');
        $this->addSql('CREATE INDEX IDX_2449BA151C4065EF ON equipe (id_jeu)');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT fk_id_jeu FOREIGN KEY (id_jeu) REFERENCES jeu (id)');
        $this->addSql('ALTER TABLE equipement CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE QrCode qrcode VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE evenement CHANGE id_recompense id_recompense INT DEFAULT NULL');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EBB114009 FOREIGN KEY (id_recompense) REFERENCES recompense (id)');
        $this->addSql('DROP INDEX id_recompence ON evenement');
        $this->addSql('CREATE INDEX IDX_B26681EBB114009 ON evenement (id_recompense)');
        $this->addSql('ALTER TABLE local ADD longitude VARCHAR(255) DEFAULT NULL, ADD latitude VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE id_equipement id_equipement INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849551D3E4624 FOREIGN KEY (id_equipement) REFERENCES equipement (id)');
        $this->addSql('CREATE INDEX IDX_42C849551D3E4624 ON reservation (id_equipement)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA151C4065EF');
        $this->addSql('ALTER TABLE equipe CHANGE id_jeu id_jeu INT NOT NULL');
        $this->addSql('DROP INDEX idx_2449ba151c4065ef ON equipe');
        $this->addSql('CREATE INDEX fk_id_jeu ON equipe (id_jeu)');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA151C4065EF FOREIGN KEY (id_jeu) REFERENCES jeu (id)');
        $this->addSql('ALTER TABLE equipement CHANGE image image VARCHAR(255) NOT NULL, CHANGE qrcode QrCode MEDIUMTEXT NOT NULL');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EBB114009');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EBB114009');
        $this->addSql('ALTER TABLE evenement CHANGE id_recompense id_recompense INT NOT NULL');
        $this->addSql('DROP INDEX idx_b26681ebb114009 ON evenement');
        $this->addSql('CREATE INDEX id_recompence ON evenement (id_recompense)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EBB114009 FOREIGN KEY (id_recompense) REFERENCES recompense (id)');
        $this->addSql('ALTER TABLE local DROP longitude, DROP latitude');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849551D3E4624');
        $this->addSql('DROP INDEX IDX_42C849551D3E4624 ON reservation');
        $this->addSql('ALTER TABLE reservation CHANGE id_equipement id_equipement INT NOT NULL');
    }
}
