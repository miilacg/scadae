<?php
    session_start();

    $cpf = $_SESSION['cpf'];
    $curso = $_SESSION['curso'];

    if ($curso == 'Administrador'){
        $valor = isset($_SESSION['cpf']) ? 's' : 'n';

        if ($valor == 'n'){
            header("Location: index.php");
        }
    }else{
       header("Location: index.php"); 
    } 

    include 'acessobancotec.php';
?>

<!DOCTYPE HTML>
<html lang = "pt-br">
	<meta charset = "utf-8">
    <meta name = "viewport" content = "initial-scale=1, user-scalable = no">

    <link rel = "stylesheet" href = "https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity = "sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> 
    <link rel = "stylesheet" href = "https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity = "sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin = "anonymous">  
    <link rel = "stylesheet" href = "estilo.css">
    <link rel = "stylesheet" href = "graficos.css">

    <title>Questionario</title>

	<head>         
		<style>	
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
                    <br><h1>Gráficos</h1><br>
                </div> 	
                
                <div class= "card-body" style = "padding-left: 3vw; padding-right: 3.5vw; padding-top: 2.5vw;">
                    <?php
                        $aspas = "\"";
                        $arquivoPizza = "graficoPizzaTecnico.txt";
                        $arquivoBarra = "graficoBarraTecnico.txt";
                        $filePizza = fopen($arquivoPizza, 'w');
                        $fileBarra = fopen($arquivoBarra, 'w');
                        fwrite($filePizza, "[{");
                        fwrite($fileBarra, "[{");

                        $quantidade = array();
                        $respostas = array();
                        $alternativa = array();
                        $string_Total = array();
                        
                        //$_POST["questao"] é o id da pergunta
                        for ($questao = 1; $questao < 57; $questao++) { 
                            $string_Total[$questao] = 0;          

                            //questões que não tem subquestoes
                            if ($questao < 13 || ($questao > 15 && $questao < 19) || $questao == 20 || ($questao > 21 && $questao < 33) || ($questao > 33 && $questao < 38) || ($questao > 43 && $questao < 48) || ($questao > 50 && $questao < 55) || $questao == 56) {
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
                                        
                                $cont = 0;
                                do {  
                                    //salvando em arrays os dados colhidos do banco
                                        $respostas[$cont] = $linha['resposta']; 
                                        $quantidade[$cont] = $linha['qtd'];
                                        $alternativa[$cont] = $linha['alternativa']; 
                                        $cont ++;
                                }while($linha = mysqli_fetch_assoc($resultadoSelecao));

                                //convertendo o array para uma string utilizada no JS
                                //os valores são separados por virgula                               
                                $string_total = implode(',', $string_Total);
                                
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
                                
                                if ($questao < 56){
                                    fwrite($filePizza, ",\n{"); 
                                }
                            }else{
                                if ($questao != 19 && $questao != 21 && $questao != 57){
                                    $selecaoConsulta = "SELECT questao, id_perguntas FROM `pergunta` NATURAL JOIN `subpergunta_has_pergunta` WHERE id_perguntas = '$questao'";
                                    $resultadoConsulta = mysqli_query($conn, $selecaoConsulta);
                                    $linhaConsulta = mysqli_fetch_assoc($resultadoConsulta);

                                    ?><h5><?php echo $linhaConsulta['questao'];?></h5><?php

                                    if ($questao > 12 && $questao < 16){
                                        $contConsulta = 1;
                                    }

                                    if ($questao == 33){
                                        $contConsulta = 5;
                                    }

                                    if ($questao == 38){
                                        $contConsulta = 10;
                                    }
                                    
                                    if ($questao == 39){
                                        $contConsulta = 17;
                                    }
                                    
                                    if ($questao == 40){
                                        $contConsulta = 26;
                                    }

                                    if ($questao == 41){
                                        $contConsulta = 45;
                                    }

                                    if ($questao == 42){
                                        $contConsulta = 71;
                                    }

                                    if ($questao == 43){
                                        $contConsulta = 82;
                                    }

                                    if ($questao == 48){
                                        $contConsulta = 87;
                                    }

                                    if ($questao == 50){
                                        $contConsulta = 99;
                                    }

                                    if ($questao == 55){
                                        $contConsulta = 103;
                                    }

                                    do { 
                                        $selecaoSubPergunta = "SELECT * from(
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

                                        if ($questao < 55 || ($questao == 55 && $contConsulta < 107 )){
                                            fwrite($fileBarra, ",\n{"); 
                                        }

                                        $indice = $questao.$contConsulta;

                                        ?><canvas class = "grafico" id = "grafico<?php echo $indice;?>"></canvas><?php

                                        if ($questao == 40){
                                            if ($contConsulta == 38){
                                                $contConsulta = 40;
                                            }else{
                                                $contConsulta++;
                                            }
                                        }else{
                                            if ($questao == 41){
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
                                                if ($questao == 43){
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
                                                    $contConsulta++;
                                                }                                          
                                            }
                                        } 

                                    }while($linhaConsulta = mysqli_fetch_assoc($resultadoConsulta));                                    
                                }
                            }                                                      
                        }
                        fwrite($filePizza, "]");
                        fwrite($fileBarra, "]");
                    ?>                 
                </div>   
                
                <div class = "botao">
                    <input id = "botao" type = "button" class= "btn btn-primary" value = "Voltar" onClick = "voltar();"/>
                </div><br>

                <div class= "card-footer">  </div>

            </div>    
        </div>
        <script src = "https://code.jquery.com/jquery-3.3.1.min.js" ></script>
        <script src= "https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js" ></script>
        <script src= "https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js" ></script>
	</body>
</html>

<script language = javascript type = "text/javascript"> 
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

    var urlPizza = 'http://localhost/questionario/graficoPizzaTecnico.txt';
    var xhttpPizza = new XMLHttpRequest();
    xhttpPizza.open("GET", urlPizza);
    xhttpPizza.send();

    xhttpPizza.onload = function(){
        var dadosPizza = JSON.parse(xhttpPizza.responseText);  
        createChartPizza(dadosPizza);
    } 

    var urlBarra = 'http://localhost/questionario/graficoBarraTecnico.txt';
    var xhttpBarra = new XMLHttpRequest();
    xhttpBarra.open("GET", urlBarra);
    xhttpBarra.send();

    xhttpBarra.onload = function(){
        var dadosBarra = JSON.parse(xhttpBarra.responseText);  
        createChartBarra(dadosBarra);
    } 

    function createChartPizza(jsonObj){         
        var dadosPizza = jsonObj;
        var string_total = "<?php echo $string_total?>";
        var total = string_total.split(',');
        var j, i, k = 0, color = [], c;

        for (j in dadosPizza) {                        
            for (i = 1; i < 57; i++) {                            
                if (i < 13 || (i > 15 && i < 19) || i == 20 || (i > 21 && i < 33) || (i > 33 && i < 38) || (i > 43 && i < 48) || (i > 50 && i < 55) || i == 56){
                    if (total[i-1] != 0){
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
                            }
                        });  
                    }k++;                                    
                }
            }
        }
    } 

    function createChartBarra(dadosBarra){         
        var dadosBarra = dadosBarra;
        var j, i, k = 0, color = [], c;

        for (j in dadosBarra) {                        
            for (i = 1; i <= 57; i++) {                            
                if (i != 19 && i != 21 && i != 57){
                   
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
                                }
                            }
                        });  
                        k++;
                    }                                    
                }
            }
        }
    }
    
    function voltar(){
        window.location.assign ("selecaoopcaotec.php");
    }
</script>