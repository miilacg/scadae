<?php
    include 'vAdministrador.php';
    include '../acessobancosup.php';
?>

<!DOCTYPE HTML>
<html lang = "pt-br">
    <head>
        <meta charset = "utf-8">
        <meta name = "viewport" content = "initial-scale=1, user-scalable = no">
        
        <link rel = "stylesheet" href = "https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity = "sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> 
        <link rel = "stylesheet" href = "https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity = "sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin = "anonymous">  
        <link rel = "stylesheet" href = "../estilo.css">
        <link rel = "stylesheet" href = "graficos.css">

        <title>Questionario</title>
                
        <style>	
            h6{
                text-align: left;
            }
            #botao{
                width: 300px;
                height: 50px;
            }
        </style>
	</head>	

	<body>
        <br>
        <div class = "corpo">
            <div class = "card text-center">
                <div class = "card-header"> 
                    <br><h1>Respostas</h1><br>
                </div> 	
                
                <div class= "card-body" style = "padding-left: 3vw; padding-right: 3.5vw; padding-top: 2.5vw;">
                    <?php
                        $aspas = "\"";
                        $arquivoPizza = "graficoPizzaSuperior.txt";
                        $arquivoBarra = "graficoBarraSuperior.txt";
                        $filePizza = fopen($arquivoPizza, 'w');
                        $fileBarra = fopen($arquivoBarra, 'w');
                        fwrite($filePizza, "[{");
                        fwrite($fileBarra, "[{");

                        $quantidade = array();
                        $respostas = array();
                        $alternativa = array();
                        $string_Total = array();
                        
                        //$_POST["questao"] é o id da pergunta
                        for ($questao = 1; $questao < 64; $questao++) { 
                            $string_Total[$questao] = 0;          

                            //questões que não tem subquestoes
                            if ($questao < 21 || $questao == 22 || ($questao > 25 && $questao < 29) || $questao == 30 || ($questao > 31 && $questao < 42) || ($questao > 42 && $questao < 47) || ($questao > 52 && $questao < 57) || $questao == 60 || $questao == 61 || $questao == 63) {
                                $selecao = "SELECT * from(
                                                SELECT questao, opcao AS resposta, alternativa, 0 AS qtd 
                                                FROM alternativa NATURAL JOIN pergunta_has_alternativa NATURAL JOIN pergunta 
                                                WHERE id_perguntas = '$questao' AND id_alternativa 
                                                NOT IN (SELECT id_alternativa FROM resposta WHERE id_perguntas = '$questao') 
                                                GROUP BY resposta 
                                                UNION 
                                                SELECT questao, resposta, alternativa, count(*) AS qtd 
                                                FROM pergunta NATURAL JOIN resposta NATURAL JOIN alternativa 
                                                WHERE id_perguntas = '$questao' 
                                                GROUP BY resposta 
                                            )AS Resultado ORDER BY Resultado.resposta;";  
                                
                                $resultadoSelecao = mysqli_query($conn, $selecao);
                                $linha = mysqli_fetch_assoc($resultadoSelecao);
                                $total = mysqli_num_rows($resultadoSelecao);//calcula quantos dados foram retornados
                                
                                $selecaoMarcadas = "SELECT questao, resposta, alternativa, count(*) AS qtd 
                                                    FROM pergunta NATURAL JOIN resposta NATURAL JOIN alternativa 
                                                    WHERE id_perguntas = '$questao' 
                                                    GROUP BY resposta ORDER BY resposta.resposta ASC;";  
                    
                                $resultadoSelecaoMarcadas = mysqli_query($conn, $selecaoMarcadas);
                                $totalMarcadas = mysqli_num_rows($resultadoSelecaoMarcadas);//calcula quantos dados foram retornados
                                
                                $string_Total[$questao] = $totalMarcadas; 
                                        
                                ?>                            
                                <!--impressao da pergunta selecionada-->
                                <h5><?php echo $linha['questao'];?></h5><?php 
                                fwrite($filePizza, "\n".$aspas."Pergunta".$aspas.": ".$aspas.$linha['questao'].$aspas.",");
                                fwrite($filePizza, "\n".$aspas."Respondida".$aspas.": ".$aspas.$string_Total[$questao].$aspas.","); 
        
                                $cont = 0;
                                do {  
                                    //salvando em arrays os dados colhidos do banco
                                        $respostas[$cont] = $linha['resposta']; 
                                        $quantidade[$cont] = $linha['qtd'];
                                        $alternativa[$cont] = $linha['alternativa']; 
                                        $cont ++;
                                }while($linha = mysqli_fetch_assoc($resultadoSelecao));
                                
                                fwrite($filePizza, "\n".$aspas."Resposta".$aspas.": [");
                                for ($i = 0; $i < $total; $i++){
                                    fwrite($filePizza, $aspas.$respostas[$i].$aspas);
                                    if ($i < $total - 1){
                                        fwrite($filePizza, ",");
                                    }
                                }
                                fwrite($filePizza, "],");

                                fwrite($filePizza, "\n".$aspas."Alternativa".$aspas.": [");
                                for ($i = 0; $i < $total; $i++){
                                    fwrite($filePizza, $aspas.$alternativa[$i].$aspas);
                                    if ($i < $total - 1){
                                        fwrite($filePizza, ",");
                                    }
                                }
                                fwrite($filePizza, "],");

                                fwrite($filePizza, "\n".$aspas."Quantidade".$aspas.": [");
                                for ($i = 0; $i < $total; $i++){
                                    fwrite($filePizza, $aspas.$quantidade[$i].$aspas);
                                    if ($i < $total - 1){
                                        fwrite($filePizza, ",");
                                    }
                                }
                                fwrite($filePizza, "]\n}");
                                

                                if ($totalMarcadas == 0){
                                    echo "</br>Essa pergunta não teve respostas.</br></br>";
                                }else{
                                    ?>
                                        <!-- espaco em que o grafico será colocado !-->
                                        <canvas class = "grafico" id = "grafico<?php echo $questao ?>"></canvas>
                                    <?php
                                }
                                
                                if ($questao < 63){
                                    fwrite($filePizza, ",\n{"); 
                                }
                            }else{
                                if ($questao != 21 && $questao != 29 && $questao != 31 && $questao != 64){
                                    $selecaoConsulta = "SELECT questao, id_perguntas FROM `pergunta` NATURAL JOIN `subpergunta_has_pergunta` WHERE id_perguntas = '$questao'";
                                    $resultadoConsulta = mysqli_query($conn, $selecaoConsulta);
                                    $linhaConsulta = mysqli_fetch_assoc($resultadoConsulta);

                                    ?><h5><?php echo $linhaConsulta['questao'];?></h5><?php

                                    if ($questao > 22 && $questao < 26){
                                        $contConsulta = 1;
                                    }

                                    if ($questao == 42){
                                        $contConsulta = 5;
                                    }

                                    if ($questao == 47){
                                        $contConsulta = 10;
                                    }

                                    if ($questao == 48){
                                        $contConsulta = 17;
                                    }
                                    
                                    if ($questao == 49){
                                        $contConsulta = 26;
                                    }
                                    
                                    if ($questao == 50){
                                        $contConsulta = 45;
                                    }

                                    if ($questao == 51){
                                        $contConsulta = 71;
                                    }

                                    if ($questao == 52){
                                        $contConsulta = 82;
                                    }

                                    if ($questao == 57){
                                        $contConsulta = 87;
                                    }

                                    if ($questao == 58){
                                        $contConsulta = 93;
                                    }

                                    if ($questao == 59){
                                        $contConsulta = 99;
                                    }

                                    if ($questao == 62){
                                        $contConsulta = 103;
                                    }

                                    do { 
                                        $selecaoSubPergunta = "SELECT * FROM(
                                                                    SELECT questao, opcao AS resposta, alternativa, subquestao, id_subpergunta, id_perguntas, 0 AS qtd 
                                                                    FROM alternativa NATURAL JOIN subpergunta_has_alternativa NATURAL JOIN subpergunta NATURAL JOIN subpergunta_has_pergunta NATURAL JOIN pergunta
                                                                    WHERE id_perguntas = '$questao' AND id_subpergunta = '$contConsulta' AND id_alternativa 
                                                                    NOT IN (SELECT id_alternativa FROM resposta WHERE id_perguntas = '$questao' AND id_subpergunta = '$contConsulta')    
                                                                    GROUP BY resposta    
                                                                    UNION
                                                                    SELECT questao, resposta, alternativa, subquestao, id_subpergunta, id_perguntas, count(*) AS qtd 
                                                                    FROM pergunta NATURAL JOIN resposta NATURAL JOIN alternativa NATURAL JOIN subpergunta 
                                                                    WHERE id_perguntas = '$questao' AND id_subpergunta = '$contConsulta'
                                                                    GROUP BY resposta                                 
                                                                )AS Resultado ORDER BY Resultado.resposta;"; 

                                        $resultadoSubPergunta = mysqli_query($conn, $selecaoSubPergunta);
                                        $linhaSubPergunta = mysqli_fetch_assoc($resultadoSubPergunta);
                                        $totalSubPergunta = mysqli_num_rows($resultadoSubPergunta);//calcula quantos dados foram retornados
                                                                               
                            
                                        fwrite($fileBarra, "\n".$aspas."Pergunta".$aspas.": ".$aspas.$linhaSubPergunta['questao'].$aspas.",");
                                        fwrite($fileBarra, "\n".$aspas."SubPergunta".$aspas.": ".$aspas.$linhaSubPergunta['subquestao'].$aspas.",");
                                        fwrite($fileBarra, "\n".$aspas."Id_perg".$aspas.": ".$aspas.$linhaSubPergunta['id_perguntas'].$aspas.","); 
                                        fwrite($fileBarra, "\n".$aspas."Id_sub".$aspas.": ".$aspas.$linhaSubPergunta['id_subpergunta'].$aspas.",");  
                                        
                                        $cont = 0;
                                        do {  
                                            //salvando em arrays os dados colhidos do banco
                                                $respostasSubPergunta[$cont] = $linhaSubPergunta['resposta']; 
                                                $quantidadeSubPergunta[$cont] = $linhaSubPergunta['qtd'];
                                                $alternativaSubPergunta[$cont] = $linhaSubPergunta['alternativa']; 
                                                $cont ++;
                                        }while($linhaSubPergunta = mysqli_fetch_assoc($resultadoSubPergunta));        
                                        
                                        fwrite($fileBarra, "\n".$aspas."Resposta".$aspas.": [");
                                        for ($i = 0; $i < $totalSubPergunta; $i++){
                                            fwrite($fileBarra, $aspas.$respostasSubPergunta[$i].$aspas);
                                            if ($i < $totalSubPergunta - 1){
                                                fwrite($fileBarra, ",");
                                            }
                                        }
                                        fwrite($fileBarra, "],");

                                        fwrite($fileBarra, "\n".$aspas."Alternativa".$aspas.": [");
                                        for ($i = 0; $i < $totalSubPergunta; $i++){
                                            fwrite($fileBarra, $aspas.$alternativaSubPergunta[$i].$aspas);
                                            if ($i < $totalSubPergunta - 1){
                                                fwrite($fileBarra, ",");
                                            }
                                        }
                                        fwrite($fileBarra, "],");

                                        fwrite($fileBarra, "\n".$aspas."Quantidade".$aspas.": [");
                                        for ($i = 0; $i < $totalSubPergunta; $i++){
                                            fwrite($fileBarra, $aspas.$quantidadeSubPergunta[$i].$aspas);
                                            if ($i < $totalSubPergunta - 1){
                                                fwrite($fileBarra, ",");
                                            }
                                        }
                                        fwrite($fileBarra, "]\n}");

                                        if ($questao < 62 || ($questao == 62 && $contConsulta < 107 )){
                                            fwrite($fileBarra, ",\n{"); 
                                        }

                                        $indice = $questao.$contConsulta;

                                        ?>
                                            <canvas class = "grafico" id = "grafico<?php echo $indice;?>"></canvas>
                                        <?php

                                        if ($questao == 49){
                                            if ($contConsulta == 32){
                                                $contConsulta = 34;
                                            }else{
                                                if ($contConsulta == 34){
                                                    $contConsulta = 36;
                                                }else{
                                                    if ($contConsulta == 44){
                                                        $contConsulta = 57;
                                                    }else{
                                                        if ($contConsulta == 57){
                                                            $contConsulta = 65;
                                                        }else{
                                                            if ($contConsulta == 65){
                                                                $contConsulta = 67;
                                                            }else{
                                                                if ($contConsulta == 67){
                                                                    $contConsulta = 109;
                                                                }else{
                                                                    $contConsulta++;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }else{
                                            if ($questao == 50){
                                                if ($contConsulta == 56){
                                                    $contConsulta = 58;
                                                }else{
                                                    if ($contConsulta == 64){
                                                        $contConsulta = 66;
                                                    }else{
                                                        if ($contConsulta == 66){
                                                            $contConsulta = 68;
                                                        }else{
                                                            $contConsulta++;
                                                        }
                                                    }
                                                }
                                            }else{
                                                if ($questao == 52){
                                                    if ($contConsulta == 84){
                                                        $contConsulta = 86;
                                                    }else{
                                                        if ($contConsulta == 86){
                                                            $contConsulta = 108;
                                                        }else{
                                                            $contConsulta++;
                                                        }
                                                    }
                                                }else{
                                                    if ($questao == 58 && $contConsulta == 96){
                                                        $contConsulta = 98;
                                                    }else{
                                                        if ($questao == 59 && $contConsulta == 102){
                                                            $contConsulta = 136;
                                                        }else{
                                                            $contConsulta++;
                                                        }
                                                    }                                                    
                                                }                                                
                                            }
                                        } 

                                    }while($linhaConsulta = mysqli_fetch_assoc($resultadoConsulta));                                    
                                }else{
                                    $selecao = "SELECT questao, resposta FROM pergunta NATURAL JOIN resposta where id_perguntas = '$questao'";
                                    $resultadoSelecao = mysqli_query($conn, $selecao);
                                    $linha = mysqli_fetch_assoc($resultadoSelecao);
                                    $total = mysqli_num_rows($resultadoSelecao); //calcula quantos dados foram retornados  

                                    ?>
                                    <!--impressao da pergunta selecionada-->
                                    <h5><?php echo $linha['questao'];?></h5><?php

                                    if ($total == 0){
                                        $selecaoN = "SELECT questao FROM pergunta where id_perguntas = '$questao';";
                                        $resultadoSelecaoN = mysqli_query($conn, $selecaoN);
                                        $linhaN = mysqli_fetch_assoc($resultadoSelecaoN);
                                        
                                        ?><h5><?php echo $linhaN['questao'];?></h5>
                                        <h6>Essa pergunta não teve respostas.<h6><?php
                                    }else{
                                        if($total > 0){       
                                            do {  
                                                ?><div id = "resposta">
                                                    <h6> * <?php echo $linha['resposta'];?>.<h6>
                                                </div><?php  
                                            }while($linha = mysqli_fetch_assoc($resultadoSelecao));
                                            echo "</br>";
                                        }
                                    } 
                                }
                            }                                                      
                        }
                        fwrite($filePizza, "]");
                        fwrite($fileBarra, "]");
                    ?>                 
                </div>   
                
                <div class = "botao">
                    <a href = "selecaoopcaosup.php"><input id = "botao" type = "button" class= "btn btn-primary" value = "Voltar"/></a>
                </div><br>

                <div class= "card-footer">  </div>

            </div>    
        </div>
        <script src = "https://code.jquery.com/jquery-3.3.1.min.js" ></script>
        <script src= "https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js" ></script>
        <script src= "https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js" ></script>
	</body>
</html>

<script>
    //Cor do grafico
    function randomRgbPizza() {
        var cor = ["#000080", "#0000CD", "#0000FF", "#00FA9A", "#008B8B", "#00FFFF", "#00BFFF", "#00CED1", "#006400", "#008000",    
                   "#191970", "#1E90FF", "#20B2AA", "#228B22", "#32CD32", "#3CB371", "#48D1CC", "#40E0D0", "#4682B4", "#4169E1",  
                   "#483D8B", "#6495ED", "#66CDAA", "#66CDAA", "#7FFFD4", "#7FFF00", "#87CEFA", "#E0FFFF", "#B0E0E6", "#98FB98"];
        var posicao = Math.floor(Math.random() * 29 + 1);
        return cor[posicao];
    }

    function randomRgbBarra() {
        var cor = ["#00FA9A", "#00CED1", "#20B2AA", "#008B8B", "#00FFFF", "#00BFFF",   
                   "#1E90FF", "#4169E1", "#6495ED", "#66CDAA", "#7FFFD4", "#87CEFA"];
        var posicao = Math.floor(Math.random() * 11 + 1);
        return cor[posicao];
    }

    //Grafico de barra
    var urlBarra = 'http://localhost/questionario/Administrador/graficoBarraSuperior.txt';
    var xhttpBarra = new XMLHttpRequest();
    xhttpBarra.open("GET", urlBarra);
    xhttpBarra.send();

    xhttpBarra.onload = function(){
        var dadosBarra = JSON.parse(xhttpBarra.responseText);  
        createChartBarra(dadosBarra);
    } 

    function createChartBarra(dadosBarra){         
        var dadosBarra = dadosBarra;
        var j, i, k = 0, color = [], c;

        for (j in dadosBarra) {                        
            for (i = 1; i <= 64; i++) {                            
                if ((i > 22 && i < 26) || i == 42 || (i > 46 && i < 53) || (i > 56 && i < 60) || (i == 62)){
                
                    while (dadosBarra[k].Id_perg == i){
                        var indice = dadosBarra[k].Id_perg.concat(dadosBarra[k].Id_sub);
                        const elementBarra = document.getElementById(`grafico${indice}`);

                        for(c = 0; c < 30; c++){
                            color[c] = randomRgbBarra();
                        }

                        new Chart(elementBarra, {
                            type: 'bar',
                            data: {
                                labels: dadosBarra[k].Alternativa,
                                datasets: [
                                    {
                                        label: dadosBarra[k].SubPergunta,
                                        data: dadosBarra[k].Quantidade,
                                        backgroundColor: color
                                    }
                                ]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                },
                                legend: { 
                                    labels: { 
                                        boxWidth: 0,
                                        fontSize: 18 
                                    } 
                                } 
                            }
                        });  
                        k++;
                    }                                    
                }
            }
        }
    }


    //Grafico de pizza
    var urlPizza = 'http://localhost/questionario/Administrador/graficoPizzaSuperior.txt';
    var xhttpPizza = new XMLHttpRequest();
    xhttpPizza.open("GET", urlPizza);
    xhttpPizza.send();

    xhttpPizza.onload = function(){
        var dadosPizza = JSON.parse(xhttpPizza.responseText);  
        createChartPizza(dadosPizza);
    } 

    function createChartPizza(jsonObj){         
        var dadosPizza = jsonObj;
        var j, i, k = 0, color = [], c;

        for (j in dadosPizza) {                        
            for (i = 1; i < 64; i++) {                            
                if (i < 21 || i == 22 || (i > 25 && i < 29) || i == 30 || (i > 31 && i < 42) || (i > 42 && i < 47) || (i > 52 && i < 57) || i == 60 || i == 61 || i == 63){
                    if (dadosPizza[k].Respondida != 0){
                        const element = document.getElementById(`grafico${i}`);

                        for(c = 0; c < 30; c++){
                            color[c] = randomRgbPizza();
                        }

                        new Chart(element, {
                            type: "pie",
                            data: {
                                labels: dadosPizza[k].Alternativa,
                                datasets: [
                                    {
                                        data: dadosPizza[k].Quantidade,
                                        backgroundColor: color
                                    }
                                ]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                },
                                legend: { 
                                    labels: { 
                                        boxWidth: 45,
                                        fontSize: 14 
                                    } 
                                } 
                            }
                        });  
                    }k++;                                    
                }
            }
        }
    } 
</script>