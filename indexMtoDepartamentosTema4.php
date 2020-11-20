<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="./webroot/css/estilo.css">

        <title>NPL - Proyecto DAW2</title>
    </head>

    <body>
        <?php
        /*
         * @author: Nacho del Prado Losada
         * @since: 19/11/2020
         * @description: index de la aplicación Mantenimiento de Departamentos
         */

        //Llamada a la librería de validación de formularios
        require_once 'core/201020libreriaValidacion.php';
        //Llamada al fichero de almacenamiento de consantes en PDO
        require_once 'config/confDBPDO.php';
        
        //Array de errores inicializado a null
        $aErrores = ["DescDepartamento" => null];

        //Varible de entrada correcta inicializada a true
        $entradaOK = true;           

        //Array de respuestas inicializado a null
        $aRespuestas = ["DescDepartamento" => null];
        ?>
        
        <header>
            <h1>Mantenimiento de Departamentos</h1>
        </header>
        
        <main>
            <div class="topBar">
                <p><a href="#">EXPORTAR</a></p>
                <p><a href="#">IMPORTAR</a></p>
                <p><a href="codigoPHP/altaDepartamento.php">AÑADIR</a></p>
            </div>
            
            <div class="buscarDepartamentos">
                <form name="input" action="<?php $_SERVER['PHP_SELF']?>" method="post">
                    <label>Descripción </label>
                    <input class="campoDescripcion" name="DescDepartamento" type="text" placeholder="Descripción del departamento" value="<?php 
                        //Devuelve el campo campoDescDepartamento si se había introducido correctamente
                        if(isset($_REQUEST['DescDepartamento']) && $aErrores["DescDepartamento"] == null){
                            echo $_REQUEST['DescDepartamento'];
                        }
                    ?>"/>

                    <span style="color:red">
                        <?php
                        //Imprime el error en el caso de que se introduzca mal el código del Departamento
                        if($aErrores["DescDepartamento"] != null){
                            echo $aErrores['DescDepartamento'];
                        }
                        ?> 
                    </span>
                    
                    <input class="botonBuscar" type="submit" value="BUSCAR" name="buscar"/>
                </form>
            </div>
            
            
                    
            <div class="mostrarDepartamentos">
                <?php
                    if(isset($_REQUEST['buscar'])){
                        //Comprobar que el campo campoDescDepartamento se ha rellenado con un alfanumérico
                        $aErrores["DescDepartamento"] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['DescDepartamento'], 255, 1, 0);

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
                    $aRespuestas = ["DescDepartamento" => $_REQUEST['DescDepartamento']];

                    //Mostrar registros de la tabla Departamento
                    try {
                        //Instanciar un objeto PDO y establecer la conexión con la base de datos
                        $miDB = new PDO(DSN, USER, PASSWORD);

                        //Establecer PDO::ERRMODE_EXCEPTION como valor del atributo PDO::ATTR_ERRMODE
                        $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        //Creación de una variable que almacena una consulta sql para insertar los valores en la tabla Departamento
                        $sql = "SELECT * FROM Departamento WHERE DescDepartamento LIKE '%{$aRespuestas['DescDepartamento']}%'";

                        //Preparación de la consulta
                        $consulta = $miDB->prepare($sql);

                        //Ejecución de la consulta
                        $consulta->execute();
                ?>
                <table>
                    <thead>
                        <tr>
                            <th>CodDepartamento</th>
                            <th>DescDepartamento</th>
                            <th>FechaBaja</th>
                            <th>VolumenNegocio</th>
                            <th>Operaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php  
                        $registro = $consulta->fetchObject();
                        while ($registro != null) {
                    ?>
                            <tr>
                                <td><?php echo $registro->CodDepartamento; ?></td>
                                <td><?php echo $registro->DescDepartamento; ?></td>
                                <td><?php echo $registro->FechaBaja; ?></td>
                                <td><?php echo $registro->VolumenNegocio; ?></td>
                                <td>
                                    <img src="doc/editar.png">
                                    <img src="doc/mostrar.png">
                                    <img src="doc/borrar.png">
                                </td>
                            </tr>
                            <?php
                            $registro = $consulta->fetchObject();
                        }
                    ?>
                    </tbody>
                </table>
                
                <?php
                } catch (PDOException $pdoe) {
                    //Mostrar mensaje de error
                    echo "<p style='color:red'>ERROR: " . $pdoe . "</p>";
                } finally {
                    //Cerrar la conexión
                    unset($miDB);
                }
        }
                ?>
            </div>
            
            <div class="botBar">
                <div>
                    <p><a href="../MtoDeDepartamentosTema4/mostrarCodigo/muestraMtoDepartamentosTema4.php">MOSTRAR CÓDIGO</a></p>
                    <p><a href="../proyectoDWES/indexProyectoDWES.php">VOLVER</a></p>
                </div>
            </div>
        </main>
        
        <footer>
            <p>2020-2021 - Nacho del Prado Losada - ignacio.pralos@educa.jcyl.es</p>
        </footer>
    </body>
</html>
