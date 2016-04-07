
/*-----------------------------------------------------------------------------*/
/********************** TEST AND PRODUCTION DATABASE ***************************/
/*-----------------------------------------------------------------------------*/

ALTER TABLE `owner` ADD `dt_request_cancel` TIMESTAMP NULL COMMENT 'datum zadosti o ukonceni premium clenstvi' AFTER `datum_zalozeni`;
ALTER TABLE `owner` ADD `cancel_reason` TINYINT(1) NULL COMMENT 'duvod ukonceni premium clenstvi' AFTER `dt_request_cancel`;
ALTER TABLE `owner` ADD `cancel_notice` TEXT NULL COMMENT 'poznamka ukonceni premium clenstvi' AFTER `cancel_reason`;

/********************** END TEST AND PRODUCTION DATABASE ***********************/


/*-----------------------------------------------------------------------------*/
/*************************** ONLY TEST DATABASE ********************************/
/*-----------------------------------------------------------------------------*/

/*pesy testing row for cancel function */
INSERT INTO `owner` (`fb_id`, `jmeno`, `prijmeni`, `mesto_id`, `pohlavi`, `narozeniny`, `nabozenstvi`, `casova_zona`, `lokalizace`, `overeni_fb`, `posledni_fb_aktualizace`, `ip`, `datum_zalozeni`, `dt_request_cancel`, `cancel_reason`, `cancel_notice`, `prohlizec`, `email`, `email_contact`, `typ`, `cover_offset`, `prace_spolecnost`, `prace_pozice`, `telefon`, `web`, `lang`, `status`) 
             VALUES ('1256893992', 'Petr', 'Syrný', NULL, 'male', NULL, NULL, NULL, '', NULL, NULL, '', CURRENT_TIMESTAMP, NULL, NULL, NULL, '', 'petr.syrny@centrum.cz', NULL, 'premium', NULL, NULL, NULL, NULL, NULL, 'cs', 'active');

/*************************** END ONLY TEST DATABASE ****************************/


/*-----------------------------------------------------------------------------*/
/*************************** GLOBAL SQL FOR BOTH  ******************************/
/*-----------------------------------------------------------------------------*/
insert into databaseversion (version, versiontype) values ('1.01', 'INC');