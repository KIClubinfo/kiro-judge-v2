CREATE TABLE IF NOT EXISTS `kiro`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `prenom` VARCHAR(100) NOT NULL ,
  `nom` VARCHAR(100) NOT NULL ,
  `password` VARCHAR(128) NOT NULL ,
  `mail` VARCHAR(255) NOT NULL ,
  `mdp_a_changer` BOOLEAN NOT NULL ,
  `tel` VARCHAR(15) NOT NULL ,
  `id_team` INT NOT NULL ,
  `admin` BOOLEAN  DEFAULT FALSE,
  `ecole` VARCHAR(300) NOT NULL , PRIMARY KEY (`id`))
  ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `kiro`.`teams` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nom` VARCHAR(180) NOT NULL ,
  `score` BIGINT NOT NULL ,
  `classement` INT NOT NULL ,
  `valide` BOOLEAN NOT NULL ,
  `hub` INT NOT NULL ,
  `type_equipe` INT NOT NULL ,
  `numero_emplacement` INT NOT NULL , PRIMARY KEY (`id`))
  ENGINE = InnoDB;

INSERT INTO `kiro`.`users` (`id`, `prenom`, `nom`, `password`, `mail`, `mdp_a_changer`, `tel`, `id_team`, `admin`,`ecole`) VALUES ('1', 'admin', 'admin', '$2y$12$F6Q4LapmPtZw8RzpVuAhd.47PJ7KG2xQTUeRB/d3XCI7cjG9qW8OG', 'admin@kiro.enpc.org', '1', '00000000', '0','1', 'KI');