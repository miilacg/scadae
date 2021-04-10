﻿<?php
	session_start();
?>

<!DOCTYPE HTML>
<html lang="pt-br">
	<head>
		<meta charset = "UTF-8">
        <meta name = "viewport" content = "width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@500&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Petrona&display=swap" rel="stylesheet">

        <link rel = "stylesheet" href = "https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity = "sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin = "anonymous">
        <link rel = "stylesheet" href = "login.css">
		<title>Questionário</title>
	</head>
	
	<body>		
        <div id="background">
            <img id="triangulos" src="./images/triangulos.png">   
            <img id="brasao" src="./images/brasao.svg">
        </div> 

        <form method="POST" action="tratamentologin.php">            
            <main>
                <img id="logo" src="./images/logo.svg" alt="logo scada-e">

                <div class="formulario">                        
                    <div class="form-group">
                        <label>CPF</label>
                        <input id="number" class="form-control" type="number" min="1" name="cpf" placeholder="Digite apenas os números" required>
                    </div>

                    <div class="form-group">
                        <label>Modalidade</label>
                        <select id="curso" class="form-control" name="curso"  required>
                            <option value="">Escolha a modalidade</option>
                            <option value="Tecnico">Técnico</option> 
                            <option value="Superior">Superior</option> 
                            <option value="Administrador">Gerente</option>
                        </select>
                    </div> 
                </div>                    
                <input id="botao" class="btn" type="submit" name="entrar" value="Entrar"/>
            </main>           
        </form>   
        

        <!-- Mensagens de erro -->
        <script src="https://cdn.trackjs.com/agent/v3/latest/t.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js" type="text/javascript"></script>

        <?php
            if (isset ($_SESSION['msg'])){ 
                if (($_SESSION['msg']) == "error"){ ?>
                    <script type="text/javascript">
                        Swal.fire({
                            title: "Acesso negado",
                            text: "CPF incorreto ou não cadastrado",
                            icon: 'error',    
                            buttonsStyling: false, 
                        })
                    </script>

                <?php  } else { 
                    if (($_SESSION['msg']) == "errorAdm") { ?>
                        <script type="text/javascript">
                            Swal.fire({
                                title: "Acesso negado",
                                text: "CPF ou senha incorretos ou não cadastrado",
                                icon: 'error', 
                                buttonsStyling: false,         
                            })
                        </script>
                    <?php  } else { ?>
                        <script type="text/javascript">   
                            Swal.fire({
                                title: "Você já respondeu a pesquisa",
                                text: "Deseja responder novamente?",
                                icon: 'warning', 
                                reverseButtons: true,
                                showCancelButton: true,
                                cancelButtonText: 'Não',   
                                showConfirmButton: true,
                                confirmButtonText: 'Sim',
                                buttonsStyling: false, 
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    <?php  if (($_SESSION['msg']) == "sup") { ?>
                                        window.location.href = "http://www.google.com.br";
                                    <?php  } ?>
                                } 
                            })
                        </script>
                    <?php }                     
                }

                unset ($_SESSION['msg']);
            }
        ?>
	</body>
</html>