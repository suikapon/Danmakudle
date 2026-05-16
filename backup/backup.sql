--
-- PostgreSQL database dump
--

\restrict er5z9H9aJ5QBCcjcSjFK53O8jj0JAMEetY5ITVxJDHjlNWcYgQt60x4Mp2mPbGH

-- Dumped from database version 18.3 (Debian 18.3-1.pgdg12+1)
-- Dumped by pg_dump version 18.2

-- Started on 2026-05-16 18:40:52

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

--
-- TOC entry 5 (class 2615 OID 2200)
-- Name: public; Type: SCHEMA; Schema: -; Owner: danmakudle_5w7q_user
--

-- *not* creating schema, since initdb creates it


ALTER SCHEMA public OWNER TO danmakudle_5w7q_user;

--
-- TOC entry 856 (class 1247 OID 16418)
-- Name: tipo_reto_enum; Type: TYPE; Schema: public; Owner: danmakudle_5w7q_user
--

CREATE TYPE public.tipo_reto_enum AS ENUM (
    'personaje',
    'videojuego'
);


ALTER TYPE public.tipo_reto_enum OWNER TO danmakudle_5w7q_user;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 223 (class 1259 OID 16565)
-- Name: juegos; Type: TABLE; Schema: public; Owner: danmakudle_5w7q_user
--

CREATE TABLE public.juegos (
    id numeric NOT NULL,
    nombre character varying(100) NOT NULL,
    "año" integer NOT NULL,
    tipo character varying(50) NOT NULL,
    plataforma character varying(50) NOT NULL,
    imagen character varying(100) NOT NULL
);


ALTER TABLE public.juegos OWNER TO danmakudle_5w7q_user;

--
-- TOC entry 222 (class 1259 OID 16527)
-- Name: personajes; Type: TABLE; Schema: public; Owner: danmakudle_5w7q_user
--

CREATE TABLE public.personajes (
    id_personaje integer NOT NULL,
    nombre character varying(100) NOT NULL,
    imagen character varying(100),
    debut numeric(4,1),
    stage character varying(20),
    ubicacion character varying(200),
    especie character varying(100),
    jugable boolean,
    especie_normalizada text,
    audio character varying(100)
);


ALTER TABLE public.personajes OWNER TO danmakudle_5w7q_user;

--
-- TOC entry 221 (class 1259 OID 16526)
-- Name: personajes_id_personaje_seq; Type: SEQUENCE; Schema: public; Owner: danmakudle_5w7q_user
--

CREATE SEQUENCE public.personajes_id_personaje_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.personajes_id_personaje_seq OWNER TO danmakudle_5w7q_user;

--
-- TOC entry 3411 (class 0 OID 0)
-- Dependencies: 221
-- Name: personajes_id_personaje_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: danmakudle_5w7q_user
--

ALTER SEQUENCE public.personajes_id_personaje_seq OWNED BY public.personajes.id_personaje;


--
-- TOC entry 220 (class 1259 OID 16445)
-- Name: retos_diarios; Type: TABLE; Schema: public; Owner: danmakudle_5w7q_user
--

CREATE TABLE public.retos_diarios (
    id_reto integer NOT NULL,
    fecha date,
    id_personaje integer,
    tipo_reto public.tipo_reto_enum
);


ALTER TABLE public.retos_diarios OWNER TO danmakudle_5w7q_user;

--
-- TOC entry 219 (class 1259 OID 16429)
-- Name: usuarios; Type: TABLE; Schema: public; Owner: danmakudle_5w7q_user
--

CREATE TABLE public.usuarios (
    username character varying(100) NOT NULL,
    email character varying(255),
    contrasena character varying(255),
    puntuacion integer,
    racha_actual integer,
    racha_max integer
);


ALTER TABLE public.usuarios OWNER TO danmakudle_5w7q_user;

--
-- TOC entry 3244 (class 2604 OID 16530)
-- Name: personajes id_personaje; Type: DEFAULT; Schema: public; Owner: danmakudle_5w7q_user
--

