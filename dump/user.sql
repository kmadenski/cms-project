INSERT INTO role (id,name) VALUES (1,'ROLE_USER');
INSERT INTO role (id,name) VALUES (2,'ROLE_ADMIN');
-- hasło w obu przypadkach to "123"
INSERT INTO person (id,email, password, name) VALUES(1,'user@mas.pl','$argon2i$v=19$m=65536,t=4,p=1$d2NHWkNTRVdYS2k5T0hIQw$WmDx/yBdlUn9O0y8Bzq2yVavwxDidZ+8eJ2GeuI/3Qc','example_user');
INSERT INTO person (id,email, password, name) VALUES(2,'admin@mas.pl','$argon2i$v=19$m=65536,t=4,p=1$d2NHWkNTRVdYS2k5T0hIQw$WmDx/yBdlUn9O0y8Bzq2yVavwxDidZ+8eJ2GeuI/3Qc','example_admin');

INSERT INTO person_role (person_id, role_id) VALUES (1,1);
INSERT INTO person_role (person_id, role_id) VALUES (2,2);
