<?php
/*
 * @author: Nacho del Prado Losada
 * @since: 10/01/2021
 * Description: fichero que pide al usuario rellenar campos. Si son correctos crea un nuevo Departamento 
 */

//Llamada a la librería de validación de formularios
require_once '../core/201020libreriaValidacion.php';
//Llamada al fichero de almacenamiento de consantes en PDO
require_once '../config/confDBPDO.php';

//Si el usuario pulsa 'Volver' le dirijo al index 
if(isset($_REQUEST['volver'])){
    header("Location: ../indexMtoDepartamentosTema4.php");
    exit;
}

//Array de errores inicializado a null
$aErrores = ["codDepartamento" => null,
             "descripcion" => null,
             "volumenNegocio" => null];

//Variable obligatorio inicializada a 1
define("OBLIGATORIO", 1);

//Variables MAX_FLOAT y MIN_FLOAT de los números máximos y mímimos permitidos
define ('MAX_FLOAT', 3.402823466E+38);
define ('MIN_FLOAT', -3.402823466E+38);

//Varible de entrada correcta inicializada a true
$entradaOK = true;           

//Array de respuestas inicializado a null
$aRespuestas = ["codDepartamento" => null,
             "descripcion" => null,
             "volumenNegocio" => null];

if(isset($_REQUEST['crear'])){
    //Comprobar que el campo codDepartamento se ha rellenado con un código válido
    $aErrores["codDepartamento"] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['codDepartamento'], 3, 3, OBLIGATORIO);
    //Comprobar que el campo descripción se ha rellenado con alfanuméricos
    $aErrores["descripcion"] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['descripcion'], 200, 1, OBLIGATORIO);
    //Comprobar que el campo volumenNegocio se ha rellenado con float
    $aErrores["volumenNegocio"] = validacionFormularios::comprobarFloat($_REQUEST['volumenNegocio'], MAX_FLOAT, MIN_FLOAT, OBLIGATORIO);

    try {
        //Instanciar un objeto PDO y establecer la conexión con la base de datos
        $miDB = new PDO(DSN, USER, PASSWORD);

        //Establecer PDO::ERRMODE_EXCEPTION como valor del atributo PDO::ATTR_ERRMODE
        $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //Creo una variable que almacena una consulta sql para ver si el campo rellenado ya existía en la base de datos
        $sql = "SELECT CodDepartamento from Departamento where CodDepartamento='{$_REQUEST['codDepartamento']}'";

        //Preparamos la consulta
        $consulta = $miDB->prepare($sql);

        //Se ejecuta la consulta
        $consulta->execute();

        //Se comprueba el campo y en caso de existir, muestra un mensaje de error
        if($consulta->rowCount()>0){
            $aErrores['codDepartamento'] = "El código introducido ya existe";
        }

    } catch (PDOException $pdoe) {
        //Mostrar mensaje de error
        echo "<p style='color:red'>ERROR: " . $pdoe . "</p>";
    } finally {
        //Cerrar la conexión
        unset($miDB);
    }

    //Comprobar si algún campo del array de errores ha sido rellenado
    foreach ($aErrores as $clave => $valor) {
        //Comprobar si el campo ha sido rellenado
        if($valor!=null){
            $_REQUEST[$clave] = "";
            $entradaOK = false;
        }
    }

}
else{
    $entradaOK = false;
}

