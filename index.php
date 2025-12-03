<!DOCTYPE html>
<?php
session_start();
require_once 'conexaoLocal.php';
?>

<html lang="pt-br">
<head>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Lagoa Dourada FM</title>
    <style>
        :root {
            --primary-dark: #1a1a1a;
            --primary-color: #2d2d2d;
            --accent-color: #d2974a;
            --text-light: #f5f5f5;
            --text-dark: #e0e0e0;
            --background-dark: #121212;
            --gold-light: #fff3b0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            background-color: var(--background-dark);
            color: var(--text-dark);
            line-height: 1.6;
            touch-action: pan-y;
        }

        header {
            background-color: var(--primary-dark);
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 2px solid var(--accent-color);
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .video-container {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
            height: 0;
            overflow: hidden;
            border-radius: 12px;
            margin: 2rem 0;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            border: 1px solid var(--accent-color);
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        .logo img {
            height: 80px;
            transition: transform 0.3s ease;
        }

        .logo img:hover {
            transform: rotate(-5deg);
        }

        nav ul {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        nav a {
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 500;
            position: relative;
            padding: 0.5rem 0;
        }

        nav a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent-color);
            transition: width 0.3s ease;
        }

        nav a:hover::after {
            width: 100%;
        }

        .hero {
            background: linear-gradient(to right, var(--primary-dark), var(--primary-color));
            padding: 4rem 0;
            text-align: center;
            border-bottom: 3px solid var(--accent-color);
        }

        .hero h1 {
            font-size: 2.8rem;
            color: var(--accent-color);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            margin-bottom: 1.5rem;
        }

        .player-container {
            background: var(--primary-color);
            position: relative;
            border-radius: 12px;
            padding: 2.5rem;
            margin: -50px auto 2rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
            border: 1px solid var(--accent-color);
            display: grid;
            gap: 1.5rem;
        }

        .station-selector {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            width: 100%;
            justify-content: center;
        }

        .station-btn {
            background: rgba(255, 215, 0, 0.1);
            border: 1px solid var(--accent-color);
            color: var(--text-dark);
            padding: 0.8rem 1.5rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .station-btn.active {
            background: var(--accent-color);
            color: var(--primary-dark);
            font-weight: 500;
        }

        .station-btn:hover:not(.active) {
            background: rgba(255, 215, 0, 0.2);
        }

        .radio-selector {
            background: rgba(255, 215, 0, 0.1);
            border: 1px solid var(--accent-color);
            color: var(--text-dark);
            padding: 0.8rem;
            border-radius: 6px;
            width: 100%;
            margin-bottom: 1.5rem;
        }

        .play-btn {
            background: var(--accent-color);
            width: 160px;
            height: 60px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(210, 151, 74, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .play-btn i {
            font-size: 1.6rem;
            margin: 0;
            transition: transform 0.2s ease;
        }

        .play-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 25px rgba(210, 151, 74, 0.4);
        }

        .play-btn:hover i {
            transform: translateX(2px);
        }

        .play-btn.paused i {
            transform: translateX(0);
        }

        .now-playing-info {
            background: rgba(0, 0, 0, 0.3);
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid var(--accent-color);
            margin: 1.5rem 0;
            text-align: center;
        }

        #radio-city {
            font-size: 1.4rem;
            color: var(--accent-color);
            margin-bottom: 0.5rem;
        }

        .player-controls {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
        }

        .volume-container {
            width: 100%;
            max-width: 250px;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .volume-slider {
            accent-color: var(--accent-color);
            width: 100%;
            height: 6px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }

        .volume-slider::-webkit-slider-thumb {
            width: 16px;
            height: 16px;
            background: var(--accent-color);
            border: none;
            border-radius: 50%;
            transition: transform 0.2s ease;
        }

        .volume-slider::-webkit-slider-thumb:hover {
            transform: scale(1.2);
        }

        .volume-labels {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 250px;
            margin-top: -8px;
            font-size: 0.8rem;
            color: var(--text-dark);
        }

        .section-title {
            color: var(--accent-color);
            text-align: center;
            margin: 3rem 0;
            position: relative;
            font-size: 2.2rem;
        }

        .section-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 3px;
            background: var(--accent-color);
            margin: 1rem auto;
        }

        .map-container {
            border: 2px solid var(--accent-color);
            border-radius: 12px;
            overflow: hidden;
        }

        .radio-list li {
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 215, 0, 0.1);
            transition: all 0.3s ease;
        }

        .radio-label {
            color: var(--accent-color);
            font-size: 1.2rem;
            text-align: center;
            padding: 1rem;
            border: 1px solid rgba(255, 215, 0, 0.2);
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }

        .radio-list li:hover {
            background: rgba(255, 215, 0, 0.05);
            transform: translateX(10px);
        }

        footer {
            background: var(--primary-dark);
            padding: 3rem 0;
            margin-top: 4rem;
            border-top: 3px solid var(--accent-color);
        }

        .news-section {
            padding: 4rem 0;
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .news-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s ease;
            border: 1px solid rgba(255, 215, 0, 0.1);
        }

        .news-card:hover {
            transform: translateY(-5px);
        }

        .news-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 2px solid var(--accent-color);
        }

        .news-content {
            padding: 1.5rem;
        }

        .news-title {
            color: var(--accent-color);
            margin-bottom: 0.8rem;
            font-size: 1.1rem;
        }

        .news-description {
            color: var(--text-dark);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .news-read-more {
            display: inline-block;
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.3s ease;
        }

        .news-read-more:hover {
            opacity: 0.8;
        }

        .news-card .match-date {
            font-size: 0.85rem;
            color: var(--accent-color);
            margin: 0.5rem 0;
            opacity: 0.8;
        }

        .about-section {
            background: linear-gradient(to bottom, var(--primary-dark), var(--background-dark));
            padding: 4rem 0;
        }

        .about-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .about-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 2rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 215, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .about-card:hover {
            transform: translateY(-5px);
        }

        .about-card h3 {
            color: var(--accent-color);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .about-card h3 i {
            font-size: 1.5rem;
        }

        .team-member {
            display: grid;
            grid-template-columns: 60px 1fr;
            gap: 1rem;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255, 215, 0, 0.1);
        }

        .team-member:last-child {
            border-bottom: none;
        }

        .team-photo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary-color);
            border: 2px solid var(--accent-color);
            background-size: cover;
            background-position: center;
        }

        .team-info h4 {
            color: var(--text-light);
            margin-bottom: 0.3rem;
        }

        .team-role {
            color: var(--text-dark);
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .team-role i {
            margin-right: 8px;
            color: var(--accent-color);
            font-size: 0.9em;
        }

        #player-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: var(--accent-color);
            z-index: 10;
            transition: opacity 0.3s ease;
        }

        .video-container iframe[src*="youtube"] ~ #player-loading {
            opacity: 0;
            pointer-events: none;
        }

        .contact-info {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin: 1.5rem 0;
            padding: 1rem;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .contact-info i {
            font-size: 1.5rem;
            color: var(--accent-color);
            min-width: 30px;
        }

        .contact-info p:first-child {
            color: var(--accent-color);
            font-weight: 500;
            margin-bottom: 0.3rem;
        }

        .program-schedule {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 2rem;
            margin-top: 2rem;
            border: 1px solid rgba(255, 215, 0, 0.1);
            }

        .tabs {
            display: flex;
            gap: 1rem;
            overflow-x: auto;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }

        .tab-btn {
            background: none;
            border: none;
            color: var(--text-dark);
            padding: 0.8rem 1.5rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
            border: 1px solid transparent;
        }

        .tab-btn.active {
            background: var(--accent-color);
            color: var(--primary-dark);
            border-color: var(--accent-color);
            font-weight: 500;
        }

        .tab-btn:hover:not(.active) {
            background: rgba(255, 215, 0, 0.1);
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.4s ease forwards;
        }

        .participate-container {
            text-align: center;
            margin: 2rem 0 4rem;
        }

        .button-group {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .participate-title {
            color: var(--accent-color);
            margin-bottom: 1.5rem;
            font-size: 1.4rem;
        }

        .participate-btn {
            padding: 1rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.8rem;
            border: 2px solid transparent;
        }

        .youtube-btn {
            background: #FF0000; /* Vermelho oficial do YouTube */
            color: white;
            border: 2px solid #FF0000;
        }

        .facebook-btn {
            background: #1877f2;
            color: white;
        }

        .participate-btn:hover {
            background: transparent;
            transform: translateY(-2px);
        }

        .youtube-btn:hover {
            background: transparent;
            color: #FF0000;
            border-color: #FF0000;
        }

        .facebook-btn:hover {
            color: #1877f2;
            border-color: #1877f2;
        }

        #ytLiveLink,
        #fbLiveLink {
            display: none;
        }

        .button-group {
            justify-content: center;
            gap: 1.5rem;
            min-height: 50px;
        }

        .tab-content.active {
            display: block;
        }

        .schedule-grid {
            display: grid;
            grid-template-columns: 100px 1fr;
            gap: 1.5rem;
            align-items: start;
        }

        .time-slot {
            color: var(--accent-color);
            font-weight: 500;
            padding: 0.5rem;
            text-align: right;
            position: relative;
        }

        .time-slot::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 10px;
            height: 2px;
            background: var(--accent-color);
            opacity: 0.5;
        }

        .program-item {
            background: rgba(0, 0, 0, 0.2);
            padding: 1.2rem;
            border-radius: 8px;
            border-left: 3px solid var(--accent-color);
            transition: transform 0.3s ease;
            margin-bottom: 1rem;
        }

        .program-item:hover {
            transform: translateX(10px);
        }

        .program-name {
            color: var(--text-light);
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .program-host {
            color: var(--text-dark);
            font-size: 0.95rem;
            line-height: 1.5;
            opacity: 0.9;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--text-dark);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            margin-left: auto;
        }

        .mobile-menu-btn:hover {
            color: var(--accent-color);
        }

        .no-results {
            text-align: center;
            padding: 2rem;
            grid-column: 1 / -1;
            color: var(--text-dark);
            opacity: 0.7;
        }

        .no-results i {
            font-size: 2rem;
            margin-bottom: 1rem;
            display: block;
            color: var(--accent-color);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }

            .mobile-menu-btn {
                display: block;
            }

            .player-container {
                padding: 1.5rem;
            }

            .play-btn {
                width: 120px;
                height: 50px;
            }

            header .container {
                display: flex;
                align-items: center;
            }

            .play-btn i {
                font-size: 1.3rem;
            }

            #radio-city {
                font-size: 1.2rem;
            }

            nav ul {
                flex-direction: column;
                display: none;
                gap: 2rem;
                list-style: none;
                transition: all 0.3s ease;
            }

            nav ul.mobile-open {
                display: flex;
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: var(--primary-dark);
                padding: 1rem 0;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
                z-index: 99;
            }

            .about-grid {
                grid-template-columns: 1fr;
            }
            
            .contact-info {
                flex-direction: column;
                text-align: center;
            }
            
            .team-member {
                grid-template-columns: 1fr;
                text-align: center;
            }
            
            .team-photo {
                margin: 0 auto;
            }

            .schedule-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .time-slot {
                text-align: left;
                padding-left: 1.5rem;
            }
            
            .time-slot::after {
                left: 0;
                right: auto;
                width: 5px;
                height: 100%;
                top: 0;
                transform: none;
            }
            
            .program-item {
                margin-bottom: 0;
            }

            .logo img {
                height: 60px;
            }
            
            .tabs {
                gap: 0.5rem;
                padding-bottom: 0.5rem;
            }
            
            .tab-btn {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .button-group {
                flex-direction: column;
            }
            
            .participate-btn {
                justify-content: center;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <img src="assets/img/logo-lagoa.png" alt="Lagoa Dourada FM">
            </div>
            <nav>
                <button class="mobile-menu-btn" aria-label="Abrir menu">
                    <i class="fas fa-bars"></i>
                </button>
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#video-player">Ao Vivo</a></li>
                    <li><a href="#schedule">Programação</a></li>
                    <li><a href="#esporte">Esporte</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section id="home" class="hero">
        <div class="container">
            <h1>Lagoa Dourada FM</h1>
            <p>Música, Esporte e Informação</p>
        </div>
    </section>

    <div class="container">
        <div class="player-container">
            <div class="now-playing-info">
            <div id="now-playing">
                <div class="now-playing-title">Agora tocando</div>
                <div id="radio-city">Selecione uma frequência</div>
                <div class="track-info">
                    <div id="current-track">Carregando informação musical...</div>
                </div>
            </div>
            </div>
            
            <div class="player-controls">
                <div class="station-selector">
                <button class="station-btn active" 
                        data-station="pg"
                        data-url="https://s1.mediacp.com.br:8040/stream"
                        data-city="FM 98,5 MHz Ponta Grossa">
                    FM 98,5 - Ponta Grossa
                </button>
                
                <button class="station-btn" 
                        data-station="tb"
                        data-url="https://s1.mediacp.com.br:8042/stream"
                        data-city="FM 94,9 MHz Telêmaco Borba">
                    FM 94,9 - Telêmaco Borba
                </button>
                </div>

                <button class="play-btn" id="play-pause-btn">
                    <i class="fas fa-play"></i>
                </button>
                
                <div class="volume-container">
                    <i class="fas fa-volume-down" style="color: var(--accent-color)"></i>
                    <input type="range" class="volume-slider" min="0" max="100" value="80">
                    <i class="fas fa-volume-up" style="color: var(--accent-color)"></i>
                </div>
            </div>
        </div>
    </div>
    <section id="video-player" class="content-section">
        <div class="container">
            <h2 class="section-title">Assista Ao Vivo</h2>
            <div class="video-container">
                <div id="player-loading">Carregando transmissão...</div>
                <iframe
                    id="ytPlayer"
                    src="about:blank"
                    frameborder="0"
                    allowfullscreen>
                </iframe>
            </div>
            <div class="participate-container">
                <h3 class="participate-title">Participe do programa</h3>
                <div class="button-group">
                    <a id="ytLiveLink" class="participate-btn youtube-btn" target="_blank" rel="noopener noreferrer">
                        Participe pelo YouTube <i class="fas fa-external-link-alt"></i>
                    </a>
                    <a id="fbLiveLink" class="participate-btn facebook-btn"
                    href="https://www.facebook.com/fmlagoadourada/"
                    target="_blank" 
                    rel="noopener noreferrer">
                        Participe pelo Facebook <i class="fab fa-facebook-f"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="schedule" class="content-section">
        <div class="container">
            <h2 class="section-title">Programação</h2>
            <div class="program-schedule">
                <div class="tabs">
                    <?php 
                    $dias_semana = [
                        0 => 'Domingo',
                        1 => 'Segunda',
                        2 => 'Terça',
                        3 => 'Quarta',
                        4 => 'Quinta', 
                        5 => 'Sexta',
                        6 => 'Sábado'
                    ];
                    
                    foreach ($dias_semana as $num => $dia): ?>
                        <button class="tab-btn <?= $num == date('w') ? 'active' : '' ?>" 
                                data-tab="tab-<?= $num ?>">
                            <?= $dia ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                
                <?php foreach ($dias_semana as $num => $dia): ?>
                    <div id="tab-<?= $num ?>" 
                        class="tab-content <?= $num == date('w') ? 'active' : '' ?>">
                        <div class="schedule-grid">
                            <?php if (!empty($programas_por_dia[$num])): ?>
                                <?php foreach ($programas_por_dia[$num] as $programa): ?>
                                    <div class="time-slot">
                                        <?= date("H:i", strtotime($programa['inicio'])) ?>
                                    </div>
                                    <div class="program-item">
                                        <div class="program-name"><?= htmlspecialchars($programa['nome']) ?></div>
                                        <div class="program-host"><?= strip_tags($programa['inf'], '<a>') ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="no-results">
                                    <i class="fas fa-calendar-times"></i>
                                    Nenhum programa cadastrado neste dia
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="esporte" class="news-section">
        <div class="container">
            <h2 class="section-title">Notícias do Esporte</h2>
            <div class="news-grid">
                <?php
                $api_key = '221e7b47fd874e8d812a54ffd933f030';
                $query = urlencode('("Operário-PR" OR "Operário PR" OR "Campeonato Paranaense" OR "Serie B")');
                $url = "https://newsapi.org/v2/everything?q=$query&apiKey=$api_key&language=pt&pageSize=6&sortBy=publishedAt&from=" . date('Y-m-d', strtotime('-3 days'));
                
                try {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
                    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_USERAGENT, 'LagoaDouradaFM/1.0 (+https://www.lagoadouradafm.com.br)');
                    curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Api-Key: ' . $api_key]);
                    $response = curl_exec($ch);
                    
                    if(curl_errno($ch)) {
                        throw new Exception('Erro na conexão: ' . curl_error($ch));
                    }
                    
                    $news = json_decode($response, true);
                    
                    if($news['status'] === 'ok' && !empty($news['articles'])) {
                        foreach($news['articles'] as $article) {
                            $image = !empty($article['urlToImage']) ? $article['urlToImage'] : 'assets/img/fallback-sports.jpg';
                            $title = htmlspecialchars($article['title'] ?? 'Notícia do Operário');
                            $description = htmlspecialchars(
                                substr($article['description'] ?? 'Clique para ler a matéria completa sobre o Operário', 0, 120)
                            );
                            $url = htmlspecialchars($article['url'] ?? '#');
                            
                            echo <<<HTML
                            <div class="news-card">
                                <img src="$image" 
                                    class="news-image" 
                                    alt="$title"
                                    onerror="this.src='assets/img/fallback-sports.jpg'">
                                <div class="news-content">
                                    <h3 class="news-title">$title</h3>
                                    <p class="news-description">$description...</p>
                                    <a href="$url" 
                                    class="news-read-more"
                                    target="_blank"
                                    rel="noopener noreferrer">
                                        Leia mais →
                                    </a>
                                </div>
                            </div>
    HTML;
                        }
                    } else {
                        $error = htmlspecialchars($news['message'] ?? 'Nenhuma notícia recente sobre o Operário');
                        echo "<p class='no-results'>⛔ $error</p>";
                    }
                    
                    curl_close($ch);
                    
                } catch(Exception $e) {
                    echo "<p class='no-results'>⚠️ Erro: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
                ?>
            </div>
        </div>
    </section>

                <section id="about" class="about-section">
                    <div class="container">
                        <h2 class="section-title" style="color: var(--text-light);">Institucional</h2>
                        
                        <div class="about-grid">                          
                            <!-- Equipe Geral -->
                            <div class="about-card">
                                <h3><i class="fas fa-users"></i> Nossa Equipe</h3>
                                
                                <?php if (!empty($equipe_geral)): ?>
                                    <?php foreach ($equipe_geral as $membro): ?>
                                        <div class="team-member">
                                            <div class="team-info">
                                                <h4><?= htmlspecialchars($membro['nome']) ?></h4>
                                                <div class="team-role"><?= htmlspecialchars($membro['cargo']) ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p style="color: #666; text-align: center;">Nossa equipe está sendo atualizada...</p>
                                <?php endif; ?>
                            </div>

                            <!-- Equipe Esporte -->
                            <div class="about-card">
                                <h3><i class="fas fa-soccer-ball"></i> Equipe Esporte</h3>
                                <?php if (!empty($equipe_esporte)): ?>
                                    <?php foreach ($equipe_esporte as $membro): ?>
                                        <div class="team-member">
                                            <div class="team-info">
                                                <h4><?= htmlspecialchars($membro['nome']) ?></h4>
                                                <div class="team-role"><?= htmlspecialchars($membro['cargo']) ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p style="color: #666; text-align: center;">Nossa equipe esportiva está carregando...</p>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Contato -->
                            <div class="about-card">
                                <h3><i class="fas fa-envelope"></i> Contato</h3>
                                <div class="contact-info">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <div>
                                        <p>Matriz</p>
                                        <p>Av. General Carlos Cavalcanti, 1386 - Uvaranas - Ponta Grossa, PR</p>
                                        <p>CEP 84025-000</p>
                                    </div>
                                </div>
                                
                                <div class="contact-info">
                                    <i class="fas fa-phone"></i>
                                    <div>
                                        <p>Central de Atendimento</p>
                                        <p>(42) 3220-9000</p>
                                        <p>Whatsapp do ouvinte: (41) 98401-0001</p>
                                    </div>
                                </div>
                                
                                <div class="contact-info">
                                    <i class="fas fa-envelope"></i>
                                    <div>
                                        <p>E-mail</p>
                                        <p>falecom@radiot.fm</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <footer>
                    <div class="container">
                        <div class="footer-grid">
                            <div>
                                <h3 class="footer-heading">Rádio T</h3>
                                <p>A maior rede de rádios do estado, conectando os paranaenses através de conteúdo de qualidade.</p>
                                <div style="margin-top: 1rem;">
                                <a href="https://www.instagram.com/radiolagoadourada/" target="_blank" rel="noopener noreferrer" style="color: inherit; text-decoration: none;">
                                        <i class="fab fa-instagram fa-lg" style="margin-right: 1rem; cursor: pointer;"></i>
                                    </a>
                                    <a href="https://www.facebook.com/fmlagoadourada/" target="_blank" rel="noopener noreferrer" style="color: inherit; text-decoration: none;">
                                        <i class="fab fa-facebook fa-lg" style="margin-right: 1rem; cursor: pointer;"></i>
                                    </a>
                                    <a href="https://www.youtube.com/channel/UCw4TrxqCpuJiW3bLCFQR3UA" target="_blank" rel="noopener noreferrer" style="color: inherit; text-decoration: none;">
                                        <i class="fab fa-youtube fa-lg" style="margin-right: 1rem; cursor: pointer;"></i>
                                    </a>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="footer-heading">Links Rápidos</h3>
                                <ul class="footer-links">
                                    <li><a href="#home">Home</a></li>
                                    <li><a href="#schedule">Programação</a></li>
                                    <li><a href="#video-player">Ao Vivo</a></li>
                                    <li><a href="#esporte">Esporte</a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="copyright">
                            &copy; Todos os direitos reservados © Lagoa Dourada FM
                        </div>
                    </div>
                </footer>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Player de Áudio
                        const player = new Audio();
                        let currentStation = 'https://s1.mediacp.com.br:8040/stream';
                        player.src = currentStation;
                        player.volume = document.querySelector('.volume-slider').value / 100;

                        // Elementos da UI
                        const cityElement = document.getElementById('radio-city');
                        const tabButtons = document.querySelectorAll('.tab-btn');
                        const tabContents = document.querySelectorAll('.tab-content');

                        // Função para ativar abas
                        function activateTab(tabNumber) {
                            // Remover classes ativas
                            tabButtons.forEach(btn => btn.classList.remove('active'));
                            tabContents.forEach(content => content.classList.remove('active'));

                            // Ativar nova aba
                            const activeButton = document.querySelector(`[data-tab="tab-${tabNumber}"]`);
                            const activeContent = document.getElementById(`tab-${tabNumber}`);
                            
                            if(activeButton && activeContent) {
                                activeButton.classList.add('active');
                                activeContent.classList.add('active');
                            }
                        }

                        // Menu mobile
                        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
                        const navList = document.querySelector('nav ul');

                        mobileMenuBtn.addEventListener('click', () => {
                            navList.classList.toggle('mobile-open');
                            
                            // Alternar ícone entre barras e X
                            const icon = mobileMenuBtn.querySelector('i');
                            if (navList.classList.contains('mobile-open')) {
                                icon.classList.replace('fa-bars', 'fa-times');
                            } else {
                                icon.classList.replace('fa-times', 'fa-bars');
                            }
                        });

                        document.querySelectorAll('nav a').forEach(link => {
                            link.addEventListener('click', () => {
                                if (window.innerWidth <= 768) {
                                    navList.classList.remove('mobile-open');
                                    const icon = mobileMenuBtn.querySelector('i');
                                    icon.classList.replace('fa-times', 'fa-bars');
                                }
                            });
                        });

                        // Event listeners para abas
                        tabButtons.forEach(button => {
                            button.addEventListener('click', function() {
                                const tabNumber = this.getAttribute('data-tab').split('-')[1];
                                activateTab(tabNumber);
                            });
                        });

                        // Ativar aba do dia atual
                        const currentDayIndex = new Date().getDay();
                        activateTab(currentDayIndex);

                        // Função para buscar música atual
                        async function getCurrentTrack(stationUrl) {
                            try {
                                const apiUrl = stationUrl.replace('/stream', '/status-json.xsl');
                                const response = await fetch(apiUrl);
                                const data = await response.json();
                                return data.icestats.source.title || 'Informação não disponível';
                            } catch (error) {
                                console.error('Erro ao buscar dados:', error);
                                return 'Não foi possível carregar a informação';
                            }
                        }

                        // Atualizar música inicial
                        getCurrentTrack(currentStation).then(track => {
                            document.getElementById('current-track').textContent = track;
                        });

                        // Controles do player de rádio
                        document.querySelectorAll('.station-btn').forEach(button => {
                            button.addEventListener('click', function(e) {
                                const url = this.getAttribute('data-url');
                                const city = this.getAttribute('data-city');
                                changeStation(url, city, e);
                            });
                        });

                        async function changeStation(url, city, event) {
                            const button = event.target.closest('.station-btn');
                            
                            // Atualizar interface
                            document.querySelectorAll('.station-btn').forEach(btn => {
                                btn.classList.remove('active');
                            });
                            button.classList.add('active');
                            cityElement.textContent = city;

                            // Exibir estado de carregamento
                            const trackElement = document.getElementById('current-track');
                            trackElement.innerHTML = '<span class="loading-track">Carregando</span>';
                            
                            // Trocar fonte se necessário
                            if (currentStation !== url) {
                                const wasPlaying = !player.paused;
                                player.pause();
                                player.src = url;
                                currentStation = url;
                                
                                // Atualizar a música atual após 2 segundos
                                setTimeout(async () => {
                                    const track = await getCurrentTrack(url);
                                    trackElement.textContent = track;
                                }, 2000);

                                if (wasPlaying) {
                                    player.play().catch(error => {
                                        console.error('Erro ao reproduzir:', error);
                                    });
                                }
                            }
                        }

                        // Controles de play/pause
                        document.getElementById('play-pause-btn').addEventListener('click', () => {
                            if (player.paused) {
                                player.play().catch(error => {
                                    console.error('Erro ao iniciar:', error);
                                });
                            } else {
                                player.pause();
                            }
                        });

                        // Controle de volume
                        document.querySelector('.volume-slider').addEventListener('input', (e) => {
                            player.volume = e.target.value / 100;
                        });

                        // Atualizar ícone do player
                        player.addEventListener('play', () => {
                            document.getElementById('play-pause-btn').querySelector('i')
                                .classList.replace('fa-play', 'fa-pause');
                        });

                        player.addEventListener('pause', () => {
                            document.getElementById('play-pause-btn').querySelector('i')
                                .classList.replace('fa-pause', 'fa-play');
                        });

                        // Atualização periódica da música
                        setInterval(async () => {
                            const track = await getCurrentTrack(currentStation);
                            document.getElementById('current-track').textContent = track;
                        }, 30000);

                        // Player de Vídeo
                        async function initVideoPlayer() {
                            const player = document.getElementById('ytPlayer');
                            const loading = document.getElementById('player-loading');

                            try {
                                const response = await fetch(`get_video.php?channel_id=UCw4TrxqCpuJiW3bLCFQR3UA`);
                                const data = await response.json();

                                if (data.error) throw new Error(data.error);

                                player.onload = () => {
                                    loading.style.opacity = '0';
                                    setTimeout(() => {
                                        loading.style.display = 'none';
                                    }, 300); // Tempo para a transição de opacidade
                                };

                                if (data.isLive) {
                                    player.src = `https://www.youtube.com/embed/live_stream?channel=UCw4TrxqCpuJiW3bLCFQR3UA&autoplay=1&mute=1`;
                                } else {
                                    player.src = `https://www.youtube.com/embed/${data.id}?autoplay=1&mute=1`;
                                }
                                if (data.id) {
                                    const ytLink = document.getElementById('ytLiveLink');
                                    const fbLink = document.getElementById('fbLiveLink');
                                    
                                    ytLink.href = `https://www.youtube.com/watch?v=${data.id}`;
                                    
                                    if (data.isLive) {
                                        // Mostrar ambos os botões se estiver ao vivo
                                        ytLink.style.display = 'flex';
                                        fbLink.style.display = 'flex';
                                        document.querySelector('.participate-title').textContent = 'Quer participar do programa?';
                                    } else {
                                        // Ocultar ambos se não for live
                                        ytLink.style.display = 'none';
                                        fbLink.style.display = 'none';
                                        document.querySelector('.participate-title').textContent = 'Programa não está ao vivo no momento';
                                    }
                                } else {
                                    document.getElementById('ytLiveLink').style.display = 'none';
                                    document.getElementById('fbLiveLink').style.display = 'none';
                                }

                            } catch (error) {
                                loading.innerHTML = 'Transmissão indisponível';
                                console.error('Erro no player:', error);
                            }
                        }

                        // Inicializar player de vídeo
                        initVideoPlayer();
                    });
                </script>
</body>
</html>