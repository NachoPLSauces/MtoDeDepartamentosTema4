<?php
/*
 * @author: Nacho del Prado Losada
 * @since: 10/11/2020
 * @description: Página web que toma datos (código y descripción) de la tabla Departamento y guarda en un fichero departamento.xml. (COPIA DE SEGURIDAD / EXPORTAR
 */

//Llamada al fichero de almacenamiento de consantes en PDO
require_once '../config/confDBPDO.php';

try {
    //Instanciar un objeto PDO y establecer la conexión con la base de datos
    $miDB = new PDO(DSN, USER, PASSWORD);

    //Establecer PDO::ERRMODE_EXCEPTION como valor del atributo PDO::ATTR_ERRMODE
    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Creo una variable que almacena una consulta sql
    $sql = "SELECT * from Departamento";

    //Preparar la consulta
    $consulta = $miDB->prepare($sql);

    //Ejecución de la consulta
    $consulta->execute();

    //Creación variable tipo DOMDocument
    $dom = new DOMDocument("1.0", "UTF-8");
    $dom->formatOutput = true;

    //Creo la raíz del documento xml
    $xmlTablaDepartamentos = $dom->appendChild($dom->createElement("TablaDepartamentos"));

    //Recorro las filas de la consulta sql
    $registro = $consulta->fetchObject();
    while($registro){
        //Creo un elemento hijo de TablaDepartamentos
        $xmlDepartamento = $xmlTablaDepartamentos->appendChild($dom->createElement("Departamento"));

        //Creo los hijos de Departamento, que serán CodDepartamento y DescDepartamento
        $xmlDepartamento->appendChild($dom->createElement("CodDepartamento", $registro->CodDepartamento));
        $xmlDepartamento->appendChild($dom->createElement("DescDepartamento", $registro->DescDepartamento));
        $xmlDepartamento->appendChild($dom->createElement("FechaBaja", $registro->FechaBaja));
        $xmlDepartamento->appendChild($dom->createElement("VolumenNegocio", $registro->VolumenNegocio));

        $registro = $consulta->fetchObject();
    }

    //Guardar el archivo xml
    $dom->save("../tmp/departamento.xml");                

    header('Content-Type: text/xml');
    header('Content-Disposition: attachment;filename="departamento.xml"');
    readfile("../tmp/departamento.xml");
} catch (PDOException $pdoe) {
    echo "<p style='color: red'>ERROR: " . $pdoe->getMessage() . "</p>";

} finally {
    //Cerrar la conexión
    unset($miDB);
}
?>


