<?php
/*
 * @author: Nacho del Prado Losada
 * @since: 10/01/2021
 * Description: fichero que muestra al usuario los campos del departamento seleccionado 
 */

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

    //Se crea una variable que almacena una consulta sql para insertar los valores en la tabla Departamento
    $sql = "SELECT * FROM Departamento WHERE CodDepartamento=:CodDepartamento";

    //Preparación de la consulta
    $consulta = $miDB->prepare($sql);

    //Llamada a bindParam
    $consulta->bindParam(":CodDepartamento", $_GET['codDepartamento']);

    //Ejecución de la consulta
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
                        <h2>Mostrar departamento</h2>
                    </div>

                    <div>
                        <label for="codDepartamento">Código de departamento </label><br>
                        <input class="readonly" type="text" id="codDepartamento" name="codDepartamento" value="<?php echo $_GET['codDepartamento']; ?>" readonly/>

                        <label for="descripcion">Descripción </label><br>
                        <input class="readonly" type="text" id="descripcion" name="descripcion" value="<?php echo $descripcion; ?>" readonly/>

                        <label for="fechaBaja">Fecha Baja </label><br>
                        <input class="readonly" type="text" id="fechaBaja" name="fechaBaja" value="<?php echo ($fechaBaja ? $fechaBaja : "null"); ?>" readonly/>
                        
                        <label for="volumen">Volumen </label><br>
                        <input class="readonly" type="text" id="volumen" name="volumenNegocio" value="<?php echo $volumenNegocio; ?>" readonly/>
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