INSERT INTO Actor (username, password) VALUES ('Vilmatesti', 'huonosalasana');
INSERT INTO Actor (username, password) VALUES ('toinen', 'jee');
INSERT INTO Task (actor_id, name, deadline) VALUES (1, 'Tiskaa', '2016-04-05');
INSERT INTO Category (actor_id, name) VALUES (1, 'Kotityö');
INSERT INTO TaskCategory (task_id, category_id) VALUES (1, 1);