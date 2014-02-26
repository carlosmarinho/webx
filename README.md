Webx Email Crawler
===================

Rodar as seguintes querys para montar o banco de dadaos:


CREATE TABLE IF NOT EXISTS `emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 


CREATE TABLE IF NOT EXISTS `urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `domain` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `visited` enum('yes','no') COLLATE utf8_unicode_ci DEFAULT 'no',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8

INSERT INTO `webx`.`urls`(url) VALUES('https://www.google.com.br/?gfe_rd=ctrl&ei=9xcNU6uRGYfJ8Qa-moHwAg&gws_rd=cr#q=webx&safe=off');


=> Para rodar o crawler basta digitar no navegador o link: http://localhost/webx/url/crawler
=> Para visualizar os emails basta digitar o link: http://localhost/webx/email
