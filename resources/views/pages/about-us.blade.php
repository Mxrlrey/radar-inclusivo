<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Radar Inclusivo — Sobre Nós</title>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Noto+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --color-primary: #4c5667;
            --color-primary-dark: #2a374a;
            --color-primary-light: #6c7a8e;
            --color-accent: #e63946;
            --color-blue: #7fb3ef;
            --bg-body: #f3f3f4;
            --bg-dark: #4a515b;
            --text-primary: #262626;
            --text-secondary: #555555;
            --text-light: #98a6ad;
            --border-color: #dddddd;
            --topbar-height: 42px;
            --navbar-height: 80px;
            --font-heading: 'Rajdhani', sans-serif;
            --font-body: 'Noto Sans', 'Roboto Condensed', sans-serif;
            --shadow-nav: 0px 5px 10px rgba(0,0,0,0.12);
            --transition: 0.3s all ease;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { font-family: var(--font-body); font-size: 14px; color: var(--text-secondary); background: var(--bg-body); overflow-x: hidden; }
        a { text-decoration: none; transition: var(--transition); }
        ul { list-style: none; }
        h1,h2,h3,h4,h5,h6 { margin: 0; }
        p { margin: 0; line-height: 1.7; }

        /* TOP BAR */
        .top-bar { width: 100%; background: #f8f9fa; border-bottom: 1px solid #e9ecef; height: var(--topbar-height); display: flex; align-items: center; }
        .tb-inner { width: 100%; max-width: 1200px; margin: 0 auto; padding: 0 20px; display: flex; align-items: center; justify-content: space-between; }
        .top-bar-left { display: flex; align-items: center; gap: 24px; }
        .top-bar-left a { color: var(--text-secondary); font-size: 13px; display: flex; align-items: center; gap: 6px; }
        .top-bar-left a i { color: var(--color-blue); font-size: 13px; }
        .top-bar-left a:hover { color: var(--color-primary); }
        .btn-access { background: var(--color-blue); color: #fff !important; font-size: 12px; font-weight: 600; padding: 6px 16px; letter-spacing: 0.5px; text-transform: uppercase; transition: var(--transition); display: flex; align-items: center; gap: 6px; }
        .btn-access:hover { background: var(--color-primary); }

        /* NAVBAR */
        .navbar { position: sticky; top: 0; z-index: 100; background: #fff; box-shadow: var(--shadow-nav); height: var(--navbar-height); display: flex; align-items: center; }
        .nb-inner { width: 100%; max-width: 1200px; margin: 0 auto; padding: 0 20px; display: flex; align-items: center; justify-content: space-between; }
        .navbar-brand { display: flex; align-items: center; gap: 10px; }
        .brand-icon { width: 42px; height: 42px; background: var(--color-blue); display: flex; align-items: center; justify-content: center; }
        .brand-icon i { color: #fff; font-size: 20px; }
        .brand-name { font-family: var(--font-heading); font-size: 1.6rem; font-weight: 700; color: var(--color-primary); letter-spacing: 1px; text-transform: uppercase; }
        .brand-name span { color: var(--color-blue); }
        .nav-links { display: flex; align-items: center; }
        .nav-links li a { display: block; color: var(--text-primary); font-size: 1em; font-weight: 400; letter-spacing: 1px; padding: 1.5em; position: relative; transition: var(--transition); }
        .nav-links li a::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 3px; background: var(--color-blue); transform: scaleX(0); transition: transform 0.3s ease; }
        .nav-links li a:hover::after, .nav-links li a.active::after { transform: scaleX(1); }
        .nav-links li a:hover, .nav-links li a.active { color: var(--color-primary); font-weight: 600; }

        /* HERO */
        .hero { background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary) 60%, var(--color-primary-light) 100%); min-height: 320px; display: flex; align-items: center; justify-content: center; text-align: center; position: relative; overflow: hidden; }
        .hero::before { content: ''; position: absolute; inset: 0; background-image: radial-gradient(circle at 20% 50%, rgba(127,179,239,0.15) 0%, transparent 60%), radial-gradient(circle at 80% 20%, rgba(127,179,239,0.1) 0%, transparent 50%); }
        .hero-dots { position: absolute; inset: 0; background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='2' cy='2' r='1' fill='rgba(255,255,255,0.05)'/%3E%3C/svg%3E"); }
        .hero-content { position: relative; z-index: 2; padding: 3rem 1rem; }
        .hero-content h1 { font-family: var(--font-heading); font-size: 3.5rem; font-weight: 700; color: #fff; text-transform: uppercase; letter-spacing: 3px; line-height: 1.1; }
        .hero-content h1 span { color: var(--color-blue); }
        .hero-content p { color: rgba(255,255,255,0.8); font-size: 1.1rem; margin-top: 1rem; letter-spacing: 0.5px; }
        .hero-divider { width: 60px; height: 4px; background: var(--color-blue); margin: 1.2rem auto 0; }

        /* COMMONS */
        section { padding: 5rem 0; }
        .container { width: 100%; max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .section-title { font-family: var(--font-heading); font-size: 2.5rem; font-weight: 700; text-align: center; text-transform: uppercase; color: var(--text-primary); letter-spacing: 2px; position: relative; padding-bottom: 0.4em; margin-bottom: 0.5rem; }
        .section-title::after { content: ''; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 70px; height: 4px; background: var(--color-blue); }
        .section-title.white { color: #fff; }
        .section-subtitle { text-align: center; color: var(--text-light); font-size: 1rem; margin-bottom: 3rem; margin-top: 0.8rem; }
        .section-subtitle.white { color: rgba(255,255,255,0.5); }

        /* SOBRE */
        .sobre { background: #fff; }
        .sobre-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; margin-top: 3rem; }
        .sobre-text h3 { font-family: var(--font-heading); font-size: 1.8rem; color: var(--color-primary); margin-bottom: 1rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
        .sobre-text p { color: var(--text-secondary); font-size: 1rem; margin-bottom: 1rem; line-height: 1.8; }
        .sobre-list { margin-top: 1.5rem; }
        .sobre-list li { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 12px; color: var(--text-secondary); font-size: 0.95rem; }
        .sobre-list li i { color: var(--color-blue); margin-top: 2px; flex-shrink: 0; }
        .sobre-visual { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .stat-card { background: var(--bg-body); padding: 2rem 1.5rem; text-align: center; border-left: 4px solid var(--color-blue); transition: var(--transition); }
        .stat-card:hover { background: var(--color-primary); border-left-color: var(--color-accent); }
        .stat-card:hover .stat-num, .stat-card:hover .stat-label, .stat-card:hover .stat-num span { color: #fff; }
        .stat-num { font-family: var(--font-heading); font-size: 2.4rem; font-weight: 700; color: var(--color-primary); line-height: 1; }
        .stat-num span { color: var(--color-blue); }
        .stat-label { font-size: 0.72rem; color: var(--text-light); text-transform: uppercase; letter-spacing: 1px; margin-top: 0.4rem; }

        /* FUNCIONALIDADES */
        .funcionalidades { background: var(--bg-dark); }
        .features-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-top: 1rem; }
        .feature-card { text-align: center; padding: 2rem 1rem; cursor: default; }
        .feature-icon-wrap { width: 70px; height: 70px; background: #fff; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; transition: transform 0.6s ease, background 0.3s ease; }
        .feature-icon-wrap i { font-size: 1.6rem; color: var(--color-accent); transition: color 0.3s ease; }
        .feature-card:hover .feature-icon-wrap { background: var(--color-accent); transform: rotateY(360deg); }
        .feature-card:hover .feature-icon-wrap i { color: #fff; }
        .feature-card h5 { font-family: var(--font-heading); font-size: 1rem; font-weight: 700; color: #fff; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.6rem; }
        .feature-card p { color: rgba(255,255,255,0.7); font-size: 0.88rem; line-height: 1.7; }

        /* CTA */
        .cta-banner { background: var(--color-blue); padding: 4rem 0; text-align: center; }
        .cta-banner h2 { font-family: var(--font-heading); font-size: 2.2rem; color: #fff; text-transform: uppercase; letter-spacing: 2px; font-weight: 700; margin-bottom: 0.5rem; }
        .cta-banner p { color: rgba(255,255,255,0.85); font-size: 1rem; margin-bottom: 2rem; }
        .btn-cta { display: inline-flex; align-items: center; gap: 8px; background: #fff; color: var(--color-primary); font-weight: 700; font-size: 0.95rem; padding: 0.9em 2.5em; text-transform: uppercase; letter-spacing: 1px; transition: var(--transition); border: 2px solid transparent; font-family: var(--font-heading); }
        .btn-cta:hover { background: transparent; color: #fff; border-color: #fff; }

        /* CRÉDITOS */
        .creditos { background: #fff; padding: 5rem 0; }
        .creditos-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-top: 3rem; max-width: 860px; margin-left: auto; margin-right: auto; }
        .credito-card { text-align: center; }
        .credito-foto { width: 160px; height: 160px; background: var(--bg-body); margin: 0 auto 1.2rem; display: flex; align-items: center; justify-content: center; flex-direction: column; color: var(--text-light); font-size: 0.78rem; gap: 6px; }
        .credito-foto i { font-size: 2rem; color: var(--color-blue); opacity: 0.5; }
        .credito-foto img {width: 100%;height: 100%;object-fit: cover;}
        .credito-role { display: inline-block; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; color: var(--color-blue); background: rgba(127,179,239,0.1); padding: 3px 10px; margin-bottom: 0.6rem; }
        .credito-name { font-family: var(--font-heading); font-size: 1.4rem; font-weight: 700; color: var(--color-primary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.3rem; }
        .credito-desc { font-size: 0.88rem; color: var(--text-light); }
        .credito-email { margin-top: 0.5rem; }
        .credito-email a { font-size: 0.85rem; color: var(--color-blue); display: inline-flex; align-items: center; gap: 5px; }
        .credito-email a:hover { color: var(--color-primary); }
        .credito-inst { margin-top: 2.5rem; text-align: center; padding: 1.5rem 2rem; background: var(--bg-body); border-left: 4px solid var(--color-blue); max-width: 860px; margin-left: auto; margin-right: auto; }
        .credito-inst p { font-size: 0.9rem; color: var(--text-secondary); line-height: 2; }
        .credito-inst strong { color: var(--color-primary); }
        .credito-inst .year { font-family: var(--font-heading); font-size: 1.5rem; font-weight: 700; color: var(--color-blue); display: block; margin-top: 6px; }

        /* FOOTER */
        .footer { background: var(--color-primary-dark); padding: 2rem 0; text-align: center; }
        .footer p { color: rgba(255,255,255,0.6); font-size: 0.9rem; }
        .footer p a { color: var(--color-blue); }
        .footer p a:hover { color: #fff; }

        /* RESPONSIVE */
        @media(max-width: 1024px) { .features-grid { grid-template-columns: repeat(3, 1fr); } }
        @media(max-width: 768px) {
            .sobre-grid { grid-template-columns: 1fr; gap: 2rem; }
            .sobre-visual { grid-template-columns: 1fr 1fr; }
            .top-bar-left { display: none; }
            .nav-links { display: none; }
            .hero-content h1 { font-size: 2.2rem; }
            .features-grid { grid-template-columns: repeat(2, 1fr); }
            .creditos-grid { grid-template-columns: 1fr; max-width: 400px; }
        }
        @media(max-width: 480px) {
            .features-grid { grid-template-columns: 1fr; }
            .sobre-visual { grid-template-columns: 1fr; }
            .section-title { font-size: 1.8rem; }
        }
    </style>
</head>
<body>

<!-- TOP BAR -->
<div class="top-bar">
    <div class="tb-inner">
        <div class="top-bar-left">
            <a href="mailto:mxrlrey@gmail.com">
                <i class="fas fa-envelope"></i>
                mxrlrey@gmail.com
            </a>
            <a href="https://github.com/Mxrlrey" target="_blank">
                <i class="fab fa-github"></i>
                github.com/Mxrlrey
            </a>
        </div>
        <a href="/painel" class="btn-access">
            <i class="fas fa-sign-in-alt"></i>
            Acessar o Sistema
        </a>
    </div>
</div>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="nb-inner">
        <a href="#hero" class="navbar-brand">
            <div class="brand-icon">
                <i class="fas fa-satellite-dish"></i>
            </div>
            <span class="brand-name">Radar <span>Inclusivo</span></span>
        </a>
        <ul class="nav-links">
            <li><a href="#hero">Home</a></li>
            <li><a href="#sobre">Sobre</a></li>
            <li><a href="#funcionalidades">Funcionalidades</a></li>
            <li><a href="#creditos">Créditos</a></li>
        </ul>
    </div>
</nav>

<!-- HERO -->
<section class="hero" id="hero">
    <div class="hero-dots"></div>
    <div class="hero-content">
        <h1>Sobre o <span>Radar Inclusivo</span></h1>
        <div class="hero-divider"></div>
        <p>Tecnologia a serviço da inclusão e acessibilidade</p>
    </div>
</section>

<!-- SOBRE NÓS -->
<section class="sobre" id="sobre">
    <div class="container">
        <h2 class="section-title">Sobre Nós</h2>
        <p class="section-subtitle">Conheça nossa proposta e o que nos move</p>

        <div class="sobre-grid">
            <div class="sobre-text">
                <h3>O que é o Radar Inclusivo?</h3>
                <p>
                    O <strong>Radar Inclusivo</strong> é um sistema web desenvolvido como Trabalho de Conclusão de Curso
                    no Instituto Federal Baiano — Campus Guanambi. A plataforma tem como objetivo mapear,
                    registrar e acompanhar condições de acessibilidade e possíveis barreiras no ambiente institucional,
                    contribuindo para a promoção da inclusão e melhoria contínua dos espaços.
                </p>
                <p>
                    A plataforma centraliza a gestão de informações voltadas à inclusão educacional,
                    integrando o cadastro de alunos, equipes multiprofissionais, tecnologias assistivas,
                    materiais acessíveis e o mapeamento de barreiras físicas e comunicacionais.
                    Também disponibiliza funcionalidades para controle de empréstimos, gerenciamento
                    de filas de espera, organização da agenda institucional e emissão de relatórios,
                    promovendo uma visão mais estruturada, eficiente e integrada em um ambiente web
                    responsivo e acessível.
                </p>
                <ul class="sobre-list">
                    <li><i class="fas fa-check-circle"></i> Gestão integrada de dados voltados à inclusão em uma única plataforma</li>
                    <li><i class="fas fa-check-circle"></i> Controle de tecnologias assistivas e materiais pedagógicos acessíveis</li>
                    <li><i class="fas fa-check-circle"></i> Registro e monitoramento de barreiras de acessibilidade</li>
                    <li><i class="fas fa-check-circle"></i> Gerenciamento de agenda institucional e fila de espera</li>
                    <li><i class="fas fa-check-circle"></i> Interface responsiva, adaptável a diferentes dispositivos</li>
                </ul>
            </div>

            <div class="sobre-visual">
                <div class="stat-card">
                    <div class="stat-num"><span>18</span></div>
                    <div class="stat-label">Funcionalidades</div>
                </div>
                <div class="stat-card">
                    <div class="stat-num"><span>100</span>%</div>
                    <div class="stat-label">Web &amp; Mobile Ready</div>
                </div>
                <div class="stat-card">
                    <div class="stat-num" style="font-size:1.6rem;"><span>TCC</span></div>
                    <div class="stat-label">Projeto Acadêmico · IFBaiano</div>
                </div>
                <div class="stat-card">
                    <div class="stat-num"><span>2026</span></div>
                    <div class="stat-label">Ano de Desenvolvimento</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FUNCIONALIDADES -->
<section class="funcionalidades" id="funcionalidades">
    <div class="container">
        <h2 class="section-title white">Funcionalidades</h2>
        <p class="section-subtitle white">18 módulos integrados para a gestão completa do AEE</p>

        <div class="features-grid">

            <div class="feature-card">
                <div class="feature-icon-wrap"><i class="fas fa-tachometer-alt"></i></div>
                <h5>Dashboard</h5>
                <p>Visão geral do sistema com indicadores, atalhos e informações rápidas para o gestor.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon-wrap"><i class="fas fa-chart-bar"></i></div>
                <h5>Relatórios</h5>
                <p>Geração e visualização de dados consolidados — estatísticas, acompanhamentos e histórico.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon-wrap"><i class="fas fa-bell"></i></div>
                <h5>Notificações</h5>
                <p>Lista de avisos importantes do sistema como eventos, atualizações e alertas relevantes.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon-wrap"><i class="fas fa-database"></i></div>
                <h5>Backups</h5>
                <p>Gerenciamento de cópias de segurança do sistema para garantir a integridade dos dados.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon-wrap"><i class="fas fa-wheelchair"></i></div>
                <h5>Deficiências</h5>
                <p>Cadastro dos tipos de deficiência dos alunos atendidos pelo serviço de AEE.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon-wrap"><i class="fas fa-id-badge"></i></div>
                <h5>Cargos</h5>
                <p>Define funções dos profissionais e suas permissões — professor AEE, secretário e outros.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon-wrap"><i class="fas fa-universal-access"></i></div>
                <h5>Recursos de Acessibilidade</h5>
                <p>Cadastro de categorias de recursos como braille, intérprete de Libras, entre outros.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon-wrap"><i class="fas fa-exclamation-triangle"></i></div>
                <h5>Categorias de Barreiras</h5>
                <p>Classificação das barreiras encontradas — física, comunicacional, atitudinal e mais.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon-wrap"><i class="fas fa-building"></i></div>
                <h5>Instituições</h5>
                <p>Cadastro da instituição base que utiliza o sistema de gestão inclusiva.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon-wrap"><i class="fas fa-map-marker-alt"></i></div>
                <h5>Localizações</h5>
                <p>Locais físicos dentro das instituições como salas, setores e ambientes específicos.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon-wrap"><i class="fas fa-user-graduate"></i></div>
                <h5>Alunos</h5>
                <p>Cadastro e gestão completa dos alunos atendidos pelo serviço de educação especial.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon-wrap"><i class="fas fa-users"></i></div>
                <h5>Equipe</h5>
                <p>Profissionais do AEE — psicólogos, pedagogos, fonoaudiólogos e demais especialistas.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon-wrap"><i class="fas fa-laptop-medical"></i></div>
                <h5>Tecnologias Assistivas</h5>
                <p>Controle de equipamentos e recursos tecnológicos destinados à acessibilidade dos alunos.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon-wrap"><i class="fas fa-book-open"></i></div>
                <h5>Materiais Pedagógicos</h5>
                <p>Materiais adaptados para apoio ao ensino inclusivo e ao desenvolvimento dos alunos.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon-wrap"><i class="fas fa-ban"></i></div>
                <h5>Barreiras</h5>
                <p>Registro de problemas de acessibilidade encontrados na instituição para acompanhamento.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon-wrap"><i class="fas fa-exchange-alt"></i></div>
                <h5>Empréstimos</h5>
                <p>Controle de empréstimo e devolução de recursos e equipamentos assistivos.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon-wrap"><i class="fas fa-list-ol"></i></div>
                <h5>Fila de Espera</h5>
                <p>Gestão de pessoas aguardando recursos ou atendimento especializado disponível.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon-wrap"><i class="fas fa-calendar-alt"></i></div>
                <h5>Agenda Institucional</h5>
                <p>Eventos e atividades planejadas da instituição com visualização centralizada.</p>
            </div>

        </div>
    </div>
</section>

<!-- CTA — CÓDIGO FONTE -->
<div class="cta-banner">
    <div class="container">
        <h2>Acesse o Código Fonte</h2>
        <p>O Radar Inclusivo é um projeto acadêmico aberto. Explore o repositório no GitHub.</p>
        <a href="https://github.com/Mxrlrey/radar-inclusivo" target="_blank" class="btn-cta">
            <i class="fab fa-github"></i>
            Ver Repositório
        </a>
    </div>
</div>

<!-- CRÉDITOS -->
<section class="creditos" id="creditos">
    <div class="container">
        <h2 class="section-title">Créditos</h2>
        <p class="section-subtitle">Pessoas por trás do Radar Inclusivo</p>

        <div class="creditos-grid">
            <div class="credito-card">
                <div class="credito-foto">
                    <img src="{{ asset('images/team/marley.jpg') }}" alt="Foto do desenvolvedor" class="img-responsive">
                </div>
                <div class="credito-role">Desenvolvimento</div>
                <div class="credito-name">Marley Teixeira Meira</div>
                <div class="credito-email">
                    <a href="mailto:mxrlrey@gmail.com"><i class="fas fa-envelope"></i> mxrlrey@gmail.com</a>
                </div>
            </div>

            <div class="credito-card">
                <div class="credito-foto">
                    <img src="{{ asset('images/team/woquiton.jpg') }}" alt="Foto do orientador" class="img-responsive">
                </div>
                <div class="credito-role">Orientação / Coordenação</div>
                <div class="credito-name">Prof. Woquiton Fernandes</div>
                <div class="credito-email">
                    <a href="mailto:woquiton@gmail.com"><i class="fas fa-envelope"></i> woquiton@gmail.com</a>
                </div>
            </div>
        </div>

        <div class="credito-inst">
            <p>
                <strong>Instituição:</strong> Instituto Federal Baiano — Campus Guanambi<br>
                <strong>Curso:</strong> Tecnologia em Análise e Desenvolvimento de Sistemas<br>
                <strong>Projeto:</strong> Trabalho de Conclusão de Curso (TCC)
            </p>

            <p style="margin-top:10px;">
                O Radar Inclusivo foi desenvolvido com foco na promoção da acessibilidade
                e inclusão dentro do ambiente educacional, buscando oferecer ferramentas
                que auxiliem no acompanhamento e melhoria contínua das condições institucionais.
            </p>

            <span class="year">2026</span>
        </div>
    </div>
</section>

<script>
    const sections = document.querySelectorAll("section");
    const navLinks = document.querySelectorAll(".nav-links a");

    window.addEventListener("scroll", () => {
        let current = "";

        sections.forEach(section => {
            const sectionTop = section.offsetTop - 100;
            if (scrollY >= sectionTop) {
                current = section.getAttribute("id");
            }
        });

        navLinks.forEach(a => {
            a.classList.remove("active");
            if (a.getAttribute("href") === "#" + current) {
                a.classList.add("active");
            }
        });
    });
</script>

<!-- FOOTER -->
<footer class="footer">
    <div class="container">
        <p>
            &copy; 2026 Radar Inclusivo — Trabalho de Conclusão de Curso · IFBaiano Campus Guanambi &nbsp;|&nbsp;
            <a href="https://github.com/Mxrlrey/radar-inclusivo" target="_blank"><i class="fab fa-github"></i> Código Fonte</a>
        </p>
    </div>
</footer>

</body>
</html>