ALTER TABLE ONLY public.personajes ALTER COLUMN id_personaje SET DEFAULT nextval('public.personajes_id_personaje_seq'::regclass);


--
-- TOC entry 3405 (class 0 OID 16565)
-- Dependencies: 223
-- Data for Name: juegos; Type: TABLE DATA; Schema: public; Owner: danmakudle_5w7q_user
--

COPY public.juegos (id, nombre, "año", tipo, plataforma, imagen) FROM stdin;
6	The Embodiment of Scarlet Devil	2002	Shooter	Windows	th06.png
7	Perfect Cherry Blossom	2003	Shooter	Windows	th07.png
7.5	Immaterial and Missing Power	2004	Fighting	Windows	th075.png
8	Imperishable Night	2004	Shooter	Windows	th08.png
9	Phantasmagoria of Flower View	2005	Shooter	Windows	th09.png
9.5	Shoot the Bullet	2005	Fotografía	Windows	th095.png
10	Mountain of Faith	2007	Shooter	Windows	th10.png
10.5	Scarlet Weather Rhapsody	2008	Fighting	Windows	th105.png
11	Subterranean Animism	2008	Shooter	Windows	th11.png
12	Undefined Fantastic Object	2009	Shooter	Windows	th12.png
12.3	Touhou Hisoutensoku	2009	Fighting	Windows	th123.png
12.5	Double Spoiler	2010	Fotografía	Windows	th125.png
12.8	Great Fairy Wars	2010	Shooter	Windows	th128.png
13	Ten Desires	2011	Shooter	Windows	th13.png
13.5	Hopeless Masquerade	2013	Fighting	Windows	th135.png
14	Double Dealing Character	2013	Shooter	Windows	th14.png
14.3	Impossible Spell Card	2014	Esquiva	Windows	th143.png
14.5	Urban Legend in Limbo	2015	Fighting	Windows	th145.png
15	Legacy of Lunatic Kingdom	2015	Shooter	Windows	th15.png
15.5	Antinomy of Common Flowers	2017	Fighting	Windows	th155.png
16	Hidden Star in Four Seasons	2017	Shooter	Windows	th16.png
16.5	Violet Detector	2018	Fotografía	Windows	th165.png
17	Wily Beast and Weakest Creature	2019	Shooter	Windows	th17.png
17.5	Touhou Gouyoku Ibun	2021	Fighting	Windows	th175.png
18	Unconnected Marketeers	2021	Shooter	Windows	th18.png
18.5	100th Black Market	2022	Shooter	Windows	th185.png
19	Unfinished Dream of All Living Ghost	2023	Shooter	Windows	th19.png
20	Fossilized Wonders	2025	Shooter	Windows	th20.png
\.


--
-- TOC entry 3404 (class 0 OID 16527)
-- Dependencies: 222
-- Data for Name: personajes; Type: TABLE DATA; Schema: public; Owner: danmakudle_5w7q_user
--

