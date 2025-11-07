
<?php
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
$title = "VetZ - Vacinação Geral";
ob_start();
?>
<style>
    .section06 {
        padding: 40px 0;
    }
    .info-cards {
        display: flex;
        gap: 24px;
        flex-wrap: wrap;
        justify-content: center;
        margin-bottom: 32px;
    }
    .info-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(44,122,122,0.10);
    padding: 8px 8px 12px 8px;
    text-align: center;
    flex: 1 1 160px;
    min-width: 140px;
    max-width: 180px;
    margin-bottom: 12px;
    min-height: 70px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    }
    .sec006titleh1 {
        color: #038654;
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 18px;
        text-align: center;
    }
    .sec06ph1 {
        font-size: 1.2rem;
        color: #444;
        text-align: center;
        margin-bottom: 32px;
    }
    .sec06infosh2 {
        color: #2d7a7a;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 8px;
    }
    .sec06infos {
        color: #555;
        font-size: 1rem;
        margin-bottom: 0;
    }
    .vacina {
        text-align: center;
        margin-top: 32px;
    }
    .sec06phvac {
        font-size: 1.1rem;
        color: #026d47;
        margin-bottom: 12px;
    }
    .carteirinha {
        background: #04c97b;
        color: #fff;
        font-size: 1.1rem;
        padding: 12px 32px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(44,122,122,0.12);
        transition: background 0.3s;
        display: inline-block;
    }
    .carteirinha:hover {
        background: #038654;
        color: #fff;
    }
    @media (max-width: 900px) {
        .info-cards {
            gap: 16px;
        }
        .info-card {
            min-width: 160px;
            max-width: 220px;
            padding: 16px 8px;
        }
        .sec006titleh1 {
            font-size: 1.5rem;
        }
    }
    @media (max-width: 600px) {
        .section06 {
            padding: 24px 0;
        }
        .info-cards {
            flex-direction: column;
            gap: 12px;
        }
        .info-card {
            min-width: 100%;
            max-width: 100%;
            margin-bottom: 8px;
        }
        .sec006titleh1 {
            font-size: 1.2rem;
        }
        .sec06ph1 {
            font-size: 1rem;
        }
        .sec06phvac {
            font-size: 1rem;
        }
        .carteirinha {
            font-size: 1rem;
            padding: 10px 18px;
        }
    }
</style>

<section class="section06" id="sec06" style="padding-top: 120px;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="sec006titleh1">Página de Vacinações!</h1>
                <p class="sec06ph1">Nesta página você poderá acessar as vacinações do seu pet.</p>
                <div class="info-cards">
                        <div class="info-card">
                            <i class="fas fa-syringe fa-2x text-success mb-2"></i>
                            <h2 class="sec06infosh2">Controle de Vacinação</h2>
                            <p class="sec06infos">Histórico completo de vacinas com datas, doses e certificados.</p>
                        </div>
                        <div class="info-card">
                            <i class="fas fa-calendar-alt fa-2x text-success mb-2"></i>
                            <h2 class="sec06infosh2">Lembretes Automáticos</h2>
                            <p class="sec06infos">Notificações por e-mail e app para nunca esquecer uma dose.</p>
                        </div>
                        <div class="info-card">
                            <i class="fas fa-paw fa-2x text-success mb-2"></i>
                            <h2 class="sec06infosh2">Multi-Pets</h2>
                            <p class="sec06infos">Gerencie cães, gatos e outros animais em uma única conta.</p>
                        </div>
                </div>
                <div class="vacina">
                    <p class="sec06phvac">Confira as vacinas indicadas para proteger seu gato contra doenças comuns.</p>
                    <a href="vacinacao_pet.php" class="carteirinha">Carteirinha Digital</a>
                </div>
                    <div class="container07"></div>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
