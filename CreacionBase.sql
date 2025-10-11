/usr/lib/postgresql/17/bin
/usr/local/pgsql/data

create database INV;
-- -------------------------
-- CREACION DE TABLESPACES
-- -------------------------
CREATE TABLESPACE DATOS LOCATION '/inventario/data/datos';
CREATE TABLESPACE INDICES LOCATION '/inventario/data/indices';

CREATE SCHEMA schinv  AUTHORIZATION inventario;

CREATE TABLE schinv.equipo
(
	id_equipo 		SERIAL PRIMARY KEY,
    codigo			TEXT NOT NULL,
	marca 			TEXT,
	modelo 			text,
	serie 			TEXT,
	estado 			TEXT,
	fecha_ingreso 	TIMESTAMP,
	tipo_id			INTEGER NOT NULL,
	puesto_id       INTEGER NOT NULL,
	proveedor_id    INTEGER
) TABLESPACE DATOS;
ALTER TABLE schinv.equipo OWNER TO inventario;


CREATE TABLE schinv.tipo_equipo
(
	id_tipo 	SERIAL PRIMARY KEY,
    nombre 		TEXT,
	descripcion text,
	estado integer
) TABLESPACE DATOS;
ALTER TABLE schinv.tipo_equipo OWNER TO inventario;

CREATE TABLE schinv.puesto
(
	id_puesto 	SERIAL PRIMARY KEY,
    nombre 		TEXT,
	descripcion text,
	duenio 		integer
) TABLESPACE DATOS;
ALTER TABLE schinv.puesto OWNER TO inventario;

CREATE TABLE schinv.equipo_ubic
(
	id_equipo integer,
    id_puesto integer
) TABLESPACE DATOS;
ALTER TABLE schinv.equipo_ubic OWNER TO inventario;

CREATE TABLE schinv.grupos
(
	id_grupo 	SERIAL PRIMARY KEY,
    nombre 		TEXT,
	descripcion text
) TABLESPACE DATOS;
ALTER TABLE schinv.grupos OWNER TO inventario;

CREATE TABLE schinv.usr_grupo
(
	id_grupo 	integer,
    id_usuario 	integer
) TABLESPACE DATOS;
ALTER TABLE schinv.usr_grupo OWNER TO inventario;


CREATE TABLE schinv.perm_tipo
(
	id_grupo 	integer,
    id_tipo 	integer
) TABLESPACE DATOS;
ALTER TABLE schinv.perm_tipo OWNER TO inventario;

CREATE TABLE schinv.persona
(
	id_persona 	SERIAL PRIMARY KEY,
    nombre     	TEXT,
	username 	text,
	tipo     	integer
) TABLESPACE DATOS;
ALTER TABLE schinv.persona OWNER TO inventario;

CREATE TABLE schinv.tipo_persona
(
	id_tipopersona 	SERIAL PRIMARY KEY,
    nombre     		TEXT,
	descripcion 	text,
	tipo     	integer
) TABLESPACE DATOS;
ALTER TABLE schinv.tipo_persona OWNER TO inventario;

CREATE TABLE schinv.bitacora
(
	id 			SERIAL PRIMARY KEY,
    fecha     	timestamp,
	accion	 	text,
	antes     	text,
	despues		text,
	objeto		text
) TABLESPACE DATOS;

CREATE TABLE schinv.proveedor
(
	id_proveedor SERIAL PRIMARY KEY,
    nombre     	 text,
	referente	 text,
	telefono   	text,
	direccion	text,
	timestamp	timestamp
) TABLESPACE DATOS;
ALTER TABLE schinv.proveedor OWNER TO inventario;

--Llaves foraneas
alter table schinv.equipos add CONSTRAINT fk_tipo_equipo  FOREIGN KEY ("tipo_ID")   REFERENCES schinv.tipo_equipo(id_tipo);
alter table schinv.equipos add constraint fk_PUESTO       FOREIGN KEY ("puesto_ID") REFERENCES schinv.puesto(id_puesto);
alter table schinv.equipos add constraint fk_provvedor    FOREIGN KEY ("proveedor_ID") REFERENCES schinv.proveedor(id_proveedor);
alter table schinv.puesto  add CONSTRAINT fk_proveedor    FOREIGN KEY ("proveedor_ID") REFERENCES equipo_ubic(id_proveedor);