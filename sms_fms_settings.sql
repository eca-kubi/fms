create table fms_settings
(
    id    int auto_increment
        primary key,
    prop  varchar(300) null,
    value text         null
);

INSERT INTO sms.fms_settings (id, prop, value) VALUES (1, 'current_hr', '5');
INSERT INTO sms.fms_settings (id, prop, value) VALUES (2, 'current_fmgr', '19');