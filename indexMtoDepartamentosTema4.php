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
            <div class="buscarDepartamentos">
                <form name="input" action="<?php $_SERVER['PHP_SELF']?>" method="post">
                    <div class="descripcion">
                        <label>Descripción </label>
                        <input name="DescDepartamento" type="text" placeholder="Descripción del departamento" value="<?php 
                            //Devuelve el campo campoDescDepartamento
                            if(isset($_REQUEST['DescDepartamento'])){
                                echo $_REQUEST['DescDepartamento'];
                            }
                        ?>"/>
                        <input class="botonBuscar" type="submit" value="BUSCAR" name="buscar"/>
                    </div>

                    <div class="topBar">
                        <a href="codigoPHP/exportarDepartamentos.php">EXPORTAR</a>
                        <a href="#">IMPORTAR</a>
                        <a href="codigoPHP/altaDepartamento.php">AÑADIR</a>
                    </div>
                </form>
            </div>
                
            <div class="mostrarDepartamentos">
                <table>
                    <thead>
                        <tr>
                            <th>CodDepartamento</th>
                            <th>DescDepartamento</th>
                            <th>FechaBaja</th>
                            <th>VolumenNegocio</th>
                            <th></th>
                        </tr>
                    </thead>
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
                    
                    //Si los datos han sido introducidos correctamente
                    if(isset($_REQUEST['DescDepartamento'])){
                        $aRespuestas = ["DescDepartamento" => $_REQUEST['DescDepartamento']];
                    }
                    
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
                
                    <tbody>
                    <?php  
                        $registro = $consulta->fetchObject();
                        
                        //Si no se encuentra ningún departamento se muestra un mensaje
                        if($registro == null){
                            echo '<td colspan="4" class="sinDepartamentos">No se han encontrado departamentos</td>';
                        }
                        
                        //Se muestran los departamentos encontrados
                        while ($registro != null) {
                    ?>
                        <tr>
                                <td <?php if($registro->FechaBaja){echo 'style="color: red !important"';} ?>><?php echo $registro->CodDepartamento; ?></td>
                                <td <?php if($registro->FechaBaja){echo 'style="color: red !important"';} ?>><?php echo $registro->DescDepartamento; ?></td>
                                <td <?php if($registro->FechaBaja){echo 'style="color: red !important"';} ?>><?php echo ($registro->FechaBaja ? $registro->FechaBaja : "null"); ?></td>
                                <td <?php if($registro->FechaBaja){echo 'style="color: red !important"';} ?>><?php echo $registro->VolumenNegocio; ?></td>
                                <td>
                                    <a href="./codigoPHP/editarDepartamento.php?codDepartamento=<?php echo $registro->CodDepartamento; ?>"><img src="doc/images/editar.png"></a>
                                    <a href="./codigoPHP/mostrarDepartamento.php?codDepartamento=<?php echo $registro->CodDepartamento; ?>"><img src="doc/images/mostrar.png"></a>
                                    <a href="./codigoPHP/borrarDepartamento.php?codDepartamento=<?php echo $registro->CodDepartamento; ?>"><img src="doc/images/papelera.png"></a>
                                </td>
                            </tr>
                            <?php
                            $registro = $consulta->fetchObject();
                        }
                    ?>
                    </tbody>
                
                
                <?php
                } catch (PDOException $pdoe) {
                    //Mostrar mensaje de error
                    echo "<p style='color:red'>ERROR: " . $pdoe . "</p>";
                } finally {
                    //Cerrar la conexión
                    unset($miDB);
                }
                ?>
                    </table>
            </div>
            
            <div class="botBar">
                <div>
                    <a href="../MtoDeDepartamentosTema4/mostrarCodigo/muestraMtoDepartamentosTema4.php">MOSTRAR CÓDIGO</a>
                    <a href="../proyectoDWES/indexProyectoDWES.php">VOLVER</a>
                </div>
            </div>
        </main>
        
        <footer>
            <div class="enlaces">
                <a href="https://github.com/NachoPLSauces" target="_blank"><img src="doc/images/github-icon.png" alt="github"></a>
                <a href="http://daw202.ieslossauces.es/" target="_blank"><img src="doc/images/1and1-icon.png" alt="github"></a>
            </div>
            <div class="nombre">
                <h3>Nacho del Prado Losada</h3>
                <h3>ignacio.pralos@educa.jcyl.es</h3>
            </div>
        </footer>
    </body>
</html>
