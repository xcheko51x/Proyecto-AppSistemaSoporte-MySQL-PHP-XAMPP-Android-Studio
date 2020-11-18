<?php

error_reporting(0);

/*
    LISTA DE ACCIONES

    100 - Login

    ACCIONES USUARIOS

    101 - Add Reporte
    102 - Obtener Reportes del Usuario

    ACCIONES TECNICOS

    200 - Obtener reportes con estado de falla
    201 - Obtener reportes con estado de reparacion
    202 - Tomar reporte de falla el tecnico
    203 - Editar reporte con la solucion del tecnico
    204 - Obtener reportes con estado de solucionado
    205 - Obtener reportes con busqueda por id_reporte o usuario 

    ACCIONES ADMINISTRADORES

    300 - Obtener todos los usuarios y tecnicos
    301 - Actualizar los datos de un usuario o tecnico en especifico
    302 - Agregar usuario o tecnico
    303 - Obtener totales reportes
*/

require 'conexion.php';

$accion = $_POST['accion'];
//$accion = 303;

if($accion == null) {
    echo json_encode(array("ERROR" => "UPS, ME DESCUBRISTE NO SIRVE :("));
} else if ($accion == 100) {

    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    //$usuario = "xcheko51x";
    //$contrasena = "1234";

    $sql = "
        select * 
        from usuarios 
        where usuario='$usuario' and contrasena='$contrasena'
    ";

    $query = $mysqli->query($sql);

    $registros = $query->num_rows;

    if($registros == 0) {
        // NO HAY REGISTROS O COINCIDENCIAS
        //echo json_encode(array("ERROR" => "No hay coincidencia, revisa los datos"));
        echo json_encode(array("codigo" => "ERROR", "mensaje" => "No hay coincidencia, revisa los datos."));
    } else if($registros == 1) {
        // TODO OK
        $data = array();
        while($res = $query->fetch_assoc()) {
            $data[] = $res;
        }
        //echo json_encode(array("Usuario" => $data));
        //echo json_encode($data);
        echo json_encode(array("codigo" => "OK","Usuario" => $data));
    } else {
         // MAS DE UNA COINCIDENCIA
         //echo json_encode(array("ERROR" => "Mas de una coincidencia, contacta al administrador"));
         echo json_encode(array("codigo" => "ERROR", "mensaje" => "Mas de una coincidencia, contacta al administrador."));
    }

} else if($accion == 101) {

    $fecha = $_POST['fecha'];
    $usuario = $_POST['usuario'];
    $desc_reporte = $_POST['desc_reporte'];
    
    //$fecha = "27-09-2020";
    //$usuario = "usuario";
    //$desc_reporte = "Descripcion del reporte";
    //echo $fecha." ".$usuario." ".$desc_reporte;

    $sql = "
        insert into reportes (fecha_reporte, usuario, desc_reporte, estado_reporte)
        values ('$fecha', '$usuario', '$desc_reporte', 'FALLA');
    ";

   if($mysqli->query($sql) === TRUE) {
       echo json_encode(array("codigo" => "OK", "mensaje" => "Se registro el reporte exitosamente."));
   } else {
       echo json_encode(array("codigo" => "ERROR", "mensaje" => "Se produjo un problema, intentelo mas tarde."));
   }

} else if($accion == 102) {

    $usuario = $_POST['usuario'];
    //$usuario = "usuario";

    $sql = "
        select * 
        from reportes
        where usuario='$usuario'
        order by id_reporte desc;
    ";

    $query = $mysqli->query($sql);

    $data = array();
    while($res = $query->fetch_assoc()) {
        $data[] = $res;
    }

    echo json_encode(array("codigo" => "OK", "Reportes" => $data));

} else if($accion == 200) {

    $sql = "
        select * from
        reportes where estado_reporte='FALLA'
    ";

    $query = $mysqli->query($sql);

    $data = array();
    while($res = $query->fetch_assoc()) {
        $data[] = $res;
    }

    echo json_encode(array("codigo" => "OK", "Reportes" => $data));

} else if($accion == 201) {

    $tecnico = $_POST['tecnico'];

    $sql = "
        select * 
        from reportes 
        where estado_reporte='REPARACION'
        and tecnico='$tecnico'
    ";

    $query = $mysqli->query($sql);

    $data = array();
    while($res = $query->fetch_assoc()) {
        $data[] = $res;
    }

    echo json_encode(array("codigo" => "OK", "Reportes" => $data));

} else if($accion == 202) {

    $id_reporte = $_POST['id_reporte'];
    $fecha = $_POST['fecha'];
    $tecnico = $_POST['tecnico'];

    $sql = "
        update reportes
        set fecha_solucion='$fecha', tecnico='$tecnico', estado_reporte='REPARACION'
        where id_reporte='$id_reporte'
    ";

    $query = $mysqli->query($sql);

    if($query === TRUE) {
        echo json_encode(array("codigo" => "OK", "mensaje" => "Se asigno el reporte al tecnico exitosamente."));
    } else {
        echo json_encode(array("codigo" => "ERROR", "mensaje" => "No se pudo asignar el reporte, intentalo mas tarde o coontacta a un administrador."));
    }

} else if($accion == 203) {

    $id_reporte = $_POST['id_reporte'];
    $fecha = $_POST['fecha'];
    $desc_solucion = $_POST['desc_solucion'];
    $tecnico = $_POST['tecnico'];

    $sql = "
        update reportes
        set fecha_solucion='$fecha', desc_solucion='$desc_solucion', estado_reporte='SOLUCIONADO'
        where id_reporte='$id_reporte' and tecnico='$tecnico'
    ";

    $query = $mysqli->query($sql);

    if($query === TRUE) {
        echo json_encode(array("codigo" => "OK", "mensaje" => "Se actualizo el reporte del tecnico exitosamente."));
    } else {
        echo json_encode(array("codigo" => "ERROR", "mensaje" => "No se pudo actualizar el reporte, intentalo mas tarde o contacta a un administrador."));
    }

} else if($accion == 204) {

    $tecnico = $_POST['tecnico'];
    //$tecnico = "tecnico";

    $sql = "
        select * 
        from reportes 
        where estado_reporte='SOLUCIONADO'
        and tecnico='$tecnico'
    ";

    $query = $mysqli->query($sql);

    $data = array();
    while($res = $query->fetch_assoc()) {
        $data[] = $res;
    }

    echo json_encode(array("codigo" => "OK", "Reportes" => $data));

} else if($accion == 205) {

    $id_reporte = $_POST['id_reporte'];
    $usuario = $_POST['usuario'];
    $tecnico = $_POST['tecnico'];

    $sql = "";

    if(empty($id_reporte) && empty($usuario)) {

        echo json_encode(array("codigo" => "ERROR", "mensaje" => "No se puede realizar la busqueda."));

    } else {
        if(empty($usuario)) {
        
        $sql = "
            select *
            from reportes
            where id_reporte='$id_reporte'
            and tecnico='$tecnico'
        ";

        } else if(empty($id_reporte)) {

            $sql = "
                select *
                from reportes
                where usuario='$usuario'
                and tecnico='$tecnico'
            ";

        }

        $query = $mysqli->query($sql);

        $data = array();
        while($res = $query->fetch_assoc()) {
            $data[] = $res;
        }

        echo json_encode(array("codigo" => "OK", "Reportes" => $data));

    }

} else if($accion == 300) {

    $sql = "
        select *
        from usuarios
        where permiso != 'Administrador'
        order by permiso asc
    ";

    $query = $mysqli->query($sql);

        $data = array();
        while($res = $query->fetch_assoc()) {
            $data[] = $res;
        }

        echo json_encode(array("codigo" => "OK", "Usuarios" => $data));

} else if($accion == 301) {

    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];
    $permiso = $_POST["permiso"];
    $estado = $_POST["estado"];

    $sql = "
        update usuarios
        set contrasena='$contrasena', permiso='$permiso', estado='$estado'
        where usuario='$usuario'
    ";

    $query = $mysqli->query($sql);

    if($query === TRUE) {
        echo json_encode(array("codigo" => "OK", "mensaje" => "Se actualizo la información exitosamente."));
    } else {
        echo json_encode(array("codigo" => "ERROR", "mensaje" => "No se pudo actualizar la información, intentalo mas tarde o contacta a un administrador."));
    }

} else if($accion == 302){ 

    $nombreUsuario = $_POST["nombreUsuario"];
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];
    $permiso = $_POST["permiso"];
    $estado = $_POST["estado"];

    $sql = "
        insert into usuarios (usuario, contrasena, nombre, permiso, estado)
        values('$usuario', '$contrasena', '$nombreUsuario', '$permiso', '$estado');
    ";

    $query = $mysqli->query($sql);

    if($query === TRUE) {
        echo json_encode(array("codigo" => "OK", "mensaje" => "Se agrego la información exitosamente."));
    } else {
        echo json_encode(array("codigo" => "ERROR", "mensaje" => "No se pudo agregar la información, intentalo mas tarde o contacta a un administrador."));
    }

} else if($accion == 303){ 

    $sql_fallas = "
        select estado_reporte
         from reportes
         where estado_reporte='FALLA'
    ";

    $sql_reparaciones = "
        select estado_reporte
         from reportes
         where estado_reporte='REPARACION'
    ";

    $sql_solucionados = "
        select estado_reporte
         from reportes
         where estado_reporte='SOLUCIONADO'
    ";

    $data = array();
    $data2 = array();

    $query_fallas = $mysqli->query($sql_fallas);
    $query_reparaciones = $mysqli->query($sql_reparaciones);
    $query_solucionados = $mysqli->query($sql_solucionados);

    $data['Total Fallas'] = $query_fallas->num_rows;
    $data['Total Reparaciones'] = $query_reparaciones->num_rows;
    $data['Total Solucionados'] = $query_solucionados->num_rows;

    $sql2 = "
        SELECT
        usuarios.usuario as Tecnico,
        SUM(IF(reportes.estado_reporte='REPARACION', 1, 0)) AS Reparaciones,
        SUM(IF(reportes.estado_reporte='SOLUCIONADO', 1, 0)) AS Solucionados
        FROM reportes
        INNER JOIN usuarios ON usuarios.usuario=reportes.tecnico
        GROUP BY usuarios.usuario
    ";

    $query2 = $mysqli->query($sql2);

        $data2 = array();
        while($res = $query2->fetch_assoc()) {
            $data2[] = $res;
        }


    echo json_encode(array("codigo" => "OK", "Reportes" => $data, "Tecnicos" => $data2));
}


/*
EJEMPLO CONSULTA SQL PARA SACAR REPORTES POR TECNICO

SELECT
usuarios.usuario as TECNICOS,
SUM(IF(reportes.estado_reporte='REPARACION', 1, 0)) AS REPARACIONES,
SUM(IF(reportes.estado_reporte='SOLUCIONADO', 1, 0)) AS SOLUCIONADOS
FROM reportes
INNER JOIN usuarios ON usuarios.usuario=reportes.tecnico
GROUP BY usuarios.usuario

*/