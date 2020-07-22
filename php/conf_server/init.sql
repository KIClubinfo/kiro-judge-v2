CREATE TABLE IF NOT EXISTS `kiro`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `prenom` VARCHAR(100) NOT NULL ,
  `nom` VARCHAR(100) NOT NULL ,
  `password` VARCHAR(128) NOT NULL ,
  `mail` VARCHAR(255) NOT NULL ,
  `mdp_a_changer` BOOLEAN NOT NULL ,
  `tel` VARCHAR(15) NOT NULL ,
  `code_reinitialiser` VARCHAR(150) NULL DEFAULT NULL ,
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
  `numero_emplacement` INT NOT NULL , PRIMARY KEY (`id`))
  ENGINE = InnoDB;

INSERT INTO `users` (`id`, `prenom`, `nom`, `password`, `mail`, `mdp_a_changer`, `tel`, `id_team`, `admin`,`ecole`) VALUES ('1', 'admin', 'admin', '$2y$10$0R6d.079M9MYb9QzAuVV1udzg8Y1zhHv5cckqdir/66ijwenjq.hG', 'admin@kiro.enpc.org', '1', '00000000', '0','1', 'KI');