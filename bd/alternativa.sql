DROP TABLE IF EXISTS `alternativa`;
CREATE TABLE IF NOT EXISTS `alternativa` (
  `id_alternativa` int(11) NOT NULL AUTO_INCREMENT,
  `alternativa` tinytext NOT NULL,
  PRIMARY KEY (`id_alternativa`),
  UNIQUE KEY `id_alternativa_UNIQUE` (`id_alternativa`)
) ENGINE=InnoDB AUTO_INCREMENT=244 DEFAULT CHARSET=latin1;

use scadae;

INSERT INTO `alternativa` (`id_alternativa`, `alternativa`) VALUES
(1, 'Antes de 1980'),
(2, 'Antes de 1990'),
(3, '1980'),
(4, '1981'),
(5, '1982'),
(6, '1983'),
(7, '1984'),
(8, '1985'),
(9, '1986'),
(10, '1987'),
(11, '1988'),
(12, '1989'),
(13, '1990'),
(14, '1991'),
(15, '1992'),
(16, '1993'),
(17, '1994'),
(18, '1995'),
(19, '1996'),
(20, '1997'),
(21, '1998'),
(22, '1999'),
(23, '2000'),
(24, '2001'),
(25, '2002'),
(26, '2003'),
(27, '2004'),
(28, '2005'),
(29, '2006'),
(30, '2007'),
(31, '2008'),
(32, '2009'),
(33, '2010'),
(34, '2011'),
(35, '2012'),
(36, '2013'),
(37, '2014'),
(38, '2015'),
(39, '2016'),
(40, '2017'),
(41, '2013'),
(42, '2014'),
(43, '2015'),
(44, '2016'),
(45, '2017'),
(46, '2018'),
(47, '2019'),
(48, '2020'),
(49, 'Solteiro'),
(50, 'União estável'),
(51, 'Casado'),
(52, 'Divorciado'),
(53, 'Viúvo'),
(54, 'Feminino'),
(55, 'Masculino'),
(56, 'Prefiro não informar'),
(57, 'Outro'),
(58, 'Brasil'),
(59, 'Canadá'),
(60, 'Espanha'),
(61, 'Estados Unidos'),
(62, 'França'),
(63,'Itália'),
(64,'Portugal'),
(65,'Acre'),
(66, 'Alagoas'),
(67, 'Amazonas'),
(68, 'Amapá'),
(69, 'Bahia'),
(70, 'Ceará'),
(71, 'Distrito Federal'),
(72, 'Espírito Santo'),
(73, 'Goiás'),
(74, 'Maranhão'),
(75, 'Mato Grosso'),
(76, 'Mato Grosso do Sul'),
(77, 'Minas Gerais'),
(78, 'Pará'),
(79, 'Paraná'),
(80, 'Paraiba'),
(81, 'Pernambuco'),
(82, 'Piauí'),
(83, 'Rio de Janeiro'),
(84, 'Rio Grande do Norte'),
(85, 'Rio Grande do Sul'),
(86, 'Rondonia'),
(87, 'Roraima'),
(88, 'Santa Catarina'),
(89, 'Sergipe'),
(90, 'São Paulo'),
(91, 'Tocantins'),
(92, 'Antes da graduação'),
(93, 'Durante a graduação'),
(94, 'Antes de iniciar o técnico'),
(95, 'Durante o técnico'),
(96, 'Até um ano depois da formatura'),
(97, '1 a 3 anos depois da formatura'),
(98, 'Mais de 3 anos depois da formatura'),
(99, 'Sim'),
(100, 'Não'),
(101, 'Indiferente'),
(102, 'Outra área, mas na área das exatas'),
(103, 'Outra área diferente'),
(104, 'Ajudou muito'),
(105, 'Ajudou pouco'),
(106, 'Não ajudou'),
(107, 'Outra graduação'),
(108, 'Pós-graduação'),
(109, 'Mestrado'),
(110, 'Doutorado'),
(111, 'Outra formação'),
(112, 'Outro técnico'),
(113, 'Graduação'),
(114, 'Não sei'),
(115, 'Básico'),
(116, 'Intermediário'),
(117, 'Fluente'),
(118, '0'),
(119, '1'),
(120, '2'),
(121, '3'),
(122, 'Mais de 3'),
(123, 'Abriu uma empresa'),
(124, 'Enviou currículo e foi selecionado'),
(125, 'Fez estágio e foi admitido ao término do mesmo.'),
(126, 'Indicação de um amigo'),
(127, 'Indicação de um professor'),
(128, '4'),
(129, '5'),
(130, 'Sim. Pibic'),
(131, 'Sim. Pibex'),
(132, 'Sim. Ambas'),
(133, 'Concomitante'),
(134, 'Subsequente'),
(135, 'Autônomo'),
(136, 'Empregado com carteira assinada'),
(137, 'Empregado sem carteira assinada'),
(138, 'Empregador'),
(139, 'Informática / Sistemas / Tecnologia'),
(140, 'Planejamento / Desenvolvimento / Análise'),
(141, 'Industrial / Produção / Manutenção'),
(142, 'Acadêmica'),
(143, 'Administração'),
(144, 'Comercial / Vendas'),
(145, 'Consultoria'),
(146, 'Jurídica / Legal'),
(147, 'Presidência / Diretoria / Gerência Geral'),
(148, 'Telecomunicações'),
(149, 'Acadêmico'),
(150, 'Administrador de banco de dados'),
(151, 'Administrador de redes'),
(152, 'Analista de sistemas'),
(153, 'Coordenador de projetos'),
(154, 'Diretor'),
(155, 'Gerente'),
(156, 'Programador'),
(157, 'Sócio/ Proprietário'),
(158, 'Suporte técnico'),
(159, 'Menos de 40'),
(160, 'Entre 40 e 44'),
(161, 'Entre 45 e 48'),
(162, 'Mais de 48'),
(163, 'Menos de R$1000,00'),
(164, 'Entre R$1000,00 e R$1999,00'),
(165, 'Entre R$2000,00 e R$3999,00'),
(166, 'Entre R$4000,00 e R$5999,00'),
(167, 'Mais de R$6000,00'),
(168, 'Menos de R$2000,00'),
(169, 'Entre R$2000,00 e R$4999,00'),
(170, 'Entre R$5000,00 e R$7999,00'),
(171, 'Entre R$8000,00 e R$11999,00'),
(172, 'Mais de R$12000,00'),
(173, 'Falta de perspectiva de carreira'),
(174, 'Melhor oportunidade em outra profissão'),
(175, 'Mercado de trabalho atual saturado'),
(176, 'Trabalho na área da minha segunda formação'),
(177, 'Falta de oportunidade'),
(178, 'Não preciso/Não quero'),
(179, 'Me dedico aos estudos'),
(180, 'Aeroespacial e defesa'),
(181, 'Automobilísticas'),
(182, 'Fundos de aposentadoria e de pensão'),
(183, 'Indústria farmacêutica'),
(184, 'Maquinas e equipamentos'),
(185, 'Organização sem fins lucrativos'),
(186, 'Serviços'),
(187, 'Setor público'),
(188, 'Tecnologia da informação'),
(189, 'Menos de 10'),
(190, 'Entre 10 e 20'),
(191, 'Entre 10 e 30'),
(192, 'Entre 21 e 35'),
(193, 'Entre 31 e 50'),
(194, 'Entre 36 e 50'),
(195, 'Entre 51 e 100'),
(196, 'Mais de 100'),
(197, 'Usa efetivamente'),
(198, 'Usa indiretamente e é indispensável'),
(199, 'Usa indiretamente e é dispensável'),
(200, 'Nunca usou'),
(201, 'Não fiz a disciplina'),
(202, 'Não se aplica'),
(203, 'Menos de 2 horas'),
(204, '2 a 4 horas'),
(205, '4 a 6 horas'),
(206, '5 a 7 horas'),
(207, '6 a 8 horas'),
(208, '8 a 10 horas'),
(209, 'Mais de 10 horas'),
(210, 'Péssima'),
(211, 'Ruim'),
(212, 'Regular'),
(213, 'Bom'),
(214, 'Ótimo'),
(215, 'Um pouco'),
(216, 'Muito'),
(217, 'Péssimo'),
(218, 'Muito importante'),
(219, 'Importante'),
(220, 'Pouco importante'),
(221, 'Sem importância'),
(222, 'Sim. Em uma revista'),
(223, 'Sim. Em um congresso'),
(224, 'Sim. Em uma revista e em um congresso'),
(225, 'Artigo'),
(226, 'Monografia'),
(227, 'Sim. Como diretor'),
(228, 'Sim. Como gerente'),
(229, 'Sim. Efetivo'),
(230, 'Sim. Em outro cargo'),
(231, 'Sim. Em mais de uma função'),
(232, 'Sim. Na comissão coordenadora do curso'),
(233, 'Sim. No DCE'),
(234, 'Sim. Em ambas as entidade'),
(235, 'Sim. Em outra entidade'),
(236, 'Sim. Com bolsa'),
(237, 'Sim. Sem bolsa'),
(238, 'Sim. De uma matéria específica do curso'),
(239, 'Sim. De uma matéria de outro curso'),
(240, 'Sim. De ambos'),
(241, 'Sim. Com e sem bolsa'),
(242, 'Sim. No Brasil'),
(243, 'Sim. No exterior');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
