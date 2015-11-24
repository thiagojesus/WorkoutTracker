<?php
    require_once 'Database.php';
    session_start();
    error_reporting (E_ALL ^ E_NOTICE);
       
	$mensagem = '';
	$consulta = '';
	$result = '';
	$error = '';

	//conecta com o BD
    $inst = Database::getInstance();
    $con = $inst->getConnection();
    
	if($con){
        //query de busca no banco
        $consulta = "SELECT peso, altura FROM usuario;";
        //execucao da query
        $result = pg_query($con, $consulta) or die("Cannot execute query: $consulta\n"); 
        if (pg_num_fields($result) > 0) {
            
            while($row = pg_fetch_assoc($result)) {

                $peso = $row['peso'];
                $altura = $row['altura'];
                $IMC = $peso / ($altura * $altura);

                $conteudo .= '<h4 style="display: inline-block;">Seu IMC atual:</h4>';
                $conteudo .= '<h4 style="display: inline-block;">'+$IMC+'</h4>';
                $conteudo .= '<br>';
                $conteudo .= '<p style="display: inline-block;">';
                $conteudo .= 'Categoria:';
                $conteudo .= '</p>';

                if($IMC < 18.5){$categoria = "Subnutrido"}
                if(18.5 <= $IMC && $IMC < 25){$categoria = "Peso Saudável"}
                if(25 <= $IMC && $IMC < 30){$categoria = "Sobrepeso"}
                if(30 <= $IMC && $IMC < 35){$categoria = "Obesidade Grau I"}
                if(35 <= $IMC && $IMC < 40){$categoria = "Obesidade Grau II"}
                if($IMC >= 40){$categoria = "Obesidade Grau III"}

                $conteudo .= '<p style="display: inline-block;">'+$categoria+'';
                $conteudo .= '</p>';
            }
                
        }else{
            $error.='Nenhum resultado foi encontrado<br/>';
        }
			
        //Encerra a conexão com o banco
        pg_close($link);	
	}else{
        $error.='não foi possível conectar com o banco' .pg_last_error();
    }

	if(!$error){
        echo $conteudo;
	}

?>