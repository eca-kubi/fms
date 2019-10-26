create table salary_advance_secretary
(
    id                  int auto_increment
        primary key,
    user_id             int null,
    department_assigned int null
);

INSERT INTO sms.assigned_as_secretary (id, user_id, department_id) VALUES (1, 26, 10);
INSERT INTO sms.assigned_as_secretary (id, user_id, department_id) VALUES (2, 28, 13);