COPY public.personajes (id_personaje, nombre, imagen, debut, stage, ubicacion, especie, jugable, especie_normalizada, audio) FROM stdin;
4	Daiyousei	daiyousei.png	6.0	2	Lago Niebla	Hada	f	Hada	\N
6	Hong Meiling	meiling.png	6.0	3	Mansión del Demonio Escarlata	Youkai	t	Youkai	\N
7	Koakuma	koakuma.png	6.0	4	Mansión del Demonio Escarlata	Demonio	f	Oni	\N
12	Letty Whiterock	letty.png	7.0	1	Desconocida	Youkai de Invierno	f	Youkai	\N
13	Chen	chen.png	7.0	2	Bosque de la Magia	Bakeneko Shikigami	f	Youkai	\N
14	Alice Margatroid	alice.png	7.0	3	Bosque de la Magia	Maga Youkai	t	Maga, Youkai	\N
15	Lily White	lily_white.png	7.0	4	Desconocida	Hada de Primavera	f	Hada	\N
19	Youmu Konpaku	youmu.png	7.0	5	Hakugyokurou	Mitad Humana, Mitad Fantasma	t	Espíritu, Humana	\N
21	Ran Yakumo	ran.png	7.0	Extra	Mayohiga	Kitsune Shikigami	f	Youkai	\N
23	Wriggle Nightbug	wriggle.png	8.0	1	Desconocida	Youkai Insecto	f	Youkai	\N
24	Mystia Lorelei	mystia.png	8.0	2	Camino de la Montaña Youkai	Youkai Gorrión Nocturno	f	Youkai	\N
25	Keine Kamishirasawa	keine.png	8.0	3	Aldea Humana	Were-hakutaku	f	Were-hakutaku	\N
26	Tewi Inaba	tewi.png	8.0	5	Eientei	Youkai Conejo	f	Conejo, Youkai	\N
30	Fujiwara no Mokou	mokou.png	8.0	Extra	Bosque de Bambú	Humana Inmortal	t	Humana	\N
31	Shizuha Aki	shizuha.png	9.0	1	Montaña Youkai	Diosa	t	Divinidad	\N
33	Hina Kagiyama	hina.png	9.0	2	Montaña Youkai	Diosa de la Maldición	t	Divinidad	\N
35	Momiji Inubashiri	momiji.png	9.0	4	Montaña Youkai	Tengu Lobo Blanco	f	Tengu	\N
37	Sanae Kochiya	sanae.png	9.0	5	Santuario Moriya	Humana/Descendiente de Diosa	t	Divinidad, Humana	\N
38	Kanako Yasaka	kanako.png	9.0	6	Santuario Moriya	Diosa	f	Divinidad	\N
39	Suwako Moriya	suwako.png	9.0	Extra	Santuario Moriya	Diosa	t	Divinidad	\N
40	Sunny Milk	sunny.png	9.5	0	Desconocida	Hada	f	Hada	\N
41	Luna Child	luna.png	9.5	0	Desconocida	Hada	f	Hada	\N
42	Star Sapphire	star.png	9.5	0	Desconocida	Hada	f	Hada	\N
45	Tenshi Hinanawi	tenshi.png	10.5	0	Cielo	Celestial	t	Celestial	\N
46	Kisume	kisume.png	11.0	1	Antigua Capital Subterránea	Tsurube-otoshi	f	Tsurube-otoshi	\N
48	Parsee Mizuhashi	parsee.png	11.0	2	Puente del Abismo sin Retorno	Hashihime	f	Hashihime	\N
51	Rin Kaenbyou (Orin)	orin.png	11.0	5	Palacio de los Espíritus Terrestres	Kasha	f	Kasha	\N
56	Ichirin Kumoi	ichirin.png	12.0	3	Templo Myouren	Nyuudou Tsukumogami	t	Tsukumogami	\N
57	Unzan	unzan.png	12.0	3	Templo Myouren	Nyuudou	f	Nyuudou	\N
59	Shou Toramaru	shou.png	12.0	5	Templo Myouren	Tigre Youkai	t	Youkai	\N
61	Nue Houjuu	nue.png	12.0	Extra	Templo Myouren	Nue	t	Nue	\N
62	Hatate Himekaidou	hatate.png	12.5	0	Montaña Youkai	Tengu Cuervo	f	Tengu	\N
63	Kyouko Kasodani	kyouko.png	13.0	2	Templo Myouren	Yamabiko	f	Yamabiko	\N
66	Tojiko	tojiko.png	13.0	5	Mausoleo del Deseo de los Divinos Espíritus	Fantasma Vengativo	f	Espíritu	\N
68	Miko	miko.png	13.0	6	Mausoleo del Deseo de los Divinos Espíritus	Shikaisen	t	Ermitaña	\N
69	Mamizou Futatsuiwa	mamizou.png	13.0	Extra	Bosque de Bambú	Tanuki Bake-danuki	t	Youkai	\N
70	Kokoro	kokoro.png	13.5	0	Templo Myouren	Menreiki Tsukumogami	t	Tsukumogami	\N
71	Wakasagihime	wakasagihime.png	14.0	1	Lago Niebla	Sirena	f	Sirena	\N
72	Sekibanki	sekibanki.png	14.0	2	Aldea Humana	Rokurokubi/Nukekubi	t	Rokurokubi/Nukekubi	\N
73	Kagerou Imaizumi	kagerou.png	14.0	3	Bosque de Bambú	Hombre Lobo	f	Hombre Lobo	\N
74	Benben Tsukumo	benben.png	14.0	4	Desconocida	Biwa Tsukumogami	f	Tsukumogami	\N
16	Lunasa Prismriver	lunasa.png	7.0	4	Mansión Prismriver	Poltergeist	f	Espíritu	prismriver.opus
36	Aya Shameimaru	aya.png	9.0	4	Montaña Youkai	Tengu Cuervo	t	Tengu	aya.opus
11	Flandre Scarlet	flandre.png	6.0	Extra	Mansión del Demonio Escarlata	Vampiro	f	Vampiro	flandre.opus
44	Iku Nagae	iku.png	10.5	0	Cielo	Oarfish Youkai	t	Youkai	iku.opus
17	Merlin Prismriver	merlin.png	7.0	4	Mansión Prismriver	Poltergeist	f	Espíritu	prismriver.opus
18	Lyrica Prismriver	lyrica.png	7.0	4	Mansión Prismriver	Poltergeist	f	Espíritu	prismriver.opus
75	Yatsuhashi Tsukumo	yatsuhashi.png	14.0	4	Desconocida	Koto Tsukumogami	f	Tsukumogami	\N
76	Seija Kijin	seija.png	14.0	5	Desconocida	Amanojaku	t	Amanojaku	\N
78	Raiko Horikawa	raiko.png	14.0	Extra	Desconocida	Taiko no Bakemono Tsukumogami	t	Tsukumogami	\N
79	Kasen Ibaraki	kasen.png	14.3	0	Montaña Youkai	Oni Ermitaña	f	Ermitaña, Oni	\N
80	Sumireko Usami	sumireko.png	14.5	0	Mundo Exterior	Humana	t	Humana	\N
81	Seiran	seiran.png	15.0	1	Luna	Conejo Lunar	f	Conejo, Lunar	\N
82	Ringo	ringo.png	15.0	2	Luna	Conejo Lunar	f	Conejo, Lunar	\N
83	Doremy Sweet	doremy.png	15.0	3	Mundo de los Sueños	Baku	f	Baku	\N
84	Sagume Kishin	sagume.png	15.0	4	Luna	Diosa Lunarian	f	Divinidad, Lunar	\N
87	Hecatia Lapislazuli	hecatia.png	15.0	Extra	Infierno	Diosa	f	Divinidad	\N
88	Eternity Larva	eternity.png	16.0	1	Desconocida	Hada Mariposa	f	Hada	\N
89	Nemuno Sakata	nemuno.png	16.0	2	Montaña Youkai	Yamauba	f	Yamauba	\N
91	Narumi Yatadera	narumi.png	16.0	4	Desconocida	Jizou Haniwa	f	Haniwa	\N
92	Mai Teireida	mai.png	16.0	5	Tierra de las Puertas Traseras	Diosa	f	Divinidad	\N
93	Satono Nishida	satono.png	16.0	5	Tierra de las Puertas Traseras	Diosa	f	Divinidad	\N
94	Okina Matara	okina.png	16.0	6	Tierra de las Puertas Traseras	Diosa Secreta	t	Divinidad	\N
95	Eika Ebisu	eika.png	17.0	1	Infierno Bestial	Alto Espíritu Jizou Haniwa	f	Espíritu, Haniwa	\N
96	Urumi Ushizaki	urumi.png	17.0	2	Infierno Bestial	Ushi-oni	f	Oni	\N
97	Kutaka Niwatari	kutaka.png	17.0	3	Infierno Bestial	Fantasma de Pollo	f	Espíritu	\N
98	Yachie Kicchou	yachie.png	17.0	4	Infierno Bestial	Jidiao	f	Jidiao	\N
99	Mayumi Joutouguu	mayumi.png	17.0	5	Infierno Bestial	Haniwa	f	Haniwa	\N
100	Keiki Haniyasushin	keiki.png	17.0	6	Jardín de los Cielos Prístinos	Diosa	f	Divinidad	\N
101	Saki Kurokoma	saki.png	17.0	Extra	Infierno Bestial	Keukegen	f	Keukegen	\N
102	Mike Goutokuji	mike.png	18.0	1	Bosque Arcoíris del Dragón	Bakeneko	f	Youkai	\N
103	Takane Yamashiro	takane.png	18.0	2	Bosque Arcoíris del Dragón	Yamawaro	f	Yamawaro	\N
104	Sannyo Komakusa	sannyo.png	18.0	3	Bosque Arcoíris del Dragón	Zashiki-warashi	f	Zashiki-warashi	\N
105	Misumaru Tamatsukuri	misumaru.png	18.0	4	Desconocida	Diosa	f	Divinidad	\N
106	Tsukasa Kudamaki	tsukasa.png	18.0	5	Bosque Arcoíris del Dragón	Kuda-gitsune	f	Kuda-gitsune	\N
107	Megumu Iizunamaru	megumu.png	18.0	6	Montaña Youkai	Daitengu	f	Tengu	\N
108	Chimata Tenkyuu	chimata.png	18.0	Extra	Desconocida	Diosa del Mercado Libre	f	Divinidad	\N
109	Momoyo Himemushi	momoyo.png	18.5	0	Infierno Bestial	Oumukade	f	Oumukade	\N
110	Chiyari Tenkajin	chiyari.png	19.0	1	Mundo de los Sueños	Zashiki-warashi Otter	f	Zashiki-warashi Otter	\N
111	Hisami Yomotsu	hisami.png	19.0	2	Infierno	Kishin	f	Kishin	\N
112	Zanmu Nippaku	zanmu.png	19.0	3	Infierno	Jikininki	f	Jikininki	\N
113	Enoko Mitsugashira	enoko.png	19.0	4	Infierno	Yamabiko Konoha-tengu	f	Tengu	\N
114	Biten Shirayuki	son.png	19.0	5	Infierno	Youkai Arroyo	f	Youkai	\N
116	Yuuma Toutetsu	yuuma.png	19.0	Extra	Infierno Bestial	Taotie	f	Taotie	\N
117	Medicine Melancholy	medicine.png	9.0	0	Jardín del Sol	Muñeca Tsukumogami Venenosa	t	Tsukumogami	\N
118	Yuuka Kazami	yuuka.png	9.0	0	Jardín del Sol	Youkai	t	Youkai	\N
119	Watari Nina	nina.png	20.0	Extra	Mundo Exterior	Kalavinka	f	Kalavinka	\N
90	Aunn Komano	aunn.png	16.0	3	Templo Hakurei	Komainu	t	Komainu	aunn.opus
60	Byakuren Hijiri	byakuren.png	12.0	6	Templo Myouren	Maga	t	Maga	byakuren.opus
5	Cirno	cirno.png	6.0	2	Lago Niebla	Hada de Hielo	t	Hada	cirno.opus
85	Clownpiece	clownpiece.png	15.0	5	Infierno	Hada del Infierno	t	Hada	clownpiece.opus
28	Eirin Yagokoro	eirin.png	8.0	6	Eientei	Lunarian	f	Lunar	eirin.opus
86	Junko	junko.png	15.0	6	Luna	Espíritu Divino	f	Divinidad, Espíritu	Junko.opus
29	Kaguya Houraisan	kaguya.png	8.0	6	Eientei	Lunarian	f	Lunar	kaguya.opus
55	Kogasa Tatara	kogasa.png	12.0	2	Desconocida	Karakasa	t	Karakasa	kogasa.opus
53	Koishi Komeiji	koishi.png	11.0	Extra	Palacio de los Espíritus Terrestres	Satori	t	Satori	koishi.opus
2	Marisa Kirisame	marisa.png	6.0	0	Bosque de la Magia	Humana	t	Humana	marisa.opus
32	Minoriko Aki	minoriko.png	9.0	1	Montaña Youkai	Diosa	t	Divinidad	minoriko.opus
67	Futo Mononobe	futo.png	13.0	5	Mausoleo del Deseo de los Divinos Espíritus	Shikaisen	t	Ermitaña	mononobe.opus
58	Minamitsu Murasa	murasa.png	12.0	4	Templo Myouren	Fantasma	f	Espíritu	murasa.opus
54	Nazrin	nazrin.png	12.0	1	Templo Myouren	Ratón Youkai	f	Youkai	nazrin.opus
34	Nitori Kawashiro	nitori.png	9.0	3	Río Genbu	Kappa	t	Kappa	nitori.opus
8	Patchouli Knowledge	patchouli.png	6.0	4	Mansión del Demonio Escarlata	Maga Youkai	t	Maga, Youkai	patchouli.opus
1	Reimu Hakurei	reimu.png	6.0	0	Templo Hakurei	Humana	t	Humana	reimu.opus
27	Reisen Udongein Inaba	reisen.png	8.0	5	Eientei	Conejo Lunar	t	Conejo, Lunar	reisen.opus
10	Remilia Scarlet	remilia.png	6.0	6	Mansión del Demonio Escarlata	Vampiro	t	Vampiro	remilia.opus
3	Rumia	rumia.png	6.0	1	Desconocida	Youkai	f	Youkai	rumia.opus
9	Sakuya Izayoi	sakuya.png	6.0	5	Mansión del Demonio Escarlata	Humana	t	Humana	sakuya.opus
50	Satori Komeiji	satori.png	11.0	4	Palacio de los Espíritus Terrestres	Satori	t	Satori	satori.opus
65	Seiga Kaku	seiga.png	13.0	4	Mausoleo del Deseo de los Divinos Espíritus	Ermitaña Malvada	f	Ermitaña	seiga.opus
43	Eiki Shiki Yamaxanadu	eiki.png	9.0	Extra	Higan	Yama	t	Yama	shikieiki.opus
77	Shinmyoumaru Sukuna	shinmyoumaru.png	14.0	6	Mausoleo del Deseo de los Divinos Espíritus	Inchling	t	Inchling	shinmyoumaru.opus
115	Suika Ibuki	suika.png	19.0	6	Hakurei Shrine	Oni	t	Oni	suika.opus
52	Utsuho Reiuji (Okuu)	okuu.png	11.0	6	Palacio de los Espíritus Terrestres	Cuervo del Infierno	t	Cuervo del Infierno	utsuho.opus
47	Yamame Kurodani	yamame.png	11.0	1	Antigua Capital Subterránea	Tsuchigumo	f	Tsuchigumo	yamame.opus
64	Yoshika Miyako	yoshika.png	13.0	3	Mausoleo del Deseo de los Divinos Espíritus	Jiang Shi	f	Jiang Shi	yoshika.opus
22	Yukari Yakumo	yukari.png	7.0	Phantasm	Mayohiga	Youkai	t	Youkai	yukari.opus
49	Yuugi Hoshiguma	yuugi.png	11.0	3	Antigua Capital Subterránea	Oni	t	Oni	yuugi.opus
20	Yuyuko Saigyouji	yuyuko.png	7.0	6	Hakugyokurou	Fantasma	t	Espíritu	yuyuko.opus
\.


