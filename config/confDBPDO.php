<?php

    /* 
     * @author: Nacho del Prado Losada
     * @since: 29/10/2020
     * @description: Fichero de almaceniamiento de variables PDO
     */

    /*
     * Constantes para establecer conexión con la base de datos
     * $miDB = new PDO(DSN, USER, PASSWORD);
    */
    //Conexión clase
    define('DSN', 'mysql:host=192.168.20.19; dbname=DAW202DBDepartamentos');
    define('USER', 'usuarioDAW202DBDepartamentos');
    define('PASSWORD', 'paso');
    
    //Conexión casa
    /*define('DSN', 'mysql:host=192.168.1.155; dbname=DAW202DBDepartamentos');
    define('USER', 'usuarioDAW202DBDepartamentos');
    define('PASSWORD', 'P@ssw0rd');*/
         
    //Conexión 1n1
    /*define('DSN', 'mysql:host=db5000278672.hosting-data.io; dbname=dbs272016');
    define('USER', 'dbu62667');
    define('PASSWORD', 'Covid1234$');*/
?>