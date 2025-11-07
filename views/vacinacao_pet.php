<?php
include_once __DIR__ . '/../config/database_site.php';

// Consulta para verificar se há algum usuário no banco
$temUsuario = false;
if (isset($conn)) {
    $sql = "SELECT COUNT(*) AS total FROM usuarios";
    $result = $conn->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        $temUsuario = (!empty($row) && isset($row['total']) && $row['total'] > 0);
    }
}
?>

        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>VetZ</title>

            <!-- Bootstrap e CSS -->
            <link href="css/bootstrap.min.css" rel="stylesheet">
            <link href="css/style.css" rel="stylesheet" media="screen and (color)">
            <link href="css/all.min.css" rel="stylesheet">
            <link href="images/logo_vetz.svg" rel="shortcut icon">
        </head>

        <body>
                <?php include 'navbar.php'; ?>
                            </span>
                        </button>

                        <div class="navbar-collapse collapse" id="navbarCollapse">
                            <ul class="navbar-nav ml-auto left-menu">
                                <li><a href="/projeto/vetz/homepage">HOME PAGE</a></li>
                                <li><a href="/projeto/vetz/sobre-nos">SOBRE NÓS</a></li>
                                <li><a href="/projeto/vetz/curiosidades">CURIOSIDADES</a></li>
                                <li><a href="/projeto/vetz/recomendacoes">RECOMENDAÇÕES</a></li>
                                <li><a href="/projeto/vetz/cadastrar-vacina">VACINAÇÃO</a></li>

                                <li>
                                    <a class="btn btn-menu" href="<?php echo $temUsuario ? '/projeto/vetz/perfil' : '/projeto/vetz/cadastrarForm'; ?>" role="button">
                                        <img class="imgperfil" src="/projeto/vetz/views/images/perfil" alt="Perfil">
                                        <?php echo $temUsuario ? 'PERFIL' : 'CADASTRO'; ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </nav>
        </header>
        <!--End Header-->


        <!-- --------------- CONTEÚDO DA PÁGINA ----------------->

        <!-- Begin section 07 -->
            <section class="section07" id="sec07">
                <div class="container07">
                    <div class="header-info">
                        <div class="pet-photo">🐕</div>
                        <h1 class="nome-pet">Luck</h1>
                        <p>Tutor: Marcela Sanches</p>
                        <div class="pet-details">
                            <div class="pet-detail-item">
                                <span class="pet-detail-label">Espécie</span>
                                <span class="pet-detail-value">Cachorro</span>
                            </div>
                            <div class="pet-detail-item">
                                <span class="pet-detail-label">Raça</span>
                                <span class="pet-detail-value">Vira-Lata</span>
                            </div>
                            <div class="pet-detail-item">
                                <span class="pet-detail-label">Idade</span>
                                <span class="pet-detail-value">6 anos</span>
                            </div>
                            <div class="pet-detail-item">
                                <span class="pet-detail-label">Data de Nascimento</span>
                                <span class="pet-detail-value">17/10/2019</span>
                            </div>
                        </div>
                    </div>

                    <div class="vaccination-card">
                        <h2>
                            Carteirinha de Vacinação Digital
                            <button class="edit-btn" onclick="openEditModal()">
                                ✏️ Editar Vacinas
                            </button>
                        </h2>
                        <div class="age-alert">
                            <strong>⏰ Atenção:</strong> Seu pet está com 6 anos de idade. Confira as vacinas recomendadas para esta fase!
                        </div>
                        <table class="vaccine-table">
                            <thead>
                    <tr>
                        <th>Vacinação</th>
                        <th>Status</th>
                        <th>Data Aplicação</th>
                        <th>Próxima Dose</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="vaccine-name">Polivalente V8/V10</div>
                            <div class="vaccine-subtitle">1ª Dose - A partir de 6 semanas</div>
                        </td>
                        <td>
                            <span class="status-badge status-done">
                                <span class="check-icon">✓</span>
                                Aplicada
                            </span>
                        </td>
                        <td>05/10/2025</td>
                        <td>26/10/2025</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="vaccine-name">Polivalente V8/V10</div>
                            <div class="vaccine-subtitle">2ª Dose - 9 semanas</div>
                        </td>
                        <td>
                            <span class="status-badge status-pending">
                                Próxima
                            </span>
                        </td>
                        <td>-</td>
                        <td>26/10/2025</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="vaccine-name">Polivalente V8/V10</div>
                            <div class="vaccine-subtitle">3ª Dose - 12 semanas</div>
                        </td>
                        <td>
                            <span class="status-badge status-due">
                                Próxima
                            </span>
                        </td>
                        <td>-</td>
                        <td>16/11/2025</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="vaccine-name">Gripe (Tosse dos Canis)</div>
                            <div class="vaccine-subtitle">1ª Dose - A partir de 6 semanas</div>
                        </td>
                        <td>
                            <span class="status-badge status-done">
                                <span class="check-icon">✓</span>
                                Aplicada
                            </span>
                        </td>
                        <td>05/10/2025</td>
                        <td>26/10/2025</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="vaccine-name">Gripe (Tosse dos Canis)</div>
                            <div class="vaccine-subtitle">2ª Dose - 9 semanas</div>
                        </td>
                        <td>
                            <span class="status-badge status-pending">
                                Agendada
                            </span>
                        </td>
                        <td>-</td>
                        <td>26/10/2025</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="vaccine-name">Giárdia</div>
                            <div class="vaccine-subtitle">1ª Dose - A partir de 12 semanas</div>
                        </td>
                        <td>
                            <span class="status-badge status-due">
                                Próxima
                            </span>
                        </td>
                        <td>-</td>
                        <td>16/11/2025</td>
                    </tr>
                </tbody>
            </table>
        </div>

    <!-- Modal de Edição de Vacinas -->
    <div id="editVaccineModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>✏️ Editar Vacinações</h3>
                <button class="close-btn" onclick="closeEditModal()">×</button>
            </div>
            <form id="vaccineForm" onsubmit="saveVaccines(event)">
                <div class="checkbox-group">
                    <input type="checkbox" id="vac1" name="vaccine" value="polivalente1">
                    <label for="vac1">
                        <strong>Polivalente V8/V10 - 1ª Dose</strong><br>
                        <small>A partir de 6 semanas</small>
                    </label>
                    <input type="date" class="vaccine-date-input" id="date1">
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="vac2" name="vaccine" value="polivalente2">
                    <label for="vac2">
                        <strong>Polivalente V8/V10 - 2ª Dose</strong><br>
                        <small>9 semanas</small>
                    </label>
                    <input type="date" class="vaccine-date-input" id="date2">
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="vac3" name="vaccine" value="polivalente3">
                    <label for="vac3">
                        <strong>Polivalente V8/V10 - 3ª Dose</strong><br>
                        <small>12 semanas</small>
                    </label>
                    <input type="date" class="vaccine-date-input" id="date3">
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="vac4" name="vaccine" value="gripe1">
                    <label for="vac4">
                        <strong>Gripe (Tosse dos Canis) - 1ª Dose</strong><br>
                        <small>A partir de 6 semanas</small>
                    </label>
                    <input type="date" class="vaccine-date-input" id="date4">
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="vac5" name="vaccine" value="gripe2">
                    <label for="vac5">
                        <strong>Gripe (Tosse dos Canis) - 2ª Dose</strong><br>
                        <small>9 semanas</small>
                    </label>
                    <input type="date" class="vaccine-date-input" id="date5">
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="vac6" name="vaccine" value="giardia">
                    <label for="vac6">
                        <strong>Giárdia - 1ª Dose</strong><br>
                        <small>A partir de 12 semanas</small>
                    </label>
                    <input type="date" class="vaccine-date-input" id="date6">
                </div>

                <button type="submit" class="save-btn">Salvar Alterações</button>
            </form>
        </div>
    </div>

            </div>
        </section>
        <!-- End Section 07 -->

        <!-- Begin footer-->
        <div class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <p class="footerp1">
                            Todos os direitos reservados <span id="footer-year"></span> © - VetZ </p>
                    </div>

                    <!-- <div class="col-md-1">
                        <p class="instagram">
                            <a><img href="#!" src="images/instagram.svg"></a>
                    </div>
                    <div class="col-md-1">
                        <p class="email">
                            <a><img href="#!" src="images/email.svg"></a>
                    </div> -->
                </div>
            </div>
        </div>
        <!--End footer-->


        <!-- Load JS =============================-->
        <script src="js/jquery-3.3.1.min.js"></script>
        <script src="js/jquery.scrollTo-min.js"></script>
        <script src="js/jquery.nav.js"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>