--
-- TOC entry 3402 (class 0 OID 16445)
-- Dependencies: 220
-- Data for Name: retos_diarios; Type: TABLE DATA; Schema: public; Owner: danmakudle_5w7q_user
--

COPY public.retos_diarios (id_reto, fecha, id_personaje, tipo_reto) FROM stdin;
\.


--
-- TOC entry 3401 (class 0 OID 16429)
-- Dependencies: 219
-- Data for Name: usuarios; Type: TABLE DATA; Schema: public; Owner: danmakudle_5w7q_user
--

COPY public.usuarios (username, email, contrasena, puntuacion, racha_actual, racha_max) FROM stdin;
\.


--
-- TOC entry 3412 (class 0 OID 0)
-- Dependencies: 221
-- Name: personajes_id_personaje_seq; Type: SEQUENCE SET; Schema: public; Owner: danmakudle_5w7q_user
--

SELECT pg_catalog.setval('public.personajes_id_personaje_seq', 119, true);


--
-- TOC entry 3252 (class 2606 OID 16577)
-- Name: juegos juegos_pkey; Type: CONSTRAINT; Schema: public; Owner: danmakudle_5w7q_user
--

ALTER TABLE ONLY public.juegos
    ADD CONSTRAINT juegos_pkey PRIMARY KEY (id);


