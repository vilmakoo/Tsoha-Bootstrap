CREATE TABLE Actor(
  id SERIAL PRIMARY KEY,
  username varchar(20) UNIQUE NOT NULL,
  password varchar(20) NOT NULL
);

CREATE TABLE Task(
  id SERIAL PRIMARY KEY,
  actor_id INTEGER REFERENCES Actor(id) NOT NULL,
  name varchar(50) NOT NULL,
  description varchar(400),
  priority INTEGER DEFAULT 0,
  done BOOLEAN DEFAULT FALSE,
  added DATE,
  deadline DATE NOT NULL
);

CREATE TABLE Category(
  id SERIAL PRIMARY KEY,
  actor_id INTEGER REFERENCES Actor(id) NOT NULL,
  name varchar(50) NOT NULL,
  description varchar(400)
);

CREATE TABLE TaskCategory (
  task_id INTEGER REFERENCES Task(id) NOT NULL,
  category_id INTEGER REFERENCES Category(id) NOT NULL,
  PRIMARY KEY (task_id, category_id)
);