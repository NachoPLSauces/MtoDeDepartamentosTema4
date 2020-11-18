<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="./webroot/css/estilo.css">

        <title>NPL - Proyecto DAW2</title>
    </head>

    <body>
        <header>
            <h1>Mantenimiento de Departamentos</h1>
        </header>
        
        <main>
            <div class="topBar">
                <p><a href="#">EXPORTAR</a></p>
                <p><a href="#">IMPORTAR</a></p>
                <p><a href="#">AÑADIR</a></p>
            </div>
            
            <div class="buscarDepartamentos">
                <form name="input" action="<?php $_SERVER['PHP_SELF']?>" method="post">
                    <label>Descripción </label>
                    <input class="campoDescripcion" type="text" name="nombre" placeholder="Descripción del departamento" value="">
                    
                    <input class="botonBuscar" type="submit" value="BUSCAR" name="buscar"/>
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
                            <th>Operaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Campo</td>
                            <td>Campo</td>
                            <td>Campo</td>
                            <td>Campo</td>
                            <td>Campo</td>
                        </tr>
                    </tbody>
                </table>
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
