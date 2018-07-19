BEGIN ;

DROP TABLE IF EXISTS deezer_payment.transaction;
DROP TABLE IF EXISTS deezer_payment.notification;

CREATE SCHEMA deezer_payment ;

CREATE SEQUENCE deezer_payment.transaction_id_seq;

CREATE TABLE deezer_payment.transaction (
	transaction_id varchar(45) DEFAULT 'TRX_'||NEXTVAL('deezer_payment.transaction_id_seq') PRIMARY KEY,
	status int NOT NULL ,
	date_create TIMESTAMPTZ NOT NULL DEFAULT now(),
	date_update TIMESTAMPTZ NOT NULL DEFAULT now(),
	date_authorized TIMESTAMPTZ,
	date_captured TIMESTAMPTZ,
	date_settled TIMESTAMPTZ,
	date_unpaid TIMESTAMPTZ
);


CREATE TABLE deezer_payment.notification (
	id serial PRIMARY KEY NOT NULL,
	transaction_id varchar(45)  NOT NULL,
	status int,
	date  TIMESTAMPTZ NOT NULL DEFAULT now()
);


COMMIT;