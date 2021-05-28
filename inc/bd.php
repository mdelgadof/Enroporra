<?php

	define('DB_HOST',"localhost");
	define('DB_NAME',"enroporra");
	define('DB_USER',"root");
	define('DB_PASSWORD',"");

    $conexion=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD);
    mysqli_query($conexion,"SET NAMES 'utf8'");
    mysqli_query($conexion,"USE ".DB_NAME);

if (!function_exists("getQuery")){
    function getQuery($query,&$conn) {
        $res=mysqli_query($conn,$query);
        return $res;
    }
}

if (!function_exists("errorBD")){
    function errorBD($clave,$query,&$debug,$rollback,&$conn) {
        global $environment;

        $devuelve="<span style='color:red;font-weight:bold'>ERROR BD</span><hr><b>FUNCTION</b> ".$clave."<hr><b>QUERY</b> ".$query."<hr>".mysqli_error($conn)."<hr>";

        if ($debug && $environment == "local") $devuelve.="<b>Tipo de error:</b> ".mysqli_error($conn)."<br><b>Query: </b>".$query."<br>";
        if ($rollback) $res=bd_getAll("ROLLBACK",$conn);

        die($devuelve);
    }
}

//Listado de funciones rapidas de acceso a bbdd
if (!function_exists("bd_insert")) {
    function bd_insert($query,&$conn,$debug=false,$rollback=false) {
        $devuelve = 1;
        if (!$res=getQuery($query,$conn)) {
            errorBD("bd_insert",$query,$debug,$rollback,$conn);
            $devuelve=-1;
        }
        if ($devuelve!=-1) {
            $devuelve=mysqli_insert_id($conn);
        }
        return $devuelve;
    }
}
if (!function_exists("bd_fetch")) {
    function bd_fetch($res) {
        $row=@mysqli_fetch_array($res);
        return $row;
    }
}
if (!function_exists("bd_fetch_object")) {
    function bd_fetch_object($res) {
        $row=@mysqli_fetch_array($res);
        return $row;
    }
}
if (!function_exists("bd_field_name")) {
    function bd_field_name($res, $i) {
        $name=@mysqli_num_fields($res, $i);
        return $name;
    }
}
if (!function_exists("bd_seek")) {
    function bd_seek($res,$fila) {
        $devuelve=@mysqli_data_seek($res,$fila);
        return $devuelve;
    }
}
if (!function_exists("bd_num")) {
    function bd_num($res) {
        $num=@mysqli_num_rows($res);
        return $num;
    }
}
if (!function_exists("bd_affected_rows")) {
    function bd_affected_rows($conn) {
        $num=@mysqli_affected_rows($conn);
        return $num;
    }
}
if (!function_exists("bd_num_fields")) {
    function bd_num_fields($res) {
        $num=@mysqli_num_fields($res);
        return $num;
    }
}
// Devuelve un valor, numrico o string
if (!function_exists("bd_getOne")){
    function bd_getOne($query,&$conn,$debug=false,$rollback=false) {
        if (!$res=getQuery($query,$conn))
            errorBD("bd_getOne",$query,$debug,$rollback,$conn);
        $arra=@mysqli_fetch_row($res);
        return $arra[0];
    }
}
// Devuelve un array con todos los datos de la primera fila de la query
if (!function_exists("bd_getRow")){
    function bd_getRow($query,&$conn,$debug=false,$rollback=false) {
        if (!$res=getQuery($query,$conn))
            errorBD("bd_getRow",$query,$debug,$rollback,$conn);
        $arra=mysqli_fetch_array($res);
        return $arra;
    }
}
// Devuelve un identificador result
if (!function_exists("bd_getAll")){
    function bd_getAll($query,&$conn,$debug=false,$rollback=false) {
        if (!$res=getQuery($query,$conn))
            errorBD("bd_getAll",$query,$debug,$rollback,$conn);
        return $res;
    }
}
// Devuelve los valores de la query en un string de la forma "(0,1,2,3,...,n)"
if (!function_exists("bd_getString")){
    function bd_getString($query,&$conn,$debug=false,$rollback=false) {
        if (!$res=getQuery($query,$conn))
            errorBD("bd_getString",$query,$debug,$rollback,$conn);
        if (!isset($devuelve)) $devuelve='';
        while ($arra=mysqli_fetch_row($res)) {
            $devuelve.=$arra[0].",";
        }
        if ($devuelve=="") return "(NULL)";
        return "(".substr($devuelve,0,strlen($devuelve)-1).")";
    }
}
// Devuelve los valores de la query en un string de la forma "(0,1,2,3,...,n)"
if (!function_exists("bd_getArray")){
    function bd_getArray($query,&$conn,$debug=false,$rollback=false) {
        $ret=array();
        if (!$res=bd_getAll($query,$conn))
            errorBD("bd_Array",$query,$debug,$rollback,$conn);
        if (!isset($devuelve)) $devuelve='';
        //$num = mysqli_num_rows($res);
        while($arra = bd_fetch($res))
        {
            $ret[] = $arra[0];
        }
        return $ret;
    }
}
if (!function_exists("bd_getResultset")){
    function bd_getResultset($query,$conn) {
        // Ejecutar consulta
        $result = bd_getAll($query,$conn);
        // Creamos un array vacío
        $salida = array();
        // Poblamos cada elemento del array con un registro
        while($row = @mysqli_fetch_assoc($result))
            $salida[] = $row;
        // Devolvemos el array bidimensional
        return $salida;
    }
}
// Devuelve el nombre de una fila de la que pasamos la id
// PARAMETROS: La tabla y la id
// REQUISITO: En la tabla pasada han de existir los campos "id" y "nombre"
if (!function_exists("bd_getNombre")) {
    function bd_getNombre($tabla,$id,&$conn,$debug=false,$rollback=false) {
        $query="SELECT nombre FROM $tabla WHERE id='$id'";
        return bd_getOne($query,$conn,$debug,$rollback);
    }
}
if (!function_exists("bd_getNombreActivo")) {
    function bd_getNombreActivo($tabla,$id,&$conn,$activo,$debug=false,$rollback=false) {
        $query="SELECT nombre FROM $tabla WHERE id='$id' AND ".$activo."='si'";
        return bd_getOne($query,$conn,$debug,$rollback);
    }
}
// Devuelve un identificador result. Simula un mysqli_query
if (!function_exists("bd_query")){
    function bd_query($query,&$conn,$debug=false,$rollback=false) {
        if (!$res=getQuery($query,$conn))
            errorBD("bd_query",$query,$debug,$rollback,$conn);
        return $res;
    }
}
// Cierra la conn con la bbdd
if (!function_exists("bd_close")){
    function bd_close($conn) {
        return mysqli_close($conn);
    }
}
// Devuelve todos los valores. Simula un mysqli_getAll
if (!function_exists("mysqli_getAll")){
    function mysqli_getAll($query,&$conn,$debug=false,$rollback=false) {
        $res=bd_getAll($query,$conn,$debug=false,$rollback=false);
        return $res;
    }
}

// Escapa los caracteres de una variable
if (!function_exists("bd_escape")){
    function bd_escape($string) {
        global $conn;
        return mysqli_real_escape_string($conn,$string);
    }
}
