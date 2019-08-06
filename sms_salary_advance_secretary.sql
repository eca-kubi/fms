create table salary_advance_secretary
(
    id                  int auto_increment
        primary key,
    user_id             int null,
    department_assigned int null
);

INSERT INTO sms.salary_advance_secretary (id, user_id, department_assigned) VALUES (1, 26, 10);
INSERT INTO sms.salary_advance_secretary (id, user_id, department_assigned) VALUES (2, 28, 13);