if($entradaOK){
    //Si los datos han sido introducidos correctamente
    $aRespuestas = ["codDepartamento" => $_REQUEST['codDepartamento'],
                    "descripcion" => $_REQUEST['descripcion'],
                    "volumenNegocio" => $_REQUEST['volumenNegocio']];

    //Mostrar registros de la tabla Departamento
    try {
        //Instanciar un objeto PDO y establecer la conexión con la base de datos
        $miDB = new PDO(DSN, USER, PASSWORD);

        //Establecer PDO::ERRMODE_EXCEPTION como valor del atributo PDO::ATTR_ERRMODE
        $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //Se crea una variable que almacena una consulta sql para insertar los valores en la tabla Departamento
        $sql = <<<EOD
                INSERT INTO Departamento (CodDepartamento, DescDepartamento, VolumenNegocio) 
                VALUES (:CodDepartamento, :DescDepartamento, :VolumenNegocio)
EOD;

        //Preparación de la consulta
        $consulta = $miDB->prepare($sql);

        //Llamada a bindParam
        $consulta->bindParam(":CodDepartamento", $aRespuestas['codDepartamento']);
        $consulta->bindParam(":DescDepartamento", $aRespuestas['descripcion']);
        $consulta->bindParam(":VolumenNegocio", $aRespuestas['volumenNegocio']);

        //Ejecución de la consulta
        $consulta->execute();

        header("Location: ../indexMtoDepartamentosTema4.php");
        exit;

    } catch (PDOException $pdoe) {
        //Mostrar mensaje de error
        echo "<p style='color:red'>ERROR: " . $pdoe . "</p>";
    } finally {
        //Cerrar la conexión
        unset($miDB);
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Mantenimiento de Departamentos</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../webroot/css/estilo.css">

        
    </head>

    <body>
        <header>
            <h1>Mantenimiento de Departamentos</h1>
        </header>
        
        
        <main>
            <form class="formulario" name="input" action="<?php $_SERVER['PHP_SELF']?>" method="post">
                <fieldset>
                    <div>
                        <h2>Alta departamento</h2>
                    </div>

                    <div>
                        <label for="codDepartamento">Código de departamento <span>*</span></label><br>
                        <span style="color:red">
                            <?php
                            //Imprime el error en el caso de que se introduzca mal el código del Departamento
                            if($aErrores["codDepartamento"] != null){
                                echo $aErrores['codDepartamento'];
                            }
                            ?> 
                        </span>

                        <input type="text" id="codDepartamento" name="codDepartamento" placeholder="Formato: ABC" value="<?php 
                            //Devuelve el campo codDepartamento si se había introducido correctamente
                            if(isset($_REQUEST['codDepartamento']) && $aErrores["codDepartamento"] == null){
                                echo $_REQUEST['codDepartamento'];
                            }
                        ?>"/>

                        <label for="descripcion">Descripción <span>*</span></label><br>
                        <span style='color:red'>
                            <?php
                            //Imprime el error en el caso de que se introduzca mal la descripcion
                            if($aErrores["descripcion"] != null){
                                echo $aErrores['descripcion'];
                            }
                            ?> 
                        </span>

                        <input type="text" id="descripcion" name="descripcion" placeholder="descripción del departamento" value="<?php 
                            //Devuelve el campo descripcion si se había introducido correctamente
                            if(isset($_REQUEST['descripcion']) && $aErrores["descripcion"] == null){
                                echo $_REQUEST['descripcion'];
                            }
                        ?>"/>

                        <label for="volumen">Volumen <span>*</span></label><br>
                        <span style='color:red'>
                            <?php
                            //Imprime el error en el caso de que se introduzca mal el apellido2
                            if($aErrores["volumenNegocio"] != null){
                                echo $aErrores['volumenNegocio'];
                            }
                            ?> 
                        </span>

                        <input type="text" id="volumen" name="volumenNegocio" placeholder="Volumen negocio" value="<?php 
                            //Devuelve el campo volumenNegocio si se había introducido correctamente
                            if(isset($_REQUEST['volumenNegocio']) && $aErrores["volumenNegocio"] == null){
                                echo $_REQUEST['volumenNegocio'];
                            }
                        ?>"/>

                        <input class="enviar" type="submit" value="Crear departamento" name="crear"/>
                        <input class="enviar" type="reset" value="Borrar"/>
                    </div>
                </fieldset>

                <div class="formBottom">
                    <input class="volver" type="submit" value="Volver" name="volver">
                </div>
            </form>
        </main>

        <footer>
            <div class="enlaces">
                <a href="https://github.com/NachoPLSauces" target="_blank"><img src="../doc/images/github-icon.png" alt="github"></a>
                <a href="http://daw202.ieslossauces.es/" target="_blank"><img src="../doc/images/1and1-icon.png" alt="github"></a>
            </div>
            <div class="nombre">
                <h3>Nacho del Prado Losada</h3>
                <h3>ignacio.pralos@educa.jcyl.es</h3>
            </div>
        </footer>
    </body>
</html>