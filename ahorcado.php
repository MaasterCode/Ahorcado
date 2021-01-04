<!DOCTYPE html>
<html>

<head>
    <style>
        :root {
            font-family: Arial, Helvetica, sans-serif;
        }

        * {
            box-sizing: border-box;
        }
        html{
            height: fit-content;
        }

        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .juego {
            text-align: center;
            width: fit-content;
            margin: 0 auto;
        }

        .bienvenida {
            position: relative;
            text-align: center;
            margin-bottom: 150px;
        }

        .oculto {
            display: none;
        }

        .esta {
            position: relative;
            left: 210px;
            margin: 0 auto;
        }

        .formulario {
            position: relative;
        }

        .formulario form {
            max-height: 20px;
        }

        .formulario form input {
            margin: 0 10px;
        }

        input #letra {
            margin: 0 30px;
        }

        .palabra {
            position: relative;
        }

        .conseguido {
            position: relative;
        }

        .letraas {
            position: relative;
            width: 200px;
            padding: 5px;
            margin: 0 auto;
        }

        .letraas p{
            width: 200px;
            padding: 5px;
        }

        .letraas #g{
            border: 1px solid green;
        }  

        .letraas #r{
            border: 1px solid red;
        }

        .reiniciar {
            position: relative;
        }
        .imga{
            position: relative;
            bottom: 50vh;
            width: fit-content;
            margin: 0 auto;
            right: 30vw;
            z-index: -1;
        }
        .imagen{
            display: block;
            height: 200px;
            width: 200px;
        }
    </style>

</head>

<body>
    <div class="juego">
        <?php
        $palabras = ["Empacar", "Donde", "Hombre", "Monedero", "Ocupar", "Observatorio", "Himalaya", "Garfio", "Carrera", "Rabino", "Denominados", "Web", "Incapacidad", "Tribu"];
        if (!isset($_GET['palabra'])) {
            $palJuego = $palabras[array_rand($palabras)]; //Selecciono una palabra al azar
        } else {
            $palJuego = $_GET['palabra'];
        }

        $pal = $palJuego;
        $palJuego = strtolower($palJuego);
        $palabraJue = str_split($palJuego); //Creo un array con las letras de la palabra, también es la palabra con la que comparo si existe la letra introducida
        $numLetras = count($palabraJue); //Cuento el numero de letras de la palabra
        $falladas = array();


        if(!isset($_GET['contador'])){
            $contador = 0;
        }else{
            $contador = $_GET['contador'];
        }

        if (isset($_GET['letras'])) {
            $letras = $_GET['letras'];
        } else {
            $letras = array();
        }

        if(isset($_GET['falladas'])){
            $falladas = $_GET['falladas'];
        }


        if (isset($_GET['conseguido'])) {
            $conseguido = $_GET['conseguido'];
        } else {
            $conseguido = array($numLetras); //Esta es el array de letras que tiene que adivinar el jugador, se rellena con _ que se sustituyen por las letras adivinadas
            $conseguido = array_fill(0, $numLetras, "_"); //Lleno el array con _    
        }

        if (isset($_GET['letra'])) {
            $letra = strtolower($_GET['letra']);
            $encontrada = compruebaContenido($conseguido, $letra, $palabraJue, $falladas);
        }


        //Este array almacena las letras acertadas en un formulario para hacerles un get
        function formularioLetras(&$letras)
        {
            foreach ($letras as $letra) {
                echo "<input type = \"checkbox\" class=\"oculto\" name = \"letras[]\" value =\"$letra\" checked = \"checked\">";
            }
        }

        function formularioFalladas(&$falladas){
            foreach($falladas as $fallos){
                echo "<input type = \"checkbox\" class=\"oculto\" name = \"falladas[]\" value =\"$fallos\" checked = \"checked\">";
            }
        }
        

        function formularioConseguido($conseguido)
        {
            foreach ($conseguido as $letra) {
                echo "<input type = \"checkbox\" class=\"oculto\" name = \"conseguido[]\" value =\"$letra\" checked = \"checked\">";
            }
        }

        function compruebaContenido(&$conseguido, $letra, &$palabra, &$falladas)
        {
            global $contador, $letras;
            if (in_array($letra, $palabra, true)) {
                $indices = array_keys($palabra, $letra, true);
                foreach ($indices as $valor) {
                    $conseguido[$valor] = $letra;
                    array_push($letras, $letra);
                }
                return true;
            }else {
                if((ord($letra) > 122 && ord($letra) === 164) ||  ord($letra) < 97){
                    echo "No es una letra $letra";
                }else{
                    $contador = $contador + 1;
                    array_push($falladas, $letra);    
                }
                return false;
            }
        }
        function muestraPalabra(&$palabra)
        {
            echo '<div class= "palabra">';
            echo "<p>$palabra</p>";
            echo '</div>';
        }

        function enseñaConseguido(&$conseguido)
        {
            echo "";
            echo '<div class = "conseguido">';
            echo '<p>';
            foreach ($conseguido as $letra) {
                echo $letra . " ";
            }
            echo '</p>';
            echo '</div>';
        }
        function letraEsta(&$encontrada)
        {
            echo '<div class = "esta">';
            if ($encontrada) {
                echo '<p>La letra está </p>';
            } else if (!$encontrada) {
                echo '<p>La letra no está</p>';
            }
            echo '</div>';
        }

        function muestraLetras(){
            global $letras, $falladas;
            echo "<p id = \"g\">Letras acertadas</p><br>";
            foreach($letras as $letra){
                echo $letra;
            }
            echo "<p id = \"r\">Letras falladas</p><br>";
                foreach ($falladas as $fallo){
                    echo $fallo;
                }

        }


        ?>
        <div class="bienvenida">
            <h1>Bienvenido al ahorcado</h1>
            <p>Por Félix García Narocki</p>
        </div>
        <div class="formulario">
            <form action="#" method="get">
            <?php
                echo "<input type = \"hidden\" value = \"$palJuego\" name = \"palabra\">";
                echo "<input type = \"hidden\" value = \"$contador\" name = \"contador\">";

                formularioLetras($letras);
                formularioFalladas($falladas);
                formularioConseguido($conseguido);
                if($contador < 6){
                    echo '<lable for="letra">Escribe una letra</lable>';
                    echo '<input type = "text" name = "letra" id = "letra" maxlength="1" autocomplete="off" size = "5" autofocus="on">';
                    echo '<input type = "submit" value ="probar">';
                }else{
                    echo 'Has perdido';
                }
                if (empty($encontrada)){
                     letraEsta($encontrada);
                }
                ?>

            </form>
        </div>
        <?php
        if (isset($conseguido)) {
            enseñaConseguido($conseguido);
        }

        echo ' <div class = "letraas">';
        muestraLetras();
        echo '</div>';
        if($contador > 6){
            muestraPalabra($pal);
        }
        ?>
        <div class="reiniciar">
            <a href="ahorcado.php">Reiniciar</a>
        </div>
    </div>
    <?php
        if($contador > 0){
            echo '<div class="imga">';
            echo "<img class = \"imagen\" src=\"Imagenes/$contador.jpg\">";
            echo '</div>';
        }  
    ?>
</body>

</html>