#
# Table structure for table 'tx_twplaces_domain_model_place'
#
CREATE TABLE tx_twplaces_domain_model_place
(

    type             INT(11)             DEFAULT '0'    NOT NULL,
    name             VARCHAR(255)        DEFAULT ''     NOT NULL,
    given_name       VARCHAR(255)        DEFAULT ''     NOT NULL,
    family_name      VARCHAR(255)        DEFAULT ''     NOT NULL,
    latitude         DECIMAL(19, 16)     DEFAULT '0.00' NOT NULL,
    longitude        DECIMAL(19, 16)     DEFAULT '0.00' NOT NULL,


    description      TEXT                DEFAULT ''     NOT NULL,
    image            INT(11)             DEFAULT '0'    NOT NULL,
    postal_code      VARCHAR(255)        DEFAULT ''     NOT NULL,
    country          VARCHAR(255)        DEFAULT ''     NOT NULL,
    region           VARCHAR(255)        DEFAULT ''     NOT NULL,
    state            VARCHAR(255)        DEFAULT ''     NOT NULL,
    city             VARCHAR(255)        DEFAULT ''     NOT NULL,
    street_address   VARCHAR(255)        DEFAULT ''     NOT NULL,
    email            VARCHAR(255)        DEFAULT ''     NOT NULL,
    fax              VARCHAR(255)        DEFAULT ''     NOT NULL,
    phone            VARCHAR(255)        DEFAULT ''     NOT NULL,
    url              VARCHAR(255)        DEFAULT ''     NOT NULL,


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
