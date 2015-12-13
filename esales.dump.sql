--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- Name: estoque_compra(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION estoque_compra() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    BEGIN
        IF (TG_OP = 'INSERT') THEN
            UPDATE produto set quantidade = quantidade+NEW.quantidade where NEW.produto_id = produto.id;
            RETURN NEW;
        END IF;
        RETURN NULL; 
    END;
$$;


ALTER FUNCTION public.estoque_compra() OWNER TO postgres;

--
-- Name: estoque_venda(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION estoque_venda() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    BEGIN
        --
        -- Create a row in emp_audit to reflect the operation performed on emp,
        -- make use of the special variable TG_OP to work out the operation.
        --
        IF (TG_OP = 'INSERT') THEN
            UPDATE produto set quantidade = quantidade-NEW.quantidade where NEW.produto_id = produto.id;
            RETURN NEW;
        END IF;
        RETURN NULL; -- result is ignored since this is an AFTER trigger
    END;
$$;


ALTER FUNCTION public.estoque_venda() OWNER TO postgres;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: caixa; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE caixa (
    id integer NOT NULL,
    descricao character varying(45),
    pessoa_id integer NOT NULL,
    saldo_inicial character varying(45),
    filial_id integer NOT NULL
);


ALTER TABLE caixa OWNER TO postgres;

--
-- Name: caixa_filial_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE caixa_filial_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE caixa_filial_id_seq OWNER TO postgres;

--
-- Name: caixa_filial_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE caixa_filial_id_seq OWNED BY caixa.filial_id;


--
-- Name: caixa_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE caixa_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE caixa_id_seq OWNER TO postgres;

--
-- Name: caixa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE caixa_id_seq OWNED BY caixa.id;


--
-- Name: caixa_pessoa_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE caixa_pessoa_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE caixa_pessoa_id_seq OWNER TO postgres;

--
-- Name: caixa_pessoa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE caixa_pessoa_id_seq OWNED BY caixa.pessoa_id;


--
-- Name: cidade; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cidade (
    id integer NOT NULL,
    descricao character varying(45)
);


ALTER TABLE cidade OWNER TO postgres;

--
-- Name: cidade_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cidade_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE cidade_id_seq OWNER TO postgres;

--
-- Name: cidade_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cidade_id_seq OWNED BY cidade.id;


--
-- Name: compra; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE compra (
    id integer NOT NULL,
    vendedor_id integer NOT NULL,
    filial_id integer NOT NULL,
    data_compra timestamp without time zone DEFAULT now(),
    comprador_id integer
);


ALTER TABLE compra OWNER TO postgres;

--
-- Name: compra_filial_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE compra_filial_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE compra_filial_id_seq OWNER TO postgres;

--
-- Name: compra_filial_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE compra_filial_id_seq OWNED BY compra.filial_id;


--
-- Name: compra_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE compra_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE compra_id_seq OWNER TO postgres;

--
-- Name: compra_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE compra_id_seq OWNED BY compra.id;


--
-- Name: compra_item; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE compra_item (
    id integer NOT NULL,
    compra_id integer NOT NULL,
    produto_id integer NOT NULL,
    valor_unitario character varying(45),
    quantidade integer
);


ALTER TABLE compra_item OWNER TO postgres;

--
-- Name: compra_item_compra_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE compra_item_compra_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE compra_item_compra_id_seq OWNER TO postgres;

--
-- Name: compra_item_compra_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE compra_item_compra_id_seq OWNED BY compra_item.compra_id;


--
-- Name: compra_item_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE compra_item_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE compra_item_id_seq OWNER TO postgres;

--
-- Name: compra_item_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE compra_item_id_seq OWNED BY compra_item.id;


--
-- Name: compra_item_produto_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE compra_item_produto_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE compra_item_produto_id_seq OWNER TO postgres;

--
-- Name: compra_item_produto_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE compra_item_produto_id_seq OWNED BY compra_item.produto_id;


--
-- Name: compra_vendedor_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE compra_vendedor_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE compra_vendedor_id_seq OWNER TO postgres;

--
-- Name: compra_vendedor_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE compra_vendedor_id_seq OWNED BY compra.vendedor_id;


--
-- Name: entrada; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE entrada (
    id integer NOT NULL,
    data date,
    valor character varying(45),
    caixa_id integer NOT NULL
);


ALTER TABLE entrada OWNER TO postgres;

--
-- Name: entrada_caixa_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE entrada_caixa_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE entrada_caixa_id_seq OWNER TO postgres;

--
-- Name: entrada_caixa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE entrada_caixa_id_seq OWNED BY entrada.caixa_id;


--
-- Name: entrada_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE entrada_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE entrada_id_seq OWNER TO postgres;

--
-- Name: entrada_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE entrada_id_seq OWNED BY entrada.id;


--
-- Name: filial; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE filial (
    id integer NOT NULL,
    nome character varying(45),
    endereco character varying(45),
    numero character varying(45),
    email character varying(45),
    telefone character varying(45),
    fl_matriz character varying(45)
);


ALTER TABLE filial OWNER TO postgres;

--
-- Name: filial_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE filial_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE filial_id_seq OWNER TO postgres;

--
-- Name: filial_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE filial_id_seq OWNED BY filial.id;


--
-- Name: log; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE log (
    id integer NOT NULL,
    tabela character varying(45),
    sql character varying(45)
);


ALTER TABLE log OWNER TO postgres;

--
-- Name: log_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE log_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE log_id_seq OWNER TO postgres;

--
-- Name: log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE log_id_seq OWNED BY log.id;


--
-- Name: marca; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE marca (
    id integer NOT NULL,
    descricao character varying(45)
);


ALTER TABLE marca OWNER TO postgres;

--
-- Name: marca_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE marca_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE marca_id_seq OWNER TO postgres;

--
-- Name: marca_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE marca_id_seq OWNED BY marca.id;


--
-- Name: pessoa; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE pessoa (
    id integer NOT NULL,
    nome character varying(45),
    telefone character varying(45),
    email character varying(45),
    endereco character varying(45),
    numero character varying(45),
    cidade_id integer NOT NULL,
    cep character varying(45),
    cpf_cnpj text,
    tipo_pessoa character(1)
);


ALTER TABLE pessoa OWNER TO postgres;

--
-- Name: pessoa_cidade_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE pessoa_cidade_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE pessoa_cidade_id_seq OWNER TO postgres;

--
-- Name: pessoa_cidade_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE pessoa_cidade_id_seq OWNED BY pessoa.cidade_id;


--
-- Name: pessoa_fisica; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE pessoa_fisica (
    pessoa_id integer NOT NULL,
    cpf character varying(45),
    data_nascimento character varying(45),
    sexo character varying(45)
);


ALTER TABLE pessoa_fisica OWNER TO postgres;

--
-- Name: pessoa_fisica_pessoa_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE pessoa_fisica_pessoa_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE pessoa_fisica_pessoa_id_seq OWNER TO postgres;

--
-- Name: pessoa_fisica_pessoa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE pessoa_fisica_pessoa_id_seq OWNED BY pessoa_fisica.pessoa_id;


--
-- Name: pessoa_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE pessoa_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE pessoa_id_seq OWNER TO postgres;

--
-- Name: pessoa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE pessoa_id_seq OWNED BY pessoa.id;


--
-- Name: pessoa_juridica; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE pessoa_juridica (
    pessoa_id integer NOT NULL,
    cnpj character varying(45),
    data_fundacao character varying(45),
    nome_fantasia character varying(45)
);


ALTER TABLE pessoa_juridica OWNER TO postgres;

--
-- Name: pessoa_juridica_pessoa_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE pessoa_juridica_pessoa_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE pessoa_juridica_pessoa_id_seq OWNER TO postgres;

--
-- Name: pessoa_juridica_pessoa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE pessoa_juridica_pessoa_id_seq OWNED BY pessoa_juridica.pessoa_id;


--
-- Name: produto; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE produto (
    id integer NOT NULL,
    descricao character varying(45),
    preco_compra character varying(45),
    preco_venda character varying(45),
    marca_id integer NOT NULL,
    quantidade integer
);


ALTER TABLE produto OWNER TO postgres;

--
-- Name: produto_filial; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE produto_filial (
    id integer NOT NULL,
    produto_id integer NOT NULL,
    filial_id integer NOT NULL,
    quantidade character varying(45)
);


ALTER TABLE produto_filial OWNER TO postgres;

--
-- Name: produto_filial_filial_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE produto_filial_filial_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE produto_filial_filial_id_seq OWNER TO postgres;

--
-- Name: produto_filial_filial_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE produto_filial_filial_id_seq OWNED BY produto_filial.filial_id;


--
-- Name: produto_filial_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE produto_filial_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE produto_filial_id_seq OWNER TO postgres;

--
-- Name: produto_filial_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE produto_filial_id_seq OWNED BY produto_filial.id;


--
-- Name: produto_filial_produto_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE produto_filial_produto_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE produto_filial_produto_id_seq OWNER TO postgres;

--
-- Name: produto_filial_produto_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE produto_filial_produto_id_seq OWNED BY produto_filial.produto_id;


--
-- Name: produto_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE produto_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE produto_id_seq OWNER TO postgres;

--
-- Name: produto_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE produto_id_seq OWNED BY produto.id;


--
-- Name: produto_marca_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE produto_marca_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE produto_marca_id_seq OWNER TO postgres;

--
-- Name: produto_marca_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE produto_marca_id_seq OWNED BY produto.marca_id;


--
-- Name: saida; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE saida (
    id integer NOT NULL,
    data date,
    valor character varying(45),
    caixa_id integer NOT NULL
);


ALTER TABLE saida OWNER TO postgres;

--
-- Name: saida_caixa_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE saida_caixa_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE saida_caixa_id_seq OWNER TO postgres;

--
-- Name: saida_caixa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE saida_caixa_id_seq OWNED BY saida.caixa_id;


--
-- Name: saida_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE saida_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE saida_id_seq OWNER TO postgres;

--
-- Name: saida_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE saida_id_seq OWNED BY saida.id;


--
-- Name: system_group; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE system_group (
    id integer NOT NULL,
    name character varying(45)
);


ALTER TABLE system_group OWNER TO postgres;

--
-- Name: system_group_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE system_group_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system_group_id_seq OWNER TO postgres;

--
-- Name: system_group_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE system_group_id_seq OWNED BY system_group.id;


--
-- Name: system_group_program; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE system_group_program (
    id integer NOT NULL,
    system_program_id integer NOT NULL,
    system_group_id integer NOT NULL,
    action character varying(45)
);


ALTER TABLE system_group_program OWNER TO postgres;

--
-- Name: system_group_program_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE system_group_program_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system_group_program_id_seq OWNER TO postgres;

--
-- Name: system_group_program_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE system_group_program_id_seq OWNED BY system_group_program.id;


--
-- Name: system_group_program_system_group_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE system_group_program_system_group_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system_group_program_system_group_id_seq OWNER TO postgres;

--
-- Name: system_group_program_system_group_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE system_group_program_system_group_id_seq OWNED BY system_group_program.system_group_id;


--
-- Name: system_group_program_system_program_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE system_group_program_system_program_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system_group_program_system_program_id_seq OWNER TO postgres;

--
-- Name: system_group_program_system_program_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE system_group_program_system_program_id_seq OWNED BY system_group_program.system_program_id;


--
-- Name: system_program; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE system_program (
    id integer NOT NULL,
    name character varying(45),
    controller character varying(45)
);


ALTER TABLE system_program OWNER TO postgres;

--
-- Name: system_program_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE system_program_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system_program_id_seq OWNER TO postgres;

--
-- Name: system_program_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE system_program_id_seq OWNED BY system_program.id;


--
-- Name: system_user; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE system_user (
    id integer NOT NULL,
    name character varying(45),
    login character varying(45),
    password character varying(45),
    email character varying(45),
    frontpage_id character varying(45),
    filial_id integer NOT NULL
);


ALTER TABLE system_user OWNER TO postgres;

--
-- Name: system_user_filial_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE system_user_filial_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system_user_filial_id_seq OWNER TO postgres;

--
-- Name: system_user_filial_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE system_user_filial_id_seq OWNED BY system_user.filial_id;


--
-- Name: system_user_group; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE system_user_group (
    id integer NOT NULL,
    system_user_id integer NOT NULL,
    system_group_id integer NOT NULL
);


ALTER TABLE system_user_group OWNER TO postgres;

--
-- Name: system_user_group_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE system_user_group_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system_user_group_id_seq OWNER TO postgres;

--
-- Name: system_user_group_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE system_user_group_id_seq OWNED BY system_user_group.id;


--
-- Name: system_user_group_system_group_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE system_user_group_system_group_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system_user_group_system_group_id_seq OWNER TO postgres;

--
-- Name: system_user_group_system_group_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE system_user_group_system_group_id_seq OWNED BY system_user_group.system_group_id;


--
-- Name: system_user_group_system_user_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE system_user_group_system_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system_user_group_system_user_id_seq OWNER TO postgres;

--
-- Name: system_user_group_system_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE system_user_group_system_user_id_seq OWNED BY system_user_group.system_user_id;


--
-- Name: system_user_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE system_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system_user_id_seq OWNER TO postgres;

--
-- Name: system_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE system_user_id_seq OWNED BY system_user.id;


--
-- Name: system_user_program; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE system_user_program (
    id integer NOT NULL,
    system_user_id integer NOT NULL,
    system_program_id integer NOT NULL,
    action character varying(45)
);


ALTER TABLE system_user_program OWNER TO postgres;

--
-- Name: system_user_program_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE system_user_program_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system_user_program_id_seq OWNER TO postgres;

--
-- Name: system_user_program_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE system_user_program_id_seq OWNED BY system_user_program.id;


--
-- Name: system_user_program_system_program_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE system_user_program_system_program_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system_user_program_system_program_id_seq OWNER TO postgres;

--
-- Name: system_user_program_system_program_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE system_user_program_system_program_id_seq OWNED BY system_user_program.system_program_id;


--
-- Name: system_user_program_system_user_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE system_user_program_system_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE system_user_program_system_user_id_seq OWNER TO postgres;

--
-- Name: system_user_program_system_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE system_user_program_system_user_id_seq OWNED BY system_user_program.system_user_id;


--
-- Name: venda; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE venda (
    id integer NOT NULL,
    vendedor_id integer NOT NULL,
    comprador_id integer NOT NULL,
    filial_id integer NOT NULL,
    data_venda timestamp without time zone DEFAULT now()
);


ALTER TABLE venda OWNER TO postgres;

--
-- Name: v_vendas; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW v_vendas AS
 SELECT v.id AS venda_id,
    v.comprador_id,
    v.vendedor_id,
    v.filial_id,
    f.nome AS filial_nome,
    date(v.data_venda) AS dt_venda_formatada,
    comprador.nome AS comprador_nome,
    vendedor.nome AS vendedor_nome
   FROM venda v,
    pessoa vendedor,
    pessoa comprador,
    filial f
  WHERE (((v.filial_id = f.id) AND (v.comprador_id = comprador.id)) AND (v.vendedor_id = vendedor.id));


ALTER TABLE v_vendas OWNER TO postgres;

--
-- Name: venda_comprador_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE venda_comprador_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE venda_comprador_id_seq OWNER TO postgres;

--
-- Name: venda_comprador_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE venda_comprador_id_seq OWNED BY venda.comprador_id;


--
-- Name: venda_filial_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE venda_filial_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE venda_filial_id_seq OWNER TO postgres;

--
-- Name: venda_filial_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE venda_filial_id_seq OWNED BY venda.filial_id;


--
-- Name: venda_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE venda_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE venda_id_seq OWNER TO postgres;

--
-- Name: venda_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE venda_id_seq OWNED BY venda.id;


--
-- Name: venda_item; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE venda_item (
    id integer NOT NULL,
    produto_id integer NOT NULL,
    venda_id integer NOT NULL,
    valor_unitario character varying(45),
    quantidade integer
);


ALTER TABLE venda_item OWNER TO postgres;

--
-- Name: venda_item_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE venda_item_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE venda_item_id_seq OWNER TO postgres;

--
-- Name: venda_item_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE venda_item_id_seq OWNED BY venda_item.id;


--
-- Name: venda_item_produto_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE venda_item_produto_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE venda_item_produto_id_seq OWNER TO postgres;

--
-- Name: venda_item_produto_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE venda_item_produto_id_seq OWNED BY venda_item.produto_id;


--
-- Name: venda_item_venda_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE venda_item_venda_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE venda_item_venda_id_seq OWNER TO postgres;

--
-- Name: venda_item_venda_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE venda_item_venda_id_seq OWNED BY venda_item.venda_id;


--
-- Name: venda_vendedor_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE venda_vendedor_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE venda_vendedor_id_seq OWNER TO postgres;

--
-- Name: venda_vendedor_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE venda_vendedor_id_seq OWNED BY venda.vendedor_id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY caixa ALTER COLUMN id SET DEFAULT nextval('caixa_id_seq'::regclass);


--
-- Name: pessoa_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY caixa ALTER COLUMN pessoa_id SET DEFAULT nextval('caixa_pessoa_id_seq'::regclass);


--
-- Name: filial_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY caixa ALTER COLUMN filial_id SET DEFAULT nextval('caixa_filial_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY cidade ALTER COLUMN id SET DEFAULT nextval('cidade_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY compra ALTER COLUMN id SET DEFAULT nextval('compra_id_seq'::regclass);


--
-- Name: vendedor_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY compra ALTER COLUMN vendedor_id SET DEFAULT nextval('compra_vendedor_id_seq'::regclass);


--
-- Name: filial_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY compra ALTER COLUMN filial_id SET DEFAULT nextval('compra_filial_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY compra_item ALTER COLUMN id SET DEFAULT nextval('compra_item_id_seq'::regclass);


--
-- Name: compra_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY compra_item ALTER COLUMN compra_id SET DEFAULT nextval('compra_item_compra_id_seq'::regclass);


--
-- Name: produto_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY compra_item ALTER COLUMN produto_id SET DEFAULT nextval('compra_item_produto_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entrada ALTER COLUMN id SET DEFAULT nextval('entrada_id_seq'::regclass);


--
-- Name: caixa_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entrada ALTER COLUMN caixa_id SET DEFAULT nextval('entrada_caixa_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY filial ALTER COLUMN id SET DEFAULT nextval('filial_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY log ALTER COLUMN id SET DEFAULT nextval('log_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY marca ALTER COLUMN id SET DEFAULT nextval('marca_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa ALTER COLUMN id SET DEFAULT nextval('pessoa_id_seq'::regclass);


--
-- Name: cidade_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa ALTER COLUMN cidade_id SET DEFAULT nextval('pessoa_cidade_id_seq'::regclass);


--
-- Name: pessoa_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa_fisica ALTER COLUMN pessoa_id SET DEFAULT nextval('pessoa_fisica_pessoa_id_seq'::regclass);


--
-- Name: pessoa_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa_juridica ALTER COLUMN pessoa_id SET DEFAULT nextval('pessoa_juridica_pessoa_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY produto ALTER COLUMN id SET DEFAULT nextval('produto_id_seq'::regclass);


--
-- Name: marca_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY produto ALTER COLUMN marca_id SET DEFAULT nextval('produto_marca_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY produto_filial ALTER COLUMN id SET DEFAULT nextval('produto_filial_id_seq'::regclass);


--
-- Name: produto_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY produto_filial ALTER COLUMN produto_id SET DEFAULT nextval('produto_filial_produto_id_seq'::regclass);


--
-- Name: filial_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY produto_filial ALTER COLUMN filial_id SET DEFAULT nextval('produto_filial_filial_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY saida ALTER COLUMN id SET DEFAULT nextval('saida_id_seq'::regclass);


--
-- Name: caixa_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY saida ALTER COLUMN caixa_id SET DEFAULT nextval('saida_caixa_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_group ALTER COLUMN id SET DEFAULT nextval('system_group_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_group_program ALTER COLUMN id SET DEFAULT nextval('system_group_program_id_seq'::regclass);


--
-- Name: system_program_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_group_program ALTER COLUMN system_program_id SET DEFAULT nextval('system_group_program_system_program_id_seq'::regclass);


--
-- Name: system_group_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_group_program ALTER COLUMN system_group_id SET DEFAULT nextval('system_group_program_system_group_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_program ALTER COLUMN id SET DEFAULT nextval('system_program_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_user ALTER COLUMN id SET DEFAULT nextval('system_user_id_seq'::regclass);


--
-- Name: filial_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_user ALTER COLUMN filial_id SET DEFAULT nextval('system_user_filial_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_user_group ALTER COLUMN id SET DEFAULT nextval('system_user_group_id_seq'::regclass);


--
-- Name: system_user_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_user_group ALTER COLUMN system_user_id SET DEFAULT nextval('system_user_group_system_user_id_seq'::regclass);


--
-- Name: system_group_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_user_group ALTER COLUMN system_group_id SET DEFAULT nextval('system_user_group_system_group_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_user_program ALTER COLUMN id SET DEFAULT nextval('system_user_program_id_seq'::regclass);


--
-- Name: system_user_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_user_program ALTER COLUMN system_user_id SET DEFAULT nextval('system_user_program_system_user_id_seq'::regclass);


--
-- Name: system_program_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_user_program ALTER COLUMN system_program_id SET DEFAULT nextval('system_user_program_system_program_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY venda ALTER COLUMN id SET DEFAULT nextval('venda_id_seq'::regclass);


--
-- Name: vendedor_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY venda ALTER COLUMN vendedor_id SET DEFAULT nextval('venda_vendedor_id_seq'::regclass);


--
-- Name: comprador_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY venda ALTER COLUMN comprador_id SET DEFAULT nextval('venda_comprador_id_seq'::regclass);


--
-- Name: filial_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY venda ALTER COLUMN filial_id SET DEFAULT nextval('venda_filial_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY venda_item ALTER COLUMN id SET DEFAULT nextval('venda_item_id_seq'::regclass);


--
-- Name: produto_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY venda_item ALTER COLUMN produto_id SET DEFAULT nextval('venda_item_produto_id_seq'::regclass);


--
-- Name: venda_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY venda_item ALTER COLUMN venda_id SET DEFAULT nextval('venda_item_venda_id_seq'::regclass);


--
-- Data for Name: caixa; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY caixa (id, descricao, pessoa_id, saldo_inicial, filial_id) FROM stdin;
\.


--
-- Name: caixa_filial_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('caixa_filial_id_seq', 1, false);


--
-- Name: caixa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('caixa_id_seq', 1, false);


--
-- Name: caixa_pessoa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('caixa_pessoa_id_seq', 1, false);


--
-- Data for Name: cidade; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY cidade (id, descricao) FROM stdin;
1	Teutônia
2	Lajeado
3	Arroio do meio
4	Estrela
5	Paverama
6	Porto Alegre
7	Canoas
8	São leopoldo
9	Venâncio Aires
10	Santa Cruz do Sul
\.


--
-- Name: cidade_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cidade_id_seq', 10, true);


--
-- Data for Name: compra; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY compra (id, vendedor_id, filial_id, data_compra, comprador_id) FROM stdin;
1	1	1	2015-12-13 18:48:14.323724	2
\.


--
-- Name: compra_filial_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('compra_filial_id_seq', 1, false);


--
-- Name: compra_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('compra_id_seq', 1, true);


--
-- Data for Name: compra_item; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY compra_item (id, compra_id, produto_id, valor_unitario, quantidade) FROM stdin;
1	1	3	\N	10
2	1	2	\N	14
\.


--
-- Name: compra_item_compra_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('compra_item_compra_id_seq', 1, false);


--
-- Name: compra_item_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('compra_item_id_seq', 2, true);


--
-- Name: compra_item_produto_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('compra_item_produto_id_seq', 1, false);


--
-- Name: compra_vendedor_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('compra_vendedor_id_seq', 1, false);


--
-- Data for Name: entrada; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY entrada (id, data, valor, caixa_id) FROM stdin;
\.


--
-- Name: entrada_caixa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('entrada_caixa_id_seq', 1, false);


--
-- Name: entrada_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('entrada_id_seq', 1, false);


--
-- Data for Name: filial; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY filial (id, nome, endereco, numero, email, telefone, fl_matriz) FROM stdin;
1	Filial Teutonia	Ernani Julio	789	klausetgeton@gmail.com	5181785755	\N
\.


--
-- Name: filial_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('filial_id_seq', 1, true);


--
-- Data for Name: log; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY log (id, tabela, sql) FROM stdin;
\.


--
-- Name: log_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('log_id_seq', 1, false);


--
-- Data for Name: marca; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY marca (id, descricao) FROM stdin;
4	Nike
1	Cocacola
2	Guaranaaaa
\.


--
-- Name: marca_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('marca_id_seq', 1, true);


--
-- Data for Name: pessoa; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY pessoa (id, nome, telefone, email, endereco, numero, cidade_id, cep, cpf_cnpj, tipo_pessoa) FROM stdin;
1	Klaus	51 81785755	klausetgeton@gmail.com	erna	98	2	0192830198	02699795062	F
2	Vendedor	0923810293809	vendedor@vend.com.br	rua das vendas	1908	2	0981236876	998029823768	J
\.


--
-- Name: pessoa_cidade_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('pessoa_cidade_id_seq', 1, false);


--
-- Data for Name: pessoa_fisica; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY pessoa_fisica (pessoa_id, cpf, data_nascimento, sexo) FROM stdin;
\.


--
-- Name: pessoa_fisica_pessoa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('pessoa_fisica_pessoa_id_seq', 1, false);


--
-- Name: pessoa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('pessoa_id_seq', 2, true);


--
-- Data for Name: pessoa_juridica; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY pessoa_juridica (pessoa_id, cnpj, data_fundacao, nome_fantasia) FROM stdin;
\.


--
-- Name: pessoa_juridica_pessoa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('pessoa_juridica_pessoa_id_seq', 1, false);


--
-- Data for Name: produto; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY produto (id, descricao, preco_compra, preco_venda, marca_id, quantidade) FROM stdin;
1	Coca 2 1,5L	10	150	1	\N
2	Coca Cola	20	30	1	20
3	Pepsi	20	30	1	20
4	Arroz	20	30	1	20
5	Feijão	20	30	1	20
6	Massa	20	30	1	20
7	Carne	20	30	1	20
8	Bolacha	20	30	1	20
9	Biscoito	20	30	1	20
10	Waffler	20	30	1	20
11	Laranja	20	30	1	20
12	Bergamota	20	30	1	20
13	Abacate	20	30	1	20
\.


--
-- Data for Name: produto_filial; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY produto_filial (id, produto_id, filial_id, quantidade) FROM stdin;
\.


--
-- Name: produto_filial_filial_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('produto_filial_filial_id_seq', 1, false);


--
-- Name: produto_filial_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('produto_filial_id_seq', 1, false);


--
-- Name: produto_filial_produto_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('produto_filial_produto_id_seq', 1, false);


--
-- Name: produto_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('produto_id_seq', 13, true);


--
-- Name: produto_marca_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('produto_marca_id_seq', 1, false);


--
-- Data for Name: saida; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY saida (id, data, valor, caixa_id) FROM stdin;
\.


--
-- Name: saida_caixa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('saida_caixa_id_seq', 1, false);


--
-- Name: saida_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('saida_id_seq', 1, false);


--
-- Data for Name: system_group; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY system_group (id, name) FROM stdin;
1	Admin
\.


--
-- Name: system_group_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('system_group_id_seq', 1, false);


--
-- Data for Name: system_group_program; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY system_group_program (id, system_program_id, system_group_id, action) FROM stdin;
1	4	1	\N
2	5	1	\N
3	6	1	\N
4	7	1	\N
5	8	1	\N
6	9	1	\N
7	10	1	\N
8	11	1	\N
9	12	1	\N
10	13	1	\N
11	14	1	\N
\.


--
-- Name: system_group_program_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('system_group_program_id_seq', 1, false);


--
-- Name: system_group_program_system_group_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('system_group_program_system_group_id_seq', 1, false);


--
-- Name: system_group_program_system_program_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('system_group_program_system_program_id_seq', 1, false);


--
-- Data for Name: system_program; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY system_program (id, name, controller) FROM stdin;
4	Grupos	SystemGroupList
5	Filial	FilialList
6	Pessoa	PessoaList
7	Marca	MarcaList
8	Produto	ProdutoList
9	Compra	CompraList
10	Venda	VendaList
11	Relatorio Vendas	VendasReport
12	Admin - Programas	SystemProgramList
13	Admin - Usuarios	SystemUserList
14	Admin - Grupos	SystemGroupList
\.


--
-- Name: system_program_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('system_program_id_seq', 14, true);


--
-- Data for Name: system_user; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY system_user (id, name, login, password, email, frontpage_id, filial_id) FROM stdin;
1	Admin Esales	admin	21232f297a57a5a743894a0e4a801fc3	admin@esales.com	\N	1
\.


--
-- Name: system_user_filial_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('system_user_filial_id_seq', 1, true);


--
-- Data for Name: system_user_group; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY system_user_group (id, system_user_id, system_group_id) FROM stdin;
1	1	1
\.


--
-- Name: system_user_group_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('system_user_group_id_seq', 1, false);


--
-- Name: system_user_group_system_group_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('system_user_group_system_group_id_seq', 1, false);


--
-- Name: system_user_group_system_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('system_user_group_system_user_id_seq', 1, false);


--
-- Name: system_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('system_user_id_seq', 1, false);


--
-- Data for Name: system_user_program; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY system_user_program (id, system_user_id, system_program_id, action) FROM stdin;
\.


--
-- Name: system_user_program_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('system_user_program_id_seq', 1, false);


--
-- Name: system_user_program_system_program_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('system_user_program_system_program_id_seq', 1, false);


--
-- Name: system_user_program_system_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('system_user_program_system_user_id_seq', 1, false);


--
-- Data for Name: venda; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY venda (id, vendedor_id, comprador_id, filial_id, data_venda) FROM stdin;
1	2	1	1	2015-12-13 17:19:28.90422
\.


--
-- Name: venda_comprador_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('venda_comprador_id_seq', 1, false);


--
-- Name: venda_filial_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('venda_filial_id_seq', 1, false);


--
-- Name: venda_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('venda_id_seq', 1, true);


--
-- Data for Name: venda_item; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY venda_item (id, produto_id, venda_id, valor_unitario, quantidade) FROM stdin;
1	1	1	\N	\N
\.


--
-- Name: venda_item_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('venda_item_id_seq', 1, true);


--
-- Name: venda_item_produto_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('venda_item_produto_id_seq', 1, false);


--
-- Name: venda_item_venda_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('venda_item_venda_id_seq', 1, false);


--
-- Name: venda_vendedor_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('venda_vendedor_id_seq', 1, false);


--
-- Name: caixa_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY caixa
    ADD CONSTRAINT caixa_pkey PRIMARY KEY (id);


--
-- Name: cidade_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cidade
    ADD CONSTRAINT cidade_pkey PRIMARY KEY (id);


--
-- Name: compra_item_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY compra_item
    ADD CONSTRAINT compra_item_pkey PRIMARY KEY (id);


--
-- Name: compra_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY compra
    ADD CONSTRAINT compra_pkey PRIMARY KEY (id);


--
-- Name: entrada_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY entrada
    ADD CONSTRAINT entrada_pkey PRIMARY KEY (id);


--
-- Name: filial_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY filial
    ADD CONSTRAINT filial_pkey PRIMARY KEY (id);


--
-- Name: log_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY log
    ADD CONSTRAINT log_pkey PRIMARY KEY (id);


--
-- Name: marca_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY marca
    ADD CONSTRAINT marca_pkey PRIMARY KEY (id);


--
-- Name: pessoa_fisica_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY pessoa_fisica
    ADD CONSTRAINT pessoa_fisica_pkey PRIMARY KEY (pessoa_id);


--
-- Name: pessoa_juridica_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY pessoa_juridica
    ADD CONSTRAINT pessoa_juridica_pkey PRIMARY KEY (pessoa_id);


--
-- Name: pessoa_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY pessoa
    ADD CONSTRAINT pessoa_pkey PRIMARY KEY (id);


--
-- Name: produto_filial_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY produto_filial
    ADD CONSTRAINT produto_filial_pkey PRIMARY KEY (id, produto_id, filial_id);


--
-- Name: produto_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY produto
    ADD CONSTRAINT produto_pkey PRIMARY KEY (id);


--
-- Name: saida_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY saida
    ADD CONSTRAINT saida_pkey PRIMARY KEY (id);


--
-- Name: system_group_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY system_group
    ADD CONSTRAINT system_group_pkey PRIMARY KEY (id);


--
-- Name: system_group_program_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY system_group_program
    ADD CONSTRAINT system_group_program_pkey PRIMARY KEY (id);


--
-- Name: system_program_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY system_program
    ADD CONSTRAINT system_program_pkey PRIMARY KEY (id);


--
-- Name: system_user_group_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY system_user_group
    ADD CONSTRAINT system_user_group_pkey PRIMARY KEY (id);


--
-- Name: system_user_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY system_user
    ADD CONSTRAINT system_user_pkey PRIMARY KEY (id);


--
-- Name: system_user_program_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY system_user_program
    ADD CONSTRAINT system_user_program_pkey PRIMARY KEY (id);


--
-- Name: venda_item_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY venda_item
    ADD CONSTRAINT venda_item_pkey PRIMARY KEY (id);


--
-- Name: venda_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY venda
    ADD CONSTRAINT venda_pkey PRIMARY KEY (id);


--
-- Name: fk_caixa_filial1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY caixa
    ADD CONSTRAINT fk_caixa_filial1 FOREIGN KEY (filial_id) REFERENCES filial(id);


--
-- Name: fk_caixa_pessoa1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY caixa
    ADD CONSTRAINT fk_caixa_pessoa1 FOREIGN KEY (pessoa_id) REFERENCES pessoa(id);


--
-- Name: fk_compra_filial1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY compra
    ADD CONSTRAINT fk_compra_filial1 FOREIGN KEY (filial_id) REFERENCES filial(id);


--
-- Name: fk_compra_item_compra1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY compra_item
    ADD CONSTRAINT fk_compra_item_compra1 FOREIGN KEY (compra_id) REFERENCES compra(id);


--
-- Name: fk_compra_item_produto1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY compra_item
    ADD CONSTRAINT fk_compra_item_produto1 FOREIGN KEY (produto_id) REFERENCES produto(id);


--
-- Name: fk_compra_pessoa1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY compra
    ADD CONSTRAINT fk_compra_pessoa1 FOREIGN KEY (vendedor_id) REFERENCES pessoa(id);


--
-- Name: fk_entrada_caixa1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entrada
    ADD CONSTRAINT fk_entrada_caixa1 FOREIGN KEY (caixa_id) REFERENCES caixa(id);


--
-- Name: fk_pessoa_cidade1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa
    ADD CONSTRAINT fk_pessoa_cidade1 FOREIGN KEY (cidade_id) REFERENCES cidade(id);


--
-- Name: fk_pessoa_fisica_pessoa; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa_fisica
    ADD CONSTRAINT fk_pessoa_fisica_pessoa FOREIGN KEY (pessoa_id) REFERENCES pessoa(id);


--
-- Name: fk_pessoa_juridica_pessoa1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pessoa_juridica
    ADD CONSTRAINT fk_pessoa_juridica_pessoa1 FOREIGN KEY (pessoa_id) REFERENCES pessoa(id);


--
-- Name: fk_produto_has_filial_filial1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY produto_filial
    ADD CONSTRAINT fk_produto_has_filial_filial1 FOREIGN KEY (filial_id) REFERENCES filial(id);


--
-- Name: fk_produto_has_filial_produto1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY produto_filial
    ADD CONSTRAINT fk_produto_has_filial_produto1 FOREIGN KEY (produto_id) REFERENCES produto(id);


--
-- Name: fk_produto_marca1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY produto
    ADD CONSTRAINT fk_produto_marca1 FOREIGN KEY (marca_id) REFERENCES marca(id);


--
-- Name: fk_saida_caixa1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY saida
    ADD CONSTRAINT fk_saida_caixa1 FOREIGN KEY (caixa_id) REFERENCES caixa(id);


--
-- Name: fk_system_group_program_system_group1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_group_program
    ADD CONSTRAINT fk_system_group_program_system_group1 FOREIGN KEY (system_group_id) REFERENCES system_group(id);


--
-- Name: fk_system_group_program_system_program1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_group_program
    ADD CONSTRAINT fk_system_group_program_system_program1 FOREIGN KEY (system_program_id) REFERENCES system_program(id);


--
-- Name: fk_system_user_filial1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_user
    ADD CONSTRAINT fk_system_user_filial1 FOREIGN KEY (filial_id) REFERENCES filial(id);


--
-- Name: fk_system_user_group_system_group1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_user_group
    ADD CONSTRAINT fk_system_user_group_system_group1 FOREIGN KEY (system_group_id) REFERENCES system_group(id);


--
-- Name: fk_system_user_group_system_user1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_user_group
    ADD CONSTRAINT fk_system_user_group_system_user1 FOREIGN KEY (system_user_id) REFERENCES system_user(id);


--
-- Name: fk_system_user_program_system_program1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_user_program
    ADD CONSTRAINT fk_system_user_program_system_program1 FOREIGN KEY (system_program_id) REFERENCES system_program(id);


--
-- Name: fk_system_user_program_system_user1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY system_user_program
    ADD CONSTRAINT fk_system_user_program_system_user1 FOREIGN KEY (system_user_id) REFERENCES system_user(id);


--
-- Name: fk_venda_filial1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY venda
    ADD CONSTRAINT fk_venda_filial1 FOREIGN KEY (filial_id) REFERENCES filial(id);


--
-- Name: fk_venda_item_produto1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY venda_item
    ADD CONSTRAINT fk_venda_item_produto1 FOREIGN KEY (produto_id) REFERENCES produto(id);


--
-- Name: fk_venda_item_venda1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY venda_item
    ADD CONSTRAINT fk_venda_item_venda1 FOREIGN KEY (venda_id) REFERENCES venda(id);


--
-- Name: fk_venda_pessoa1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY venda
    ADD CONSTRAINT fk_venda_pessoa1 FOREIGN KEY (vendedor_id) REFERENCES pessoa(id);


--
-- Name: fk_venda_pessoa2; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY venda
    ADD CONSTRAINT fk_venda_pessoa2 FOREIGN KEY (comprador_id) REFERENCES pessoa(id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

