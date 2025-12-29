

create database profejoaco;
-- -------------------------
-- CREACION DE TABLESPACES
-- -------------------------
CREATE TABLESPACE DATOS LOCATION '/profeJoaco/Datos';
CREATE TABLESPACE INDICES LOCATION '/profeJoaco/Indices';
CREATE USER joaco WITH PASSWORD 'joaco2002'; 
CREATE SCHEMA joacosch  AUTHORIZATION joaco;

CREATE TABLE joacosch.usuarios
(
	id      		SERIAL PRIMARY KEY,
    nombre			TEXT NOT NULL,
	apellido		TEXT,
	correo			text,
	fechanac 		date,
	Activo 			TEXT,
	fecharegistro 	TIMESTAMP,
	comentario		TEXT NOT NULL
	
) TABLESPACE DATOS;
ALTER TABLE joacosch.usuarios OWNER TO joaco;


CREATE TABLE joacosch.seguimiento
(
	id					serial primary key,
	id_usuario		 	integer,
    Fecha_seguimiento 	TIMESTAMP,
	comentario 			text
	
) TABLESPACE DATOS;
ALTER TABLE joacosch.seguimiento OWNER TO joaco;

CREATE TABLE joacosch.planes
(
	id 			SERIAL PRIMARY KEY,
    nombre 		TEXT,
	descripcion text,
	id_ejercicio integer
	
) TABLESPACE DATOS;
ALTER TABLE joacosch.planes OWNER TO joaco;


CREATE TABLE joacosch.ejercicios
(
	id 			SERIAL PRIMARY KEY,
    musculo 	TEXT,
	ejercicio 	text,
	explicacion text
	
) TABLESPACE DATOS;
ALTER TABLE joacosch.ejercicios OWNER TO joaco;

CREATE TABLE joacosch.plan_usuario
(
	id			serial primary key,
	id_plan 	integer,
    id_usuario 	integer,
	repeticiones	integer,
	series 		integer,
	comentarios	text,
	peso		FLOAT
) TABLESPACE DATOS;
ALTER TABLE joacosch.plan_usuario OWNER TO joaco;



--Llaves foraneas
alter table joacosch.plan_usuario add CONSTRAINT fk_planes  FOREIGN KEY ("id_plan")   REFERENCES joacosch.planes(id);
alter table joacosch.plan_usuario add constraint fk_usuario FOREIGN KEY ("id_usuario") REFERENCES joacosch.usuarios(id);
alter table joacosch.seguimiento  add constraint fk_usuario FOREIGN KEY ("id_usuario") REFERENCES joacosch.usuarios(id);
alter table joacosch.planes       add constraint fk_ejercicios FOREIGN KEY ("id_ejercicio") REFERENCES joacosch.ejercicios(id);
--alter table joacosch.puesto  add CONSTRAINT fk_proveedor    FOREIGN KEY ("proveedor_ID") REFERENCES equipo_ubic(id_proveedor);