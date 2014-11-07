BEGIN;

CREATE TABLE IF NOT EXISTS modeltemplates (
  id serial primary key,
  name varchar(255) not null,
  filename varchar(255) not null,
  filesize integer,
  content bytea not null,
  created timestamp without time zone NOT NULL,
  modified timestamp without time zone NOT NULL
);

-- Création des tables nécessaires à la validation des modèles
CREATE TABLE modeltypes (
  id serial primary key,
  name varchar(255),
  description varchar(255),
  created timestamp without time zone NOT NULL,
  modified timestamp without time zone NOT NULL
);

-- Insertion des types de modèle
INSERT INTO modeltypes VALUES ('1', 'Toutes Editions', 'Toutes Editions', now(), now());

ALTER TABLE modeltemplates ADD COLUMN modeltype_id INTEGER REFERENCES modeltypes(id) DEFAULT 1;

CREATE TABLE modelsections (
  id serial primary key,
  name varchar(255),
  description varchar(255),
  parent_id integer references modelsections(id) DEFAULT 1,
  created timestamp without time zone NOT NULL,
  modified timestamp without time zone NOT NULL
);
CREATE TABLE modelvariables (
  id serial primary key,
  name varchar(255),
  description varchar(255),
  created timestamp without time zone NOT NULL,
  modified timestamp without time zone NOT NULL
);
CREATE TABLE modelvalidations (
  id serial primary key,
  modelvariable_id integer references modelvariables(id),
  modelsection_id integer references modelsections(id) NOT NULL,
  modeltype_id integer references modeltypes(id) NOT NULL,
  min integer default 0,
  max integer default null,
  actif bool DEFAULT true
);
COMMIT;