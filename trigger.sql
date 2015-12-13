CREATE OR REPLACE FUNCTION estoque_compra()
  RETURNS trigger AS
$BODY$
    BEGIN
        IF (TG_OP = 'INSERT') THEN
            UPDATE produto set quantidade = quantidade+NEW.quantidade where NEW.produto_id = produto.id;
            RETURN NEW;
        END IF;
        RETURN NULL; 
    END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION estoque_compra()
  OWNER TO postgres;



CREATE OR REPLACE FUNCTION estoque_venda()
  RETURNS trigger AS
$BODY$
    BEGIN
        IF (TG_OP = 'INSERT') THEN
            UPDATE produto set quantidade = quantidade-NEW.quantidade where NEW.produto_id = produto.id;
            RETURN NEW;
        END IF;
        RETURN NULL; 
    END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION estoque_venda()
  OWNER TO postgres;