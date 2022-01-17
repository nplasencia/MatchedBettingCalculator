<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220117072944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE matched_bet (id INT AUTO_INCREMENT NOT NULL, back_bet_id INT NOT NULL, lay_bet_id INT NOT NULL, event_id INT NOT NULL, market_type_id INT NOT NULL, bet VARCHAR(255) NOT NULL, bet_type VARCHAR(255) NOT NULL, bet_mode VARCHAR(255) NOT NULL, notes LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_26C4578795B140C (back_bet_id), UNIQUE INDEX UNIQ_26C457876C78770F (lay_bet_id), INDEX IDX_26C4578771F7E88B (event_id), INDEX IDX_26C45787CCDA6413 (market_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE matched_bet ADD CONSTRAINT FK_26C4578795B140C FOREIGN KEY (back_bet_id) REFERENCES back_bet (id)');
        $this->addSql('ALTER TABLE matched_bet ADD CONSTRAINT FK_26C457876C78770F FOREIGN KEY (lay_bet_id) REFERENCES lay_bet (id)');
        $this->addSql('ALTER TABLE matched_bet ADD CONSTRAINT FK_26C4578771F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE matched_bet ADD CONSTRAINT FK_26C45787CCDA6413 FOREIGN KEY (market_type_id) REFERENCES market_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE matched_bet');
    }
}
