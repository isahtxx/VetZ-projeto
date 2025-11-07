<?php
// Inicia sessão (só uma vez, graças ao layout.php)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$title = "Vídeos - VetZ";
ob_start();
?>

<!-- Estilos personalizados -->
<style>
    .youtube-section {
        padding: 80px 0;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9f7ef 100%);
        min-height: 80vh;
    }
    .youtube-section h2 {
        color: #038654;
        font-weight: 700;
        text-align: center;
        margin-bottom: 40px;
        font-size: 2.2rem;
    }
    .video-buttons {
        display: flex;
        justify-content: center;
        gap: 16px;
        margin-bottom: 40px;
        flex-wrap: wrap;
    }
    .video-buttons button {
        padding: 12px 28px;
        border: 2px solid #038654;
        background: #fff;
        color: #038654;
        font-weight: 600;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s ease;
        min-width: 160px;
    }
    .video-buttons button.active,
    .video-buttons button:hover {
        background: #038654;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(3,134,84,0.25);
    }
    #video-container {
        min-height: 300px;
    }
    .video-item {
        transition: transform 0.3s ease;
    }
    .video-item:hover {
        transform: translateY(-8px);
    }
    .loader {
        text-align: center;
        padding: 60px 20px;
        color: #038654;
        font-size: 1.1rem;
    }
    @media (max-width: 768px) {
        .video-buttons button {
            padding: 10px 20px;
            font-size: 0.9rem;
            min-width: 140px;
        }
        .youtube-section h2 {
            font-size: 1.8rem;
        }
    }
</style>

<!-- Conteúdo Principal -->
<main>
    <section class="youtube-section">
        <div class="container">
            <h2>Vídeos Educativos sobre Pets</h2>

            <!-- Botões de Filtro -->
            <div class="video-buttons">
                <button class="recentes active">MAIS RECENTES</button>
                <button class="antigos">MAIS ANTIGOS</button>
            </div>

            <!-- Grid de Vídeos -->
            <div id="recentes"></div>
            <div id="antigos"></div>
        </div>
    </section>
</main>

<!-- Scripts -->
<script>
    // Configurações globais para o scripts.js
    window.VETZ_CONFIG = {
        API_KEY: 'SUA_CHAVE_AQUI', // COLE SUA CHAVE DO YOUTUBE AQUI
        CANAIS: [
            'UCsKneoQQHq93LsJpfspj_6A',
            'UCTU-01IN0p5JXB7VxEzDdJg',
            'UCpfYQpjkTmxMPN1vUoTaAMw'
        ],
        MAX_PER_CANAL: 6
    };
</script>

<!-- Carregar Google API, jQuery e scripts.js na ordem correta -->
<script src="https://apis.google.com/js/api.js"></script>
<script src="/views/js/jquery-3.3.1.min.js"></script>
<script src="/views/js/scripts.js"></script>

<?php
$content = ob_get_clean();
include 'layout.php';
?>