--
-- TOC entry 3250 (class 2606 OID 16536)
-- Name: personajes personajes_pkey; Type: CONSTRAINT; Schema: public; Owner: danmakudle_5w7q_user
--

ALTER TABLE ONLY public.personajes
    ADD CONSTRAINT personajes_pkey PRIMARY KEY (id_personaje);


--
-- TOC entry 3248 (class 2606 OID 16450)
-- Name: retos_diarios retos_diarios_pkey; Type: CONSTRAINT; Schema: public; Owner: danmakudle_5w7q_user
--

ALTER TABLE ONLY public.retos_diarios
    ADD CONSTRAINT retos_diarios_pkey PRIMARY KEY (id_reto);


--
-- TOC entry 3246 (class 2606 OID 16436)
-- Name: usuarios usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: danmakudle_5w7q_user
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (username);


--
-- TOC entry 3253 (class 2606 OID 16583)
-- Name: personajes fk_debut; Type: FK CONSTRAINT; Schema: public; Owner: danmakudle_5w7q_user
--

ALTER TABLE ONLY public.personajes
    ADD CONSTRAINT fk_debut FOREIGN KEY (debut) REFERENCES public.juegos(id);


--
-- TOC entry 2068 (class 826 OID 16391)
-- Name: DEFAULT PRIVILEGES FOR SEQUENCES; Type: DEFAULT ACL; Schema: -; Owner: postgres
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres GRANT ALL ON SEQUENCES TO danmakudle_5w7q_user;


--
-- TOC entry 2070 (class 826 OID 16393)
-- Name: DEFAULT PRIVILEGES FOR TYPES; Type: DEFAULT ACL; Schema: -; Owner: postgres
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres GRANT ALL ON TYPES TO danmakudle_5w7q_user;


--
-- TOC entry 2069 (class 826 OID 16392)
-- Name: DEFAULT PRIVILEGES FOR FUNCTIONS; Type: DEFAULT ACL; Schema: -; Owner: postgres
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres GRANT ALL ON FUNCTIONS TO danmakudle_5w7q_user;


--
-- TOC entry 2067 (class 826 OID 16390)
-- Name: DEFAULT PRIVILEGES FOR TABLES; Type: DEFAULT ACL; Schema: -; Owner: postgres
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres GRANT ALL ON TABLES TO danmakudle_5w7q_user;


-- Completed on 2026-05-16 18:40:57

--
-- PostgreSQL database dump complete
--

\unrestrict er5z9H9aJ5QBCcjcSjFK53O8jj0JAMEetY5ITVxJDHjlNWcYgQt60x4Mp2mPbGH

