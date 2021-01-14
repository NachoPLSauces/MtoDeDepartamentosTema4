<!DOCTYPE html>
<html>
    <head>
        <title>Ejercicios Tema 4</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body>
        <?php
        /*
         * @author: Nacho del Prado Losada
         * @since: 10/01/2021
         * @description: permite tomar datos de un fichero .xml 
         */

        //Llamada al fichero de almacenamiento de consantes en PDO
        require_once '../config/confDBPDO.php';
        
        //Creación variable tipo DOMDocument
        $dom = new DOMDocument("1.0", "UTF-8");
        $dom->load("../tmp/departamento.xml");
        
        
        if(isset($_REQUEST['importar'])){
            try {
                //Instanciar un objeto PDO y establecer la conexión con la base de datos
                $miDB = new PDO(DSN, USER, PASSWORD);

                //Establecer PDO::ERRMODE_EXCEPTION como valor del atributo PDO::ATTR_ERRMODE
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //Creo una variable que almacena una consulta sql
                $sql = "INSERT INTO Departamento (CodDepartamento, DescDepartamento, FechaBaja, VolumenNegocio) VALUES (:CodDepartamento, :DescDepartamento, :FechaBaja, :VolumenNegocio)";

                //Obtener el número de departamentos
                $numDepartamentos = $dom->getElementsByTagName("Departamento")->count();
                
                //Recorrer los departamentos para introducir los registros en la base de datos
                for($departamento=0;$departamento<=$numDepartamentos;$departamento++){
                    //Preparar la consulta
                    $consulta = $miDB->prepare($sql);
                
                    //Creo variables y las inicializo para insertar los registros
                    $CodDepartamento = $dom->getElementsByTagName("CodDepartamento")->item($departamento)->nodeValue;
                    $DescDepartamento = $dom->getElementsByTagName("DescDepartamento")->item($departamento)->nodeValue;
                    $FechaBaja = $dom->getElementsByTagName("FechaBaja")->item($departamento)->nodeValue;
                    $VolumenNegocio = $dom->getElementsByTagName("VolumenNegocio")->item($departamento)->nodeValue;
                    
                    //Llamada a bindParam
                    $consulta->bindParam(":CodDepartamento", $CodDepartamento);
                    $consulta->bindParam(":DescDepartamento", $DescDepartamento);
                    $consulta->bindParam(":FechaBaja", $FechaBaja);
                    $consulta->bindParam(":VolumenNegocio", $VolumenNegocio);
                
                    //Ejecución de la consulta
                    $consulta->execute(); 
                }

                echo "<p style='color: green'>Las consultas se han realizado correctamente</p>";
            } catch (PDOException $pdoe) {
                echo "<p style='color: red'>ERROR: " . $pdoe->getMessage() . "</p>";

            } finally {
                //Cerrar la conexión
                unset($miDB);
            }
        }
        else{
        ?>
        <form name="input" method="post">
            <fieldset>
                <legend>Importar archivos</legend>
                <label>Pulsa para importar el archivo .xml</label><br>
                <input type="submit" name="importar" value="Importar">
            </fieldset>
        </form>
        
        <?php
        }
        ?>
    </body>
</html>



