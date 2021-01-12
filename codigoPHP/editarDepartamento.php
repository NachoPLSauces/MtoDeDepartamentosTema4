<?php
/*
 * @author: Nacho del Prado Losada
 * @since: 10/01/2021
 * Description: fichero que permite al usuario editar los campos del departamento seleccionado
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

//Cargar los campos del departamento seleccionado
try {
    //Instanciar un objeto PDO y establecer la conexión con la base de datos
    $miDB = new PDO(DSN, USER, PASSWORD);

    //Establecer PDO::ERRMODE_EXCEPTION como valor del atributo PDO::ATTR_ERRMODE
    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Creo una variable que almacena una consulta sql para ver si el campo rellenado ya existía en la base de datos
    $sql = "SELECT * from Departamento WHERE CodDepartamento=:CodDepartamento";

    //Preparamos la consulta
    $consulta = $miDB->prepare($sql);

    //Llamada a bindParam
    $consulta->bindParam(":CodDepartamento", $_GET['codDepartamento']);

    //Se ejecuta la consulta
    $consulta->execute();

    $registro = $consulta->fetchObject();
    
    //Almaceno el contenido de los campos en variables
    $descripcion = $registro->DescDepartamento;
    $fechaBaja = $registro->FechaBaja;
    $volumenNegocio = $registro->VolumenNegocio;

} catch (PDOException $pdoe) {
    //Mostrar mensaje de error
    echo "<p style='color:red'>ERROR: " . $pdoe . "</p>";
} finally {
    //Cerrar la conexión
    unset($miDB);
}

//Array de errores inicializado a null
$aErrores = ["descripcion" => null,
             "volumenNegocio" => null];

//Variable obligatorio inicializada a 1
define("OBLIGATORIO", 1);

//Variables MAX_FLOAT y MIN_FLOAT de los números máximos y mímimos permitidos
define ('MAX_FLOAT', 3.402823466E+38);
define ('MIN_FLOAT', -3.402823466E+38);

//Varible de entrada correcta inicializada a true
$entradaOK = true;   

if(isset($_REQUEST['editar'])){
    //Comprobar que el campo descripción se ha rellenado con alfanuméricos
    $aErrores["descripcion"] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['descripcion'], 200, 1, OBLIGATORIO);
    //Comprobar que el campo volumenNegocio se ha rellenado con float
    $aErrores["volumenNegocio"] = validacionFormularios::comprobarFloat($_REQUEST['volumenNegocio'], MAX_FLOAT, MIN_FLOAT, OBLIGATORIO);

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
    //Mostrar registros de la tabla Departamento
    try {
        //Instanciar un objeto PDO y establecer la conexión con la base de datos
        $miDB = new PDO(DSN, USER, PASSWORD);

        //Establecer PDO::ERRMODE_EXCEPTION como valor del atributo PDO::ATTR_ERRMODE
        $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //Se crea una variable que almacena una consulta sql para insertar los valores en la tabla Departamento
        $sql = "UPDATE Departamento SET DescDepartamento=:DescDepartamento, VolumenNegocio=:VolumenNegocio WHERE CodDepartamento=:CodDepartamento";

        //Preparación de la consulta
        $consulta = $miDB->prepare($sql);

        //Llamada a bindParam
        $consulta->bindParam(":CodDepartamento", $_GET['codDepartamento']);
        $consulta->bindParam(":DescDepartamento", $_REQUEST['descripcion']);
        $consulta->bindParam(":VolumenNegocio", $_REQUEST['volumenNegocio']);

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
                        <h2>Editar departamento</h2>
                    </div>

                    <div>
                        <label for="codDepartamento">Código de departamento </label><br>
                        <input class="readonly" type="text" id="codDepartamento" name="codDepartamento" value="<?php echo $_GET['codDepartamento']; ?>" readonly/>

                        <label for="descripcion">Descripción <span>*</span></label><br>
                        <span style='color:red'>
                            <?php
                            //Imprime el error en el caso de que se introduzca mal la descripcion
                            if($aErrores["descripcion"] != null){
                                echo $aErrores['descripcion'];
                            }
                            ?> 
                        </span>

                        <input type="text" id="descripcion" name="descripcion" value="<?php 
                            //Rellena el campo descripcion
                            if(isset($_REQUEST['descripcion']) && $aErrores["descripcion"] == null){
                                echo $_REQUEST['descripcion'];
                            }
                            else{
                                echo $descripcion;
                            }
                        ?>"/>
                        
                        <label for="fechaBaja">Fecha Baja </label><br>
                        <input class="readonly" type="text" id="fechaBaja" name="fechaBaja" value="<?php echo ($fechaBaja ? $fechaBaja : "null"); ?>" readonly/>
                        
                        <label for="volumen">Volumen <span>*</span></label><br>
                        <span style='color:red'>
                            <?php
                            //Imprime el error en el caso de que se introduzca mal el volumen de negocio
                            if($aErrores["volumenNegocio"] != null){
                                echo $aErrores['volumenNegocio'];
                            }
                            ?> 
                        </span>

                        <input type="text" id="volumen" name="volumenNegocio" value="<?php 
                            //Rellena el campo volumenNegocio
                            if(isset($_REQUEST['volumenNegocio']) && $aErrores["volumenNegocio"] == null){
                                echo $_REQUEST['volumenNegocio'];
                            }
                            else{
                                echo $volumenNegocio;
                            }
                        ?>"/>

                        <input class="enviar" type="submit" value="Confirmar cambios" name="editar"/>
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