<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration editada à mão: RENAME em vez de DROP/CREATE para preservar os dados.
 */
final class Version20261027152655 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Renomeia mobile para furniture (tabela e slot.mobile_id) sem perder dados';
    }

    public function up(Schema $schema): void
    {
        // Solta a chave estrangeira, renomeia tabela/coluna/índice e recria a chave
        $this->addSql('ALTER TABLE slot DROP FOREIGN KEY `FK_AC0E2067B806424B`');
        $this->addSql('RENAME TABLE mobile TO furniture');
        $this->addSql('ALTER TABLE slot CHANGE mobile_id furniture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE slot RENAME INDEX IDX_AC0E2067B806424B TO IDX_AC0E2067CF5485C3');
        $this->addSql('ALTER TABLE slot ADD CONSTRAINT FK_AC0E2067CF5485C3 FOREIGN KEY (furniture_id) REFERENCES furniture (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE slot DROP FOREIGN KEY FK_AC0E2067CF5485C3');
        $this->addSql('RENAME TABLE furniture TO mobile');
        $this->addSql('ALTER TABLE slot CHANGE furniture_id mobile_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE slot RENAME INDEX IDX_AC0E2067CF5485C3 TO IDX_AC0E2067B806424B');
        $this->addSql('ALTER TABLE slot ADD CONSTRAINT `FK_AC0E2067B806424B` FOREIGN KEY (mobile_id) REFERENCES mobile (id)');
    }
}
