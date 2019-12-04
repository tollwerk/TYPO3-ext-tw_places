#
# Table structure for table 'tx_twplaces_domain_model_place'
#
CREATE TABLE tx_twplaces_domain_model_place
(

    name             VARCHAR(255)        DEFAULT ''     NOT NULL,
    latitude         DECIMAL(19, 16)     DEFAULT '0.00' NOT NULL,
    longitude        DECIMAL(19, 16)     DEFAULT '0.00' NOT NULL,

    uid              INT(11)                            NOT NULL AUTO_INCREMENT,
    pid              INT(11)             DEFAULT '0'    NOT NULL,
    sorting          INT(11)             DEFAULT '0'    NOT NULL,
    tstamp           INT(11) UNSIGNED    DEFAULT '0'    NOT NULL,
    crdate           INT(11) UNSIGNED    DEFAULT '0'    NOT NULL,
    cruser_id        INT(11) UNSIGNED    DEFAULT '0'    NOT NULL,
    deleted          TINYINT(4) UNSIGNED DEFAULT '0'    NOT NULL,
    hidden           TINYINT(4) UNSIGNED DEFAULT '0'    NOT NULL,
    starttime        INT(11) UNSIGNED    DEFAULT '0'    NOT NULL,
    endtime          INT(11) UNSIGNED    DEFAULT '0'    NOT NULL,
    sys_language_uid INT(11)             DEFAULT '0'    NOT NULL,
    l10n_parent      INT(11)             DEFAULT '0'    NOT NULL,
    l10n_diffsource  MEDIUMBLOB,

    PRIMARY KEY (uid),
    KEY parent (pid)
);