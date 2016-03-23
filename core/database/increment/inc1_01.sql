
ALTER TABLE `owner` ADD `dt_request_cancel` TIMESTAMP NULL COMMENT 'datum zadosti o ukonceni premium clenstvi' AFTER `datum_zalozeni`;

insert into databaseversion (version, versiontype) values ('1.01', 'INC');