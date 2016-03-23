--
-- Struktura tabulky `databaseversion`
--

CREATE TABLE IF NOT EXISTS `databaseversion` (
  `version` varchar(6) NOT NULL,
  `versiontype` varchar(4) NOT NULL DEFAULT 'INC',
  `scriptdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='keep actual database version';

insert into databaseversion (version, versiontype) values ('1.0', 'INC');