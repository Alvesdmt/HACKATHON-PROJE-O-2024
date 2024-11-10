<script src="../..\public\js\nav.js"></script>

<link rel="stylesheet" href="../../public/css/sidebar.css"> 


<aside class="side-nav navbar" id="sidenav-main" style="align-content: flex-start;">
   
<br><br>
    <div class="collapse navbar-collapse w-auto h-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <span class="nav-subtitles">Trabalho</span>
            <li class="navItem">
                <a class="navLink active" href="../..\resources\viewsadm\admin_panel.php">
                    <img class="icon" src="../..\public\images\icons\layout.png" alt="Dashboard Icon" />
                    <span class="navLink-text ms-1">Solicitação</span>
                </a>
            </li>
         

            <li class="navItem">
                <a class="navLink" href="admin_dashboard.php">
                    <img class="icon" src="../..\public\images\icons\Relatorio.png" alt="Reports Icon" />
                    <span class="navLink-text ms-1">Requerimentos</span>
                </a>
            </li>
            <li class="navItem">
                <a class="navLink" href="chat_admin.php">
                    <img class="icon" src="../..\public\images\icons\config.png" alt="Webhooks Icon" />
                    <span class="navLink-text ms-1">Chat</span>
                </a>
            </li>
          
            
           
        </ul>

       

        <ul class="navbar-nav">
            <span class="nav-subtitles">Avançado</span>
           
          

         

            <li class="navItem">
                <a class="navLink" href="logoutadm.php">
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