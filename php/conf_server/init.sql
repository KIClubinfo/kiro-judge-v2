CREATE TABLE IF NOT EXISTS `kiro`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `prenom` VARCHAR(100) NOT NULL ,
  `nom` VARCHAR(100) NOT NULL ,
  `password` VARCHAR(128) NOT NULL ,
  `mail` VARCHAR(255) NOT NULL ,
  `chef` BOOLEAN NOT NULL ,
  `tel` VARCHAR(15) NOT NULL ,
  `code_reinitialiser` VARCHAR(150) NULL DEFAULT NULL ,
  `ecole` VARCHAR(300) NOT NULL , PRIMARY KEY (`id`))
  ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `kiro`.`teams` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nom` VARCHAR(180) NOT NULL ,
  `score` BIGINT NOT NULL ,
  `classement` INT NOT NULL ,
  `code_valider` INT NOT NULL ,
  `valide` BOOLEAN NOT NULL ,
  `hub` INT NOT NULL ,
  `numero_emplacement` INT NOT NULL , PRIMARY KEY (`id`))
  ENGINE = InnoDB;
