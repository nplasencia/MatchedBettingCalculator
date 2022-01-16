<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220116125649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE back_bet (id INT AUTO_INCREMENT NOT NULL, bookmaker_id INT NOT NULL, stake DOUBLE PRECISION NOT NULL, odds DOUBLE PRECISION NOT NULL, bet_return DOUBLE PRECISION NOT NULL, profit DOUBLE PRECISION NOT NULL, result VARCHAR(255) NOT NULL, INDEX IDX_2D702E2B8FB29728 (bookmaker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bookmaker (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, event_type_id INT NOT NULL, name VARCHAR(255) NOT NULL, date_time DATETIME NOT NULL, INDEX IDX_3BAE0AA7401B253C (event_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exchange (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lay_bet (id INT AUTO_INCREMENT NOT NULL, exchange_id INT NOT NULL, stake DOUBLE PRECISION NOT NULL, odds DOUBLE PRECISION NOT NULL, liability DOUBLE PRECISION NOT NULL, bet_return DOUBLE PRECISION NOT NULL, profit DOUBLE PRECISION NOT NULL, result VARCHAR(255) NOT NULL, INDEX IDX_71CF8DD368AFD1A0 (exchange_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE back_bet ADD CONSTRAINT FK_2D702E2B8FB29728 FOREIGN KEY (bookmaker_id) REFERENCES bookmaker (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7401B253C FOREIGN KEY (event_type_id) REFERENCES event_type (id)');
        $this->addSql('ALTER TABLE lay_bet ADD CONSTRAINT FK_71CF8DD368AFD1A0 FOREIGN KEY (exchange_id) REFERENCES exchange (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE back_bet DROP FOREIGN KEY FK_2D702E2B8FB29728');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7401B253C');
        $this->addSql('ALTER TABLE lay_bet DROP FOREIGN KEY FK_71CF8DD368AFD1A0');
        $this->addSql('DROP TABLE back_bet');
        $this->addSql('DROP TABLE bookmaker');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_type');
        $this->addSql('DROP TABLE exchange');
        $this->addSql('DROP TABLE lay_bet');
    }
}
