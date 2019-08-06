create table salary_advance
(
    id_salary_advance   int auto_increment
        primary key,
    user_id             int                  null,
    percentage          float                null,
    date_raised         datetime             null,
    hod_approval_date   datetime             null,
    amount_payable      float                null,
    hr_approval_date    datetime             null,
    hod_id              int                  null,
    hr_id               int                  null,
    fmgr_id             int                  null,
    fmgr_approval_date  datetime             null,
    amount_received     float                null,
    date_received       datetime             null,
    amount_approved     float                null,
    status              varchar(60)          null,
    amount_requested    float                null,
    department_id       int                  null,
    hod_comment         text                 null,
    hr_comment          text                 null,
    fmgr_comment        text                 null,
    hod_approval        tinyint(1) default 0 null,
    fmgr_approval       tinyint    default 0 null,
    hr_approval         tinyint    default 0 null,
    department_ref      varchar(300)         null,
    raised_by_secretary tinyint    default 0 null,
    received_by         text                 null,
    raised_by_id        int                  null,
    deleted             tinyint(1) default 0 null
);

INSERT INTO sms.salary_advance (id_salary_advance, user_id, percentage, date_raised, hod_approval_date, amount_payable, hr_approval_date, hod_id, hr_id, fmgr_id, fmgr_approval_date, amount_received, date_received, amount_approved, status, amount_requested, department_id, hod_comment, hr_comment, fmgr_comment, hod_approval, fmgr_approval, hr_approval, department_ref, raised_by_secretary, received_by, raised_by_id, deleted) VALUES (68, 2, null, '2019-05-26 15:32:42', null, 2100, '2019-05-27 15:06:52', null, 5, 19, '2019-05-27 17:06:33', null, null, 2000, null, 2500, 9, 'ok', 'ok', 'oka', 1, 1, 1, 'ITX-008', 0, null, null, 0);
INSERT INTO sms.salary_advance (id_salary_advance, user_id, percentage, date_raised, hod_approval_date, amount_payable, hr_approval_date, hod_id, hr_id, fmgr_id, fmgr_approval_date, amount_received, date_received, amount_approved, status, amount_requested, department_id, hod_comment, hr_comment, fmgr_comment, hod_approval, fmgr_approval, hr_approval, department_ref, raised_by_secretary, received_by, raised_by_id, deleted) VALUES (69, 2, null, '2019-05-26 15:36:24', '2019-05-27 18:44:21', 300, '2019-05-27 18:46:04', 3, 5, 19, '2019-05-27 18:49:35', null, null, 300, null, 350, 9, 'okay', 'ok', 'ok', 1, 1, 1, 'ITX-009', 0, null, null, 1);
INSERT INTO sms.salary_advance (id_salary_advance, user_id, percentage, date_raised, hod_approval_date, amount_payable, hr_approval_date, hod_id, hr_id, fmgr_id, fmgr_approval_date, amount_received, date_received, amount_approved, status, amount_requested, department_id, hod_comment, hr_comment, fmgr_comment, hod_approval, fmgr_approval, hr_approval, department_ref, raised_by_secretary, received_by, raised_by_id, deleted) VALUES (70, 2, null, '2019-05-26 15:36:36', null, null, null, null, null, null, null, null, null, null, null, 430, 9, null, null, null, 0, 0, 0, 'ITX-010', 0, null, null, 1);
INSERT INTO sms.salary_advance (id_salary_advance, user_id, percentage, date_raised, hod_approval_date, amount_payable, hr_approval_date, hod_id, hr_id, fmgr_id, fmgr_approval_date, amount_received, date_received, amount_approved, status, amount_requested, department_id, hod_comment, hr_comment, fmgr_comment, hod_approval, fmgr_approval, hr_approval, department_ref, raised_by_secretary, received_by, raised_by_id, deleted) VALUES (71, null, null, '2019-05-27 15:40:51', null, null, null, null, null, null, null, null, null, null, null, 100, null, null, null, null, 0, 0, 0, '-001', 0, null, null, 0);
INSERT INTO sms.salary_advance (id_salary_advance, user_id, percentage, date_raised, hod_approval_date, amount_payable, hr_approval_date, hod_id, hr_id, fmgr_id, fmgr_approval_date, amount_received, date_received, amount_approved, status, amount_requested, department_id, hod_comment, hr_comment, fmgr_comment, hod_approval, fmgr_approval, hr_approval, department_ref, raised_by_secretary, received_by, raised_by_id, deleted) VALUES (72, 11, null, '2019-05-27 15:45:47', null, null, null, null, null, null, null, null, null, null, null, 4000, 13, null, null, null, 0, 0, 0, null, 1, null, null, 1);
INSERT INTO sms.salary_advance (id_salary_advance, user_id, percentage, date_raised, hod_approval_date, amount_payable, hr_approval_date, hod_id, hr_id, fmgr_id, fmgr_approval_date, amount_received, date_received, amount_approved, status, amount_requested, department_id, hod_comment, hr_comment, fmgr_comment, hod_approval, fmgr_approval, hr_approval, department_ref, raised_by_secretary, received_by, raised_by_id, deleted) VALUES (73, 29, null, '2019-05-28 03:10:31', null, null, null, null, null, null, null, null, null, null, null, 400, 13, null, null, null, 0, 0, 0, '-001', 1, null, 28, 1);
INSERT INTO sms.salary_advance (id_salary_advance, user_id, percentage, date_raised, hod_approval_date, amount_payable, hr_approval_date, hod_id, hr_id, fmgr_id, fmgr_approval_date, amount_received, date_received, amount_approved, status, amount_requested, department_id, hod_comment, hr_comment, fmgr_comment, hod_approval, fmgr_approval, hr_approval, department_ref, raised_by_secretary, received_by, raised_by_id, deleted) VALUES (74, 29, null, '2019-06-28 03:11:40', null, null, null, null, null, null, null, null, null, null, null, 400, 13, null, null, null, 0, 0, 0, 'PRO-003', 1, null, 28, 1);
INSERT INTO sms.salary_advance (id_salary_advance, user_id, percentage, date_raised, hod_approval_date, amount_payable, hr_approval_date, hod_id, hr_id, fmgr_id, fmgr_approval_date, amount_received, date_received, amount_approved, status, amount_requested, department_id, hod_comment, hr_comment, fmgr_comment, hod_approval, fmgr_approval, hr_approval, department_ref, raised_by_secretary, received_by, raised_by_id, deleted) VALUES (75, 29, null, '2019-05-28 03:12:28', '2019-05-28 04:55:42', 500, '2019-05-28 04:57:38', 11, 5, 19, '2019-05-28 14:14:18', 500, '2019-05-29 04:20:01', 500, null, 800, 13, 'okay', 'okay', 'okay', 1, 1, 1, 'PRO-004', 1, 'Joseph Ngamah Kwofie', 28, 0);