<script src="../..\public\js\nav.js"></script>

<link rel="stylesheet" href="../../public/css/sidebar.css"> 


<aside class="side-nav navbar" id="sidenav-main" style="align-content: flex-start;">
   
<br><br>
    <div class="collapse navbar-collapse w-auto h-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <span class="nav-subtitles">Trabalho</span>
            <li class="navItem">
                <a class="navLink active" href="../..\resources\views\Solicitação.php">
                    <img class="icon" src="../..\public\images\icons\layout.png" alt="Dashboard Icon" />
                    <span class="navLink-text ms-1">Solicitação Documentos</span>
                </a>
            </li>
            <li class="navItem">
                <a class="navLink" href="chat_user.php">
                    <img class="icon" src="../..\public\images\icons\produtos.png" alt="Products Icon" />
                    <span class="navLink-text ms-1">Chat CIIA</span>
                </a>
            </li>

            <li class="navItem">
                <a class="navLink" href="user_dashboard.php">
                    <img class="icon" src="../..\public\images\icons\Relatorio.png" alt="Reports Icon" />
                    <span class="navLink-text ms-1">SAP</span>
                </a>
            </li>
           
          
           
            
           
        </ul>

       

        <ul class="navbar-nav">
            <span class="nav-subtitles">Avançado</span>
           
         

            <li class="navItem">
                <a class="navLink" href="perfil_usuario.php">
                    <img class="icon" src="../..\public\images\icons\user.png" alt="My Account Icon" />
                    <span class="navLink-text ms-1">Minha conta</span>
                </a>
            </li>

            <li class="navItem">
                <a class="navLink" href="logout.php">
                    <img class="icon" src="../..\public\images\icons\exit.png" alt="Logout Icon" />
                    <span class="navLink-text ms-1">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</aside>


<script>
    document.getElementById('iconSidenav').addEventListener('click', function() {
        const sidebar = document.getElementById('sidenav-main');
        sidebar.classList.toggle('hidden-sidebar');
    });
</script>