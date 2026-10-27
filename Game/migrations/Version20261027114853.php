<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261027114853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Os slots antigos eram compartilhados (sem dono). Agora cada usuário tem os seus,
        // criados ao abrir a sala, então os antigos são removidos antes de exigir user_id.
        $this->addSql('DELETE FROM slot');
        $this->addSql('ALTER TABLE slot ADD position INT NOT NULL, ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE slot ADD CONSTRAINT FK_AC0E2067A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_SLOT_ROOM_USER_POSITION ON slot (room_id, user_id, position)');
        $this->addSql('CREATE INDEX IDX_AC0E2067A76ED395 ON slot (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE slot DROP FOREIGN KEY FK_AC0E2067A76ED395');
        $this->addSql('DROP INDEX UNIQ_SLOT_ROOM_USER_POSITION ON slot');
        $this->addSql('DROP INDEX IDX_AC0E2067A76ED395 ON slot');
        $this->addSql('ALTER TABLE slot DROP position, DROP user_id');
    }
}
