--
-- PostgreSQL database dump
--

\restrict cdV4vSYF2wMtNAFffygE6X7Muo24XD39AmXKk1nbksIGNwdwpBnJI5x2aJmjdIp

-- Dumped from database version 18.4
-- Dumped by pg_dump version 18.4

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: atributos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.atributos (
    idatributos integer NOT NULL,
    forca integer,
    intelecto integer,
    agilidade integer,
    carisma integer,
    vida integer,
    afinidademagica integer,
    defesa integer,
    defesamagica integer,
    bloqueio integer
);


ALTER TABLE public.atributos OWNER TO postgres;

--
-- Name: atributos_idatributos_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.atributos_idatributos_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.atributos_idatributos_seq OWNER TO postgres;

--
-- Name: atributos_idatributos_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.atributos_idatributos_seq OWNED BY public.atributos.idatributos;


--
-- Name: fichas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.fichas (
    idfichas integer NOT NULL,
    informacoesbase_id integer,
    atributos_id integer,
    habilidades_id integer,
    criadaem date,
    usuario_id integer
);


ALTER TABLE public.fichas OWNER TO postgres;

--
-- Name: fichas_idfichas_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.fichas_idfichas_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.fichas_idfichas_seq OWNER TO postgres;

--
-- Name: fichas_idfichas_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.fichas_idfichas_seq OWNED BY public.fichas.idfichas;


--
-- Name: habilidades; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.habilidades (
    idhabilidades integer NOT NULL,
    crime integer,
    furtividade integer,
    iniciativa integer,
    tiroaoalvo integer,
    luta integer,
    atletismo integer,
    intuicao integer,
    investigacao integer,
    medicina integer,
    sobrevivencia integer,
    tatica integer,
    labia integer,
    orientacaogeografica integer,
    percepcao integer,
    adestramento integer,
    alquimia integer,
    navegacao integer
);


ALTER TABLE public.habilidades OWNER TO postgres;

--
-- Name: habilidades_idhabilidades_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.habilidades_idhabilidades_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.habilidades_idhabilidades_seq OWNER TO postgres;

--
-- Name: habilidades_idhabilidades_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.habilidades_idhabilidades_seq OWNED BY public.habilidades.idhabilidades;


--
-- Name: informacoesbase; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.informacoesbase (
    idinformacoesbase integer NOT NULL,
    nomepersonagem text,
    idade integer,
    especie text,
    aparencia text,
    conexaomagica integer,
    hobbies text,
    inventario text,
    observacoes text,
    magiasconhecidas text
);


ALTER TABLE public.informacoesbase OWNER TO postgres;

--
-- Name: informacoesbase_idinformacoesbase_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.informacoesbase_idinformacoesbase_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.informacoesbase_idinformacoesbase_seq OWNER TO postgres;

--
-- Name: informacoesbase_idinformacoesbase_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.informacoesbase_idinformacoesbase_seq OWNED BY public.informacoesbase.idinformacoesbase;


--
-- Name: usuarios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.usuarios (
    idusuarios integer NOT NULL,
    nomeusuario text,
    senha text
);


ALTER TABLE public.usuarios OWNER TO postgres;

--
-- Name: usuarios_idusuarios_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.usuarios_idusuarios_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.usuarios_idusuarios_seq OWNER TO postgres;

--
-- Name: usuarios_idusuarios_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.usuarios_idusuarios_seq OWNED BY public.usuarios.idusuarios;


--
-- Name: atributos idatributos; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.atributos ALTER COLUMN idatributos SET DEFAULT nextval('public.atributos_idatributos_seq'::regclass);


--
-- Name: fichas idfichas; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.fichas ALTER COLUMN idfichas SET DEFAULT nextval('public.fichas_idfichas_seq'::regclass);


--
-- Name: habilidades idhabilidades; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.habilidades ALTER COLUMN idhabilidades SET DEFAULT nextval('public.habilidades_idhabilidades_seq'::regclass);


--
-- Name: informacoesbase idinformacoesbase; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.informacoesbase ALTER COLUMN idinformacoesbase SET DEFAULT nextval('public.informacoesbase_idinformacoesbase_seq'::regclass);


--
-- Name: usuarios idusuarios; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios ALTER COLUMN idusuarios SET DEFAULT nextval('public.usuarios_idusuarios_seq'::regclass);


--
-- Data for Name: atributos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.atributos (idatributos, forca, intelecto, agilidade, carisma, vida, afinidademagica, defesa, defesamagica, bloqueio) FROM stdin;
\.


--
-- Data for Name: fichas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.fichas (idfichas, informacoesbase_id, atributos_id, habilidades_id, criadaem, usuario_id) FROM stdin;
\.


--
-- Data for Name: habilidades; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.habilidades (idhabilidades, crime, furtividade, iniciativa, tiroaoalvo, luta, atletismo, intuicao, investigacao, medicina, sobrevivencia, tatica, labia, orientacaogeografica, percepcao, adestramento, alquimia, navegacao) FROM stdin;
\.


--
-- Data for Name: informacoesbase; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.informacoesbase (idinformacoesbase, nomepersonagem, idade, especie, aparencia, conexaomagica, hobbies, inventario, observacoes, magiasconhecidas) FROM stdin;
\.


--
-- Data for Name: usuarios; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.usuarios (idusuarios, nomeusuario, senha) FROM stdin;
\.


--
-- Name: atributos_idatributos_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.atributos_idatributos_seq', 1, true);


--
-- Name: fichas_idfichas_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.fichas_idfichas_seq', 1, true);


--
-- Name: habilidades_idhabilidades_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.habilidades_idhabilidades_seq', 1, true);


--
-- Name: informacoesbase_idinformacoesbase_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.informacoesbase_idinformacoesbase_seq', 1, true);


--
-- Name: usuarios_idusuarios_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.usuarios_idusuarios_seq', 2, true);


--
-- Name: atributos atributos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.atributos
    ADD CONSTRAINT atributos_pkey PRIMARY KEY (idatributos);


--
-- Name: fichas fichas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.fichas
    ADD CONSTRAINT fichas_pkey PRIMARY KEY (idfichas);


--
-- Name: habilidades habilidades_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.habilidades
    ADD CONSTRAINT habilidades_pkey PRIMARY KEY (idhabilidades);


--
-- Name: informacoesbase informacoesbase_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.informacoesbase
    ADD CONSTRAINT informacoesbase_pkey PRIMARY KEY (idinformacoesbase);


--
-- Name: usuarios usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (idusuarios);


--
-- Name: fichas fk_atributos; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.fichas
    ADD CONSTRAINT fk_atributos FOREIGN KEY (atributos_id) REFERENCES public.atributos(idatributos);


--
-- Name: fichas fk_habilidades; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.fichas
    ADD CONSTRAINT fk_habilidades FOREIGN KEY (habilidades_id) REFERENCES public.habilidades(idhabilidades);


--
-- Name: fichas fk_informacoesbase; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.fichas
    ADD CONSTRAINT fk_informacoesbase FOREIGN KEY (informacoesbase_id) REFERENCES public.informacoesbase(idinformacoesbase);


--
-- Name: fichas fk_usuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.fichas
    ADD CONSTRAINT fk_usuario FOREIGN KEY (usuario_id) REFERENCES public.usuarios(idusuarios);


--
-- PostgreSQL database dump complete
--

\unrestrict cdV4vSYF2wMtNAFffygE6X7Muo24XD39AmXKk1nbksIGNwdwpBnJI5x2aJmjdIp

