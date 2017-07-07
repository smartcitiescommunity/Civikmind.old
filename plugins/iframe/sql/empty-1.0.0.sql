DROP TABLE IF EXISTS `glpi_plugin_iframe_links`;
CREATE TABLE `glpi_plugin_iframeplugin_links` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `link` VARCHAR(199) NULL COMMENT '',
  `nome` VARCHAR(199) NULL COMMENT '',
  `descricao` VARCHAR(5000) NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '');