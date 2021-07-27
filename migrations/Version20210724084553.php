<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210724084553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE egg_group (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE evolution_chain (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE generation (id INT NOT NULL, name VARCHAR(255) NOT NULL, generation_identifier INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE item (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE item_name (id INT NOT NULL, item_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, language INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_96133AFD126F525E ON item_name (item_id)');
        $this->addSql('CREATE TABLE machine (id INT NOT NULL, move_id INT DEFAULT NULL, item_id INT DEFAULT NULL, version_group_id INT NOT NULL, machine_number INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1505DF846DC541A8 ON machine (move_id)');
        $this->addSql('CREATE INDEX IDX_1505DF84126F525E ON machine (item_id)');
        $this->addSql('CREATE INDEX IDX_1505DF8492AE854F ON machine (version_group_id)');
        $this->addSql('CREATE TABLE move (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE move_learn_method (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE move_name (id INT NOT NULL, move_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, language INT NOT NULL, gen1 VARCHAR(255) DEFAULT NULL, gen2 VARCHAR(255) DEFAULT NULL, gen3 VARCHAR(255) DEFAULT NULL, gen4 VARCHAR(255) DEFAULT NULL, gen5 VARCHAR(255) DEFAULT NULL, gen6 VARCHAR(255) DEFAULT NULL, gen7 VARCHAR(255) DEFAULT NULL, gen8 VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_504B42B26DC541A8 ON move_name (move_id)');
        $this->addSql('CREATE TABLE pokemon (id INT NOT NULL, pokemon_specy_id INT NOT NULL, name VARCHAR(255) NOT NULL, specific_name VARCHAR(255) DEFAULT NULL, is_alola BOOLEAN NOT NULL, is_galar BOOLEAN NOT NULL, is_default BOOLEAN NOT NULL, has_move_forms BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_62DC90F3256D30DF ON pokemon (pokemon_specy_id)');
        $this->addSql('CREATE TABLE pokemon_pokemon (pokemon_source INT NOT NULL, pokemon_target INT NOT NULL, PRIMARY KEY(pokemon_source, pokemon_target))');
        $this->addSql('CREATE INDEX IDX_EB6EC6233630327 ON pokemon_pokemon (pokemon_source)');
        $this->addSql('CREATE INDEX IDX_EB6EC622A8653A8 ON pokemon_pokemon (pokemon_target)');
        $this->addSql('CREATE TABLE pokemon_availability (id INT NOT NULL, pokemon_id INT NOT NULL, version_group_id INT NOT NULL, availability BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7E30D9662FE71C3E ON pokemon_availability (pokemon_id)');
        $this->addSql('CREATE INDEX IDX_7E30D96692AE854F ON pokemon_availability (version_group_id)');
        $this->addSql('CREATE TABLE pokemon_move (id INT NOT NULL, move_id INT NOT NULL, version_group_id INT NOT NULL, learn_method_id INT NOT NULL, pokemon_id INT NOT NULL, level INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D397493B6DC541A8 ON pokemon_move (move_id)');
        $this->addSql('CREATE INDEX IDX_D397493B92AE854F ON pokemon_move (version_group_id)');
        $this->addSql('CREATE INDEX IDX_D397493B764E5F26 ON pokemon_move (learn_method_id)');
        $this->addSql('CREATE INDEX IDX_D397493B2FE71C3E ON pokemon_move (pokemon_id)');
        $this->addSql('CREATE TABLE pokemon_specy (id INT NOT NULL, evolution_chain_id INT DEFAULT NULL, generation_id INT NOT NULL, name VARCHAR(255) NOT NULL, pokemon_species_order INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D5938CFDE417AC09 ON pokemon_specy (evolution_chain_id)');
        $this->addSql('CREATE INDEX IDX_D5938CFD553A6EC4 ON pokemon_specy (generation_id)');
        $this->addSql('CREATE TABLE pokemon_specy_egg_group (pokemon_specy_id INT NOT NULL, egg_group_id INT NOT NULL, PRIMARY KEY(pokemon_specy_id, egg_group_id))');
        $this->addSql('CREATE INDEX IDX_4243C8E9256D30DF ON pokemon_specy_egg_group (pokemon_specy_id)');
        $this->addSql('CREATE INDEX IDX_4243C8E9B76DC94C ON pokemon_specy_egg_group (egg_group_id)');
        $this->addSql('CREATE TABLE specy_name (id INT NOT NULL, pokemon_specy_id INT NOT NULL, name VARCHAR(255) NOT NULL, language INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C50BC896256D30DF ON specy_name (pokemon_specy_id)');
        $this->addSql('CREATE TABLE version_group (id INT NOT NULL, generation_id INT NOT NULL, name VARCHAR(255) NOT NULL, version_group_order INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6A1C90C0553A6EC4 ON version_group (generation_id)');
        $this->addSql('ALTER TABLE item_name ADD CONSTRAINT FK_96133AFD126F525E FOREIGN KEY (item_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE machine ADD CONSTRAINT FK_1505DF846DC541A8 FOREIGN KEY (move_id) REFERENCES move (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE machine ADD CONSTRAINT FK_1505DF84126F525E FOREIGN KEY (item_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE machine ADD CONSTRAINT FK_1505DF8492AE854F FOREIGN KEY (version_group_id) REFERENCES version_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE move_name ADD CONSTRAINT FK_504B42B26DC541A8 FOREIGN KEY (move_id) REFERENCES move (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3256D30DF FOREIGN KEY (pokemon_specy_id) REFERENCES pokemon_specy (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_pokemon ADD CONSTRAINT FK_EB6EC6233630327 FOREIGN KEY (pokemon_source) REFERENCES pokemon (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_pokemon ADD CONSTRAINT FK_EB6EC622A8653A8 FOREIGN KEY (pokemon_target) REFERENCES pokemon (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_availability ADD CONSTRAINT FK_7E30D9662FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_availability ADD CONSTRAINT FK_7E30D96692AE854F FOREIGN KEY (version_group_id) REFERENCES version_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_move ADD CONSTRAINT FK_D397493B6DC541A8 FOREIGN KEY (move_id) REFERENCES move (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_move ADD CONSTRAINT FK_D397493B92AE854F FOREIGN KEY (version_group_id) REFERENCES version_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_move ADD CONSTRAINT FK_D397493B764E5F26 FOREIGN KEY (learn_method_id) REFERENCES move_learn_method (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_move ADD CONSTRAINT FK_D397493B2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_specy ADD CONSTRAINT FK_D5938CFDE417AC09 FOREIGN KEY (evolution_chain_id) REFERENCES evolution_chain (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_specy ADD CONSTRAINT FK_D5938CFD553A6EC4 FOREIGN KEY (generation_id) REFERENCES generation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_specy_egg_group ADD CONSTRAINT FK_4243C8E9256D30DF FOREIGN KEY (pokemon_specy_id) REFERENCES pokemon_specy (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_specy_egg_group ADD CONSTRAINT FK_4243C8E9B76DC94C FOREIGN KEY (egg_group_id) REFERENCES egg_group (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE specy_name ADD CONSTRAINT FK_C50BC896256D30DF FOREIGN KEY (pokemon_specy_id) REFERENCES pokemon_specy (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE version_group ADD CONSTRAINT FK_6A1C90C0553A6EC4 FOREIGN KEY (generation_id) REFERENCES generation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE pokemon_specy_egg_group DROP CONSTRAINT FK_4243C8E9B76DC94C');
        $this->addSql('ALTER TABLE pokemon_specy DROP CONSTRAINT FK_D5938CFDE417AC09');
        $this->addSql('ALTER TABLE pokemon_specy DROP CONSTRAINT FK_D5938CFD553A6EC4');
        $this->addSql('ALTER TABLE version_group DROP CONSTRAINT FK_6A1C90C0553A6EC4');
        $this->addSql('ALTER TABLE item_name DROP CONSTRAINT FK_96133AFD126F525E');
        $this->addSql('ALTER TABLE machine DROP CONSTRAINT FK_1505DF84126F525E');
        $this->addSql('ALTER TABLE machine DROP CONSTRAINT FK_1505DF846DC541A8');
        $this->addSql('ALTER TABLE move_name DROP CONSTRAINT FK_504B42B26DC541A8');
        $this->addSql('ALTER TABLE pokemon_move DROP CONSTRAINT FK_D397493B6DC541A8');
        $this->addSql('ALTER TABLE pokemon_move DROP CONSTRAINT FK_D397493B764E5F26');
        $this->addSql('ALTER TABLE pokemon_pokemon DROP CONSTRAINT FK_EB6EC6233630327');
        $this->addSql('ALTER TABLE pokemon_pokemon DROP CONSTRAINT FK_EB6EC622A8653A8');
        $this->addSql('ALTER TABLE pokemon_availability DROP CONSTRAINT FK_7E30D9662FE71C3E');
        $this->addSql('ALTER TABLE pokemon_move DROP CONSTRAINT FK_D397493B2FE71C3E');
        $this->addSql('ALTER TABLE pokemon DROP CONSTRAINT FK_62DC90F3256D30DF');
        $this->addSql('ALTER TABLE pokemon_specy_egg_group DROP CONSTRAINT FK_4243C8E9256D30DF');
        $this->addSql('ALTER TABLE specy_name DROP CONSTRAINT FK_C50BC896256D30DF');
        $this->addSql('ALTER TABLE machine DROP CONSTRAINT FK_1505DF8492AE854F');
        $this->addSql('ALTER TABLE pokemon_availability DROP CONSTRAINT FK_7E30D96692AE854F');
        $this->addSql('ALTER TABLE pokemon_move DROP CONSTRAINT FK_D397493B92AE854F');
        $this->addSql('DROP TABLE egg_group');
        $this->addSql('DROP TABLE evolution_chain');
        $this->addSql('DROP TABLE generation');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE item_name');
        $this->addSql('DROP TABLE machine');
        $this->addSql('DROP TABLE move');
        $this->addSql('DROP TABLE move_learn_method');
        $this->addSql('DROP TABLE move_name');
        $this->addSql('DROP TABLE pokemon');
        $this->addSql('DROP TABLE pokemon_pokemon');
        $this->addSql('DROP TABLE pokemon_availability');
        $this->addSql('DROP TABLE pokemon_move');
        $this->addSql('DROP TABLE pokemon_specy');
        $this->addSql('DROP TABLE pokemon_specy_egg_group');
        $this->addSql('DROP TABLE specy_name');
        $this->addSql('DROP TABLE version_group');
    }
}