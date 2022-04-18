<?php

class Academico extends Clase_mysqli{

public $id_estudiante;

public $ano_lectivo;

public $id_docente;

public $id_asignacion;



public function __SET($atributo,$valor){

	return	$this-> $atributo= $valor ;

}

public function __construct($identificacion=""){

		if ($identificacion!=""){

				$this->id_asignacion = $identificacion;

		#$this->listar_persona();

	}

}


public function consultar_link_icono($icono){
$sql="SELECT imagen_icono FROM `iconos` WHERE id_iconos=$icono";
$datos = json_decode($this->consultar_datos($sql,true),true);
if (!empty($datos)){
return SGA_COMUN_URL."/img/png/".$datos[0]['imagen_icono'];
}else{
  return SGA_COMUN_URL."/img/png/folder-10.png";
}
/*
if (!empty($datos)){
if ($row = $consulta->fetch( PDO::FETCH_ASSOC )){
    return SGA_COMUN_URL."/img/png/".$row['imagen_icono'];
}else{
    return SGA_COMUN_URL."/img/png/folder-10.png";
}      
}else{
    return SGA_COMUN_URL."/img/png/folder-10.png";
}
*/
}


public function misestudiantes(){
  $datos_curso =$this ->mis_cursos_otros(); 
  $asignacion_hoy=($this->consultar_horario(1));
if(!empty($datos_curso)){
  if(isset($datos_curso) ){

    echo "<select onchange='listarEstudiantes();limpiar(nombrees);' class='form-control' name='asignaciones' id='asignaciones'>";

    foreach($datos_curso as $clave => $valor){

  echo '<option ';

  if(!empty($asignacion_hoy[0]['id_asignacion']) and $asignacion_hoy[0]['id_asignacion']==$valor['id_asignacion']){

    echo ' selected ';

  }

  echo ' value="'.$valor['id_asignacion'].'" style="width: 150px;white-space:pre-wrap;word-wrap: break-word;">'.$valor['nombre_materia'].' ('. $valor['nombre_categoria_curso'].')</option >';

        }   

       }          

       echo'</select>';
       
      
echo '<input autofocus onfocus="listarEstudiantes();" onkeyup="listarEstudiantes();"  type="text" class="form-control" placeholder="Buscar" id="nombrees" value=""></input><br>';

      }     
echo '<div style="height:250px;" id="contenedor"></div>';

}



public function inscripcion($asignacion){

  $sql='select  * from inscripcion where id_inscripcion="'.$asignacion.'"';

  return $datos = json_decode($this->consultar_datos($sql,true),true);

}



public function NivelEducativo($nivel=0){

  $sql ='select * from categoria_curso ';

  if($nivel<>0){

   $sql.=' where id_categoria_curso="'.$nivel.'"'; 

   $datos = json_decode($this->consultar_datos($sql,true),true);

 return json_decode($datos,true);

  }else{

return $datos = json_decode($this->consultar_datos($sql,true),true);

  }

 

}



public function notasdeclase($asignacion=1,$fechainicial='',$fechafinal=''){

  $sql='select * from edunotas where  id_asignacion="'.$asignacion.'" ';

if($fechainicial<>"" and $fechafinal<>""){

$sql.=' and fecha_nota>="'.$fechainicial.'" and fecha_nota<="'.$fechafinal.'" ';

}

  $sql.='order by fecha_nota,hora_nota desc ';

  return $datos = json_decode($this->consultar_datos($sql,true),true);

}

public function notaspendientes($estado=1){

  $sql='select * from edunotas where  fijar="'.$estado.'" order by fecha_nota,hora_nota asc ';

  return $datos = json_decode($this->consultar_datos($sql,true),true);

}





public function reporte_notas(){

  if(!empty($_POST['id_inscripcion'])){

  $sql='select * from seguimiento_es,actividad,inscripcion where

seguimiento_es.id_actividad=actividad.id_actividad and

seguimiento_es.id_inscripcion="'.$_POST['id_inscripcion'].'" and 

actividad.id_asignacion="'.$_POST['id_asignacion'].'" group by seguimiento_es.id_seguimiento';

return $datos = json_decode($this->consultar_datos($sql,true),true);

  

  }

  

}









public function componente_context_menu($id_asignacion,$nombre_materia){

$rowa['id_asignacion']=$id_asignacion;

$rowa['nombre_materia']=$nombre_materia;

$mat = $this->consultar_materia($id_asignacion);

$curso=($mat[0]->id_curso);

$categoria_curso=($mat[0]->nombre_categoria_curso);



?>

<menu id="menu_curso<?php echo $rowa['id_asignacion'] ?>" style="display:none" class="showcase">

  <command label="<?php echo $rowa['nombre_materia']; ?>" onclick="

  document.location='<?php echo SGA_CURSOS_URL ?>/curso.php?asignacion=<?php echo $rowa['id_asignacion'] ?>'">

  <hr>

  <?php @session_start(); if ($_SESSION['rol']<>"estudiante" and $_SESSION['rol']<>"acudiente" ){ ?>

  

   <command  label="Nuevo RED"  onclick="

  document.location='<?php echo SGA_RED_URL ?>/nuevo_red.php?asignacion=<?php echo $rowa['id_asignacion'] ?>'">





  <command  label="Nueva Planeación"  onclick="

  document.location='<?php echo SGA_PLANEADOR_URL ?>/index.php?asignacion=<?php echo $rowa['id_asignacion'] ?>'">

  

    <command  label="Nueva Actividad"  onclick="

  document.location='<?php echo SGA_CURSOS_URL ?>/actividad.php?asignacion=<?php echo $rowa['id_asignacion'] ?>'">

  <command label="Nueva Edunota"  onclick="

  document.location='<?php echo SGA_CURSOS_URL ?>/../cursos/edunotas.php?asignacion=<?php echo $rowa['id_asignacion'] ?>'">

  <command label="horario"  onclick="

  document.location='<?php echo SGA_CURSOS_URL ?>/../asistencia/horario.php?asignacion=<?php echo $rowa['id_asignacion'] ?>'">

  <command label="asistencia"  onclick="

  document.location='<?php echo SGA_CURSOS_URL ?>/../asistencia/index.php?asignacion=<?php echo $rowa['id_asignacion'] ?>'">

  <command label="Estudiantes del curso"  onclick="

  document.location='<?php echo SGA_CURSOS_URL ?>/estudiante_curso.php?asignacion=<?php echo $rowa['id_asignacion'] ?>'">

  <command label="Modificar curso"  onclick="

  document.location='<?php echo SGA_CURSOS_URL ?>/modificar_curso.php?asignacion=<?php echo $rowa['id_asignacion'] ?>'">

  <command label="Duplicar curso"  onclick="

  document.location='<?php echo SGA_COMUN_URL ?>/funciones.rutas.php?clonar_curso=<?php echo $rowa['id_asignacion'] ?>'">

     <?php } ?>    

<command target="_BLANK" label="Reporte Valorativo"  onclick="window.open('<?php echo SGA_REPORTES_URL ?>/informe_valorativo.php?id_asignacion=<?php echo $rowa['id_asignacion'] ?>','_blank')">

<command target="_BLANK" label="Estadisticas"  onclick="window.open('<?php echo SGA_REPORTES_URL ?>/cursos/usuarios.php?id_asignacion=<?php echo $rowa['id_asignacion'] ?>','_blank');">
<command target="_BLANK" label="Reportes"  onclick="window.open('<?php echo SGA_REPORTES_URL ?>/cursos/informe_general.php?id_asignacion=<?php echo $rowa['id_asignacion'] ?>','_blank');">

  <command target="_BLANK" label="Salir"  onclick="return 'context-menu-icon context-menu-icon-quit'"> 

</menu>

<?php

}



public function consultar_anios($estado="Inactivo"){

$sql='SELECT id_ano_lectivo FROM `ano_lectivo` where estado="'.$estado.'"';

return $datos = json_decode($this->consultar_datos($sql,true),true);



}



public function reporte_actividades($estudiantes,$asignacion){

  $html='';

  $html.='

  <div id="card">';

    $contador=0;

require '../../comun/conexion.php';  

$sql='select * from seguimiento where identificacion="'.$estudiantes.'" and grupo="'.$asignacion.'" ';

   $consulta=$mysqli->query($sql);
   $bandera=0;
  while($observacion=$consulta->fetch_assoc()){
  $contador = $contador +strlen($observacion['observaciones']);
  $html.='<p><strong>'.ucwords(utf8_decode($observacion['contenido'])).'('.Fecha::formato_fecha($observacion['fecha_asesoria']).'):</strong></p>';
if($contador>1000 and $bandera==0){
  $bandera=1;
  $html.=' <div style="page-break-after:always;"></div> '; 
}
  $html.='<p>'.trim(strip_tags($observacion['observaciones'])).'</p>';   
}
$contador=0;
return $html.='</div>';

}



public static function eventos_favoritos($id,$estrellas_array){

$estrellas = json_decode(stripslashes($estrellas_array),true);

$estrellasmias=$estrellas[$_SESSION['id_usuario']];

if ($estrellasmias>="1"){

?>

<span id="span_eve_<?php echo $id ?>" onclick="fav_eve('<?php echo $id ?>','span_eve_<?php echo $id ?>')" estado="NO" title="Favoritos <?php echo count($estrellas) ?>" class="glyphicon glyphicon-star estrella_eve"></span>

<?php }else{ ?>

<span id="span_eve_<?php echo $id ?>" onclick="fav_eve('<?php echo $id ?>','span_eve_<?php echo $id ?>')" estado="SI" title="Favoritos <?php echo count($estrellas) ?>" class="glyphicon glyphicon-star-empty estrella_eve"></span>

<?php }

}//fin function







public function consultar_docente($asignacion){

 $sql='SELECT * FROM asignacion,usuario where 

asignacion.id_docente = usuario.id_usuario and id_Asignacion= "'.$asignacion.'"';

return $datos = json_decode($this->consultar_datos($sql,true),true);

}





public function consultar_registro_asistencia($asignacion,$mifecha=""){

$sql='select * from asistencia where fecha="'.$mifecha.'" and id_materia= "'.$asignacion.'"';

return $datos = json_decode($this->consultar_datos($sql,true),true);	

}



public function consultar_horas_horario(){



$mifecha=date('Y-m-d');

$sql='select distinct  hora_inicio,hora_fin from horario,asignacion, materia where horario.id_asignacion = asignacion.id_asignacion AND asignacion.id_asignatura = materia.id_materia  ';

$sql.='and 

"'.$mifecha.'" >= fecha_inicio and  "'.$mifecha.'"<= fecha_fin';

 #$sql.=' and id_asignacion='.$asignacion;

$sql.=' order by hora_inicio asc'; 

  return $datos = json_decode($this->consultar_datos($sql,true),true); 

 }





public function consultar_horario_completo($horario=false){

$mifecha=date('Y-m-d');

  $horario=1;

#$mifecha=date('2019-09-25');

$sql='select * from horario,asignacion, materia where horario.id_asignacion = asignacion.id_asignacion AND asignacion.id_asignatura = materia.id_materia and asignacion.visible="SI" ';

$sql.='and 

"'.$mifecha.'" >= fecha_inicio and  "'.$mifecha.'"<= fecha_fin';

 #$sql.=' and id_asignacion='.$asignacion;

$sql.=' order by hora_inicio asc';

#echo $sql;

if($horario==true){

 $asistencia = json_decode($this->consultar_datos($sql,true),true); 

  if(!empty($asistencia[0]['id_asignacion'])){

$info= $this->consultar_registro_asistencia($asistencia[0]['id_asignacion'],$mifecha);



if(empty($info)){

if(!empty($asistencia)) {

  $id =$asistencia[0]['id_asignacion'];

  //header("location:asistencia/index.php?asignacion=$id");

} 

}  

}



}



  return $datos = json_decode($this->consultar_datos($sql,true),true); 

 

}





public function consultar_horario_Asignacion($asignacion="", $todos=0,$dia=1){

$mifecha=date('Y-m-d');

$sql='select * from horario,asignacion, materia where horario.id_asignacion = asignacion.id_asignacion AND asignacion.id_asignatura = materia.id_materia and 

asignacion.visible = "SI" and asignacion.institucion_educativa =  "'.$_SESSION['id_institucion'].'" ';

$sql.=' and "'.$mifecha.'" >= fecha_inicio and  "'.$mifecha.'"<= fecha_fin';

if($dia==1){

$sql.=' and horario.dia =lower ("'.FECHA::saber_dia().'")';

}

if($todos==0 and $asignacion<>""){

 $sql.=' and asignacion.id_asignacion='.$asignacion;

}

$sql.=' order by hora_inicio asc';

#echo $sql;

#print_r($datos);

  return $datos = json_decode($this->consultar_datos($sql,true),true);  

}

public function consultar_horario_simple($asignacion){

$sql='select * from horario,asignacion, materia where horario.id_asignacion = asignacion.id_asignacion AND asignacion.id_asignatura = materia.id_materia';

 $sql.=' and asignacion.id_asignacion='.$asignacion;

    return $datos = json_decode($this->consultar_datos($sql,true),true);  



}



public function consultar_horario($asistencia=0){

	$hora = date('H:i');

	$mifecha=date('Y-m-d');

   $dias =Fecha::saber_dia($mifecha);



	$sql='select * from horario,asignacion where 

  horario.id_asignacion=asignacion.id_asignacion and

"'.$mifecha.'" >= horario.fecha_inicio and  "'.$mifecha.'"<= horario.fecha_fin and 	 "'.$hora.'" >= horario.hora_inicio  and  "'.$hora.'" <= horario.hora_fin and asignacion.id_docente="'.$_SESSION['id_usuario'].'" ';

   $sql.=' and horario.dia =lower ("martes")';

        #if($horario==true){

 $asistencia = json_decode($this->consultar_datos($sql,true),true); 

 if(!empty($asistencia[0]['id_asignacion'])){

$info= $this->consultar_registro_asistencia($asistencia[0]['id_asignacion'],date('Y-m-d'));

#print_r($info);

if(empty($info)){

if(!empty($asistencia) and $asistencia==0) {

  $id =$asistencia[0]['id_asignacion'];

  header("location:asistencia/index.php?asignacion=$id");

} 

}  

}



#}

           	return $datos = json_decode($this->consultar_datos($sql,true),true);	

}



public function obtener_estructura_directorios($ruta,$usuario="guagua",$ver=1,$insertar=1,$ruta_estudiante){

$ver=1;

$academico= new academico();

    $html="";

    if (is_dir($ruta)){

        $gestor = opendir($ruta);

        $html.= "<ul>";

if ($ver==1) {         echo "<ul>";  }



$ficheros  = scandir($ruta);

if((count($ficheros))<=2){

 if(is_dir($ruta)){

  #echo $ruta;

}

}else{

  

}







        while (($archivo = readdir($gestor)) !== false)  {       

            $ruta_completa = $ruta . "/" . $archivo;



            if ($archivo != "." && $archivo != "..") {

                if (is_dir($ruta_completa)) {

$this->guardar_estructura($ruta_estudiante,$ruta_completa,$insertar,$usuario);

                        $html.= "<li>($archivo)</li>";

        if ($ver==1) {

                echo "<li>" .($archivo). "</li>";

            }  

                   $this-> obtener_estructura_directorios($ruta_completa,$usuario,$ver,$insertar,$ruta_estudiante);

                } else {

                      if ($ver==1) {  

                    echo "<li>" .($archivo)  . "</li>"; #archivp

                  }

        $this->guardar_estructura($ruta_estudiante,$ruta_completa,$insertar,$usuario);



                    $html.= "<li>($archivo)</li>";

                }

            }

        }       

        // Cierra el gestor de directorios

        $html.="</ul>";

        closedir($gestor);

                     if ($ver==1) {  

        echo "</ul>";

                  }

    } else {

        echo "No es una ruta de directorio valida<br/>";

    }

    return $html;

}



public function guardar_estructura($ruta_estudiante,$ruta_completa,$insertar,$usuario){

$newstr = str_replace($ruta_estudiante," ",$ruta_completa);

#$newstr=substr($newstr,1);

if (!empty($newstr)) {

  $newstr= mb_strtoupper($newstr,'UTF-8');

  if($insertar==1){

    if ($usuario=="guagua") {

      if($newstr<>"desktop.ini"){

  $this->insertar_evaluador(($newstr),"4",$usuario,"4");

      }

    }else{

      if($newstr<>"desktop.ini"){

      $this->insertar_evaluador_es($newstr,$usuario);

      }

    }

  

  }

}

}



public function calificador($estudiante="guagua",$punto=1,$valoracion_maxima="4"){

$arbol_bien= $this->valorar($punto);

$cada_punto = $valoracion_maxima/count($arbol_bien);

$podium = 0;

$contador=0;

foreach ($arbol_bien as $key => $carpetas) {

$datos = $this->valorar_es($estudiante,$carpetas['estructura']);



$contador+=1;

if (!empty($datos)) {

$podium =$podium +$cada_punto ;

}

else{

   // echo $contador.".No Existe ".$carpetas['estructura'].'<br>';

}

}

return $podium;

}





public function valorar($actividad,$criterio=""){

$sql='select * from evaluador_guagua where usuario="guagua" and version="4" ';

if($criterio<>""){

$sql.=' and estructura=REPLACE("'.mb_strtoupper($criterio,'UTF-8').'", " ", "")';

}else{

   $sql.=' and punto="'.$actividad.'"';

}  

$datos = json_decode($this->consultar_datos($sql,true),true);

#echo $sql;

return $datos;

}



public function valorar_es($estudiante,$criterio){

$criterio = str_replace(' ', '', $criterio);



  $sql='select * from evaluadorestudiante where  usuario="'.$estudiante.'" and REPLACE(estructura, " ", "")="'.(mb_strtoupper($criterio, 'UTF-8')).'"';

#print_r( $datos = json_decode($this->consultar_datos($sql,true),true)[0]['id_actividad']);

#echo "<pre>" ;

#echo $sql.'<br>';

$datos = json_decode($this->consultar_datos($sql,true),true);

#print_r($datos);

#echo "</pre>" ;







return $datos = json_decode($this->consultar_datos($sql,true),true);

}





public function insertar_evaluador($cadena,$punto,$usuario,$version){

$cadena=substr($cadena,1);

$cadena=substr($cadena,1);

$datos = $this->valorar($usuario,mb_strtoupper($cadena,'UTF-8'));

if (empty($datos)) {

	 $sql='INSERT INTO `evaluador_guagua`(`version`, `estructura`,`punto`,usuario) VALUES ("'.$version.'","'.mb_strtoupper($cadena,'UTF-8').'","'.$punto.'","'.$usuario.'")';

		$this->query_insertar($sql);	

  }

}



public function insertar_evaluador_es($cadena,$usuario){

  $cadena=substr($cadena,1);

  $cadena=substr($cadena,1); 

  $datos = $this->valorar_es($usuario,mb_strtoupper($cadena, 'UTF-8'));  

if (empty($datos)) {

   $sql='INSERT INTO `evaluadorestudiante`( `estructura`,usuario) VALUES ("'.mb_strtoupper($cadena, 'UTF-8').'","'.$usuario.'")';

    $this->query_insertar($sql);  

}

}



public function eliminar_horario(){

    $sql='DELETE FROM `horario` WHERE `id_asignacion`="'.$_POST['asignacion'].'"';

    $this->query_insertar($sql);

}



public function insertar_horario($dia,$key){

	$sql='INSERT INTO `horario`( `id_asignacion`, `fecha_inicio`, `hora_inicio`, `hora_fin`, `fecha_fin`,dia) VALUES ("'.$_POST['asignacion'].'","'.$_POST['fecha_inicio'].'","'.$_POST['hora_inicio'][$key].'","'.$_POST['hora_fin'][$key].'","'.$_POST['fecha_fin'].'","'.$dia.'")';

		$this->query_insertar($sql);

	}







public function fechas_asistencia($materia,$fechasol=""){

#print_r($fechasol);

	$sql='SELECT distinct(`fecha`) FROM `asistencia` WHERE `id_materia`="'.$materia.'"';

	if(!empty($fechasol)){
$sql.=" and fecha='".$fechasol."'";
	}

		return $datos = json_decode($this->consultar_datos($sql,true),true);	

}



 public function inscripcion_estudiante2($cur){

   $sql_inscripcion ='SELECT * FROM `inscripcion` where id_estudiante = "'.$_SESSION['id_usuario'].'" and fecha_inscripcion like "%'.date("Y").'%" and id_asignacion="'.$cur.'"';

 $datos = json_decode($this->consultar_datos($sql_inscripcion,true),true);

 return $datos[0]['id_inscripcion'];

 }

public function inscripcion_estudiante(){

  $sql_estudiante = "SELECT * from inscripcion,asignacion

         WHERE id_estudiante =";

if ($_SESSION['rol']=="acudiente"){

    $sql_estudiante.="'".$_SESSION['hijo']."'";

}

if ($_SESSION['rol']=="estudiante"){

 $sql_estudiante.= "'".$_SESSION['id_usuario']."'";

}

$sql_estudiante.=" and           asignacion.id_asignacion=inscripcion.id_asignacion 

              order by id_inscripcion desc limit 1  ";

              

return $datos = json_decode($this->consultar_datos($sql_estudiante,true),true);



}



function ano_estudiante(){



 $sqlp= "SELECT * FROM `ano_lectivo`";

     if ($_SESSION['rol']=="acudiente" or $_SESSION['rol']=="estudiante"){ 

        $sqlp.= ' where nombre_ano_lectivo like "'.date('Y').'" '  ; }

        $sqlp.=" order by id_ano_lectivo desc";        

return    $sqlp;

}









public function estudiantes_de_una_asignacion($nombre=''){
	$sql= ' SELECT DISTINCT * FROM inscripcion inner join usuario on inscripcion.id_estudiante = usuario.id_usuario  where ';
   if($nombre<>''){
    $sql.='  LOWER(`usuario`.`nombre`) LIKE "%'.mb_strtolower($nombre,'UTF-8').'%" and ';
   }
   $sql.=' inscripcion.id_asignacion="'.$this->id_asignacion.'" order by usuario.apellido asc ';
 	 $datos = json_decode($this->consultar_datos($sql,true),true);
return $datos;

}







public function consultar_materia($asignacion="") {
$sql='SELECT materia.nombre_materia,asignacion.id_curso,asignacion.id_asignatura,categoria_curso.nombre_categoria_curso FROM `asignacion`,
materia,categoria_curso where asignacion.id_asignatura = materia.id_materia and
 asignacion.id_categoria_curso=categoria_curso.id_categoria_curso';
if (!empty($asignacion)) {
$sql.=' and asignacion.id_Asignacion= "'.$asignacion.'"';
}
#echo $sql;
$datos = json_decode($this->consultar_datos($sql,true));
return $datos;
}



public function consultar_asistencia() {

$sql='SELECT * FROM `asistencia` ';

if (!empty($this->id_persona)) {

$sql.=' where id_estudiante= "'.$this->id_persona.'"';

}

return $datos = json_decode($this->consultar_datos($sql,true),true);



	}



public function registrar_asistencia() {

	$sql="INSERT INTO `asistencia`(`id_docente`, `id_materia`, `id_estudiante`, `asistencia`, `fecha`) VALUES ";

  foreach ($_POST['asistencia'] as $estudiante=> $asistencia) {

	$sql.=' ("'.$_POST['docente'].'","'.$_POST['asignacion'].'","'.$estudiante.'","'.$asistencia.'","'.$_POST['fecha'].'"),';

}

$sql=substr($sql,0,-1);

$insercion = $this->query_insertar($sql);

return $insercion;

}





public function leer_mensajes(){

	 $sql=' SELECT * FROM `mensaje` where leido="NO" and usuario="'.$_SESSION['id_usuario'].'" order by fecha desc';

 return $datos = json_decode($this->consultar_datos($sql,true),true);



}



public function listar_estudiantes_asignacion($asignacion,$estudiante=''){

 $sql=' SELECT * FROM `inscripcion` where id_asignacion="'.$asignacion.'"  and estado_inscripcion="Aprobado" ';

 if($estudiante<>''){

  $sql.=' and id_inscripcion="'.$estudiante.'"'; 

 }

 return $datos = json_decode($this->consultar_datos($sql,true),true);



}



public function valoraciones(){

	 $sql=' select * from seguimiento_es,inscripcion,actividad where

 actividad.id_actividad = seguimiento_es.id_actividad and

 seguimiento_es.id_inscripcion =inscripcion.id_inscripcion and  inscripcion.id_estudiante = "'.$_SESSION['id_usuario'].'" and Year(inscripcion.fecha_inscripcion) = "'.$this->ano_lectivo.'" and seguimiento_es.valoracion <> "" order by seguimiento_es.fechayhora_valoracion desc';

  $datos = json_decode($this->consultar_datos($sql,true),true);

 if (!empty($datos_valoraciones)){

            foreach ($datos_valoraciones as $key => $row) {

              echo '<a  href="'.SGA_CURSOS_URL.'/visor_actividad.php?a='.$row['id_actividad'].'"><strong> Actividad :'.$row['nombre_actividad'].', Valoración =  </strong> '.$row['valoracion'].'</a> <br/>';

            }  



                       }else{

                        echo "No hay Valoraciones";

                       }

                       

            





}

public function categoria_estudiante(){

  $sql_estudiante = "SELECT * from inscripcion,asignacion

 WHERE id_estudiante = '".$_SESSION['id_usuario']."' and

      asignacion.id_asignacion=inscripcion.id_asignacion 

      order by id_inscripcion desc limit 1  ";

    $consulta_estudiante = json_decode($this->consultar_datos($sql,true),true);

 $consulta_estudiante = $mysqli->query($sql_estudiante);

    if ($consulta_estudiante[0]['id_categoria_curso']){

       $_SESSION['id_categoria_curso'] = $row_estudiante['id_categoria_curso'];   



}

}

public function mis_cursos_estudiante(){

 $sql='select *,usuario.apellido as apellido_docente ,usuario.nombre as nombre_docente from inscripcion,asignacion,ano_lectivo,materia,usuario, categoria_curso where inscripcion.id_asignacion = asignacion.id_asignacion and asignacion.id_asignatura = materia.id_materia and asignacion.id_categoria_curso=categoria_curso.id_categoria_curso and asignacion.id_docente=usuario.id_usuario and asignacion.ano_lectivo = ano_lectivo.id_ano_lectivo

and asignacion.institucion_educativa ="'.$_SESSION['institucion'].'" and id_estudiante="'.$_SESSION['id_usuario'].'" AND ano_lectivo.nombre_ano_lectivo="'.$this->ano_lectivo.'"';

  #print_r($_SESSION);

return $datos = json_decode($this->consultar_datos($sql,true),true);

}


public function promedio_actividades($inscripcion,$asignacion){
	$sql ='SELECT avg(valoracion) as promedio FROM `actividad` left join seguimiento_es on actividad.id_actividad = seguimiento_es.id_actividad where seguimiento_es.id_inscripcion="'.$inscripcion.'" and valoracion <>"" and actividad.id_asignacion="'.$asignacion.'"';
	return $datos = json_decode($this->consultar_datos($sql,true),true);
  }



public function seguimiento_actividades($actividad,$inscripcion){

  $sql ='SELECT * FROM `actividad` left join seguimiento_es on actividad.id_actividad = seguimiento_es.id_actividad where seguimiento_es.id_inscripcion="'.$inscripcion.'" and valoracion <>"" and actividad.id_actividad="'.$actividad.'" '; 
  return $datos = json_decode($this->consultar_datos($sql,true),true);
}





public function mis_cursos_otros(){

  $mifecha=date('Y-m-d');

 $sql='Select distinct * from asignacion,materia,categoria_curso, horario where 

 asignacion.id_categoria_curso=  categoria_curso.id_categoria_curso and   

 asignacion.visible = "SI" AND 

 asignacion.id_Asignacion = horario.id_Asignacion and 

"'.$mifecha.'" >= horario.fecha_inicio and  "'.$mifecha.'"<= horario.fecha_fin and asignacion.id_asignatura = materia.id_materia and asignacion.institucion_educativa =  "'.$_SESSION['id_institucion'].'" ';
if(isset($_SESSION['id_usuario'])){
$sql.='and asignacion.id_docente ="'.$_SESSION['id_usuario'].'"';
}
$sql.=' GROUP by asignacion.id_asignacion order by 

CASE 

   WHEN dia - DAYOFWEEK(CURDATE())+1 < 0 

       THEN dia + DAYOFWEEK(CURDATE())

   ELSE

      dia - DAYOFWEEK(CURDATE())

   END



';

 #echo $sql;

return $datos = json_decode($this->consultar_datos($sql,true),true);

}



public function area(){

$sql= "SELECT * FROM area;";

return $datos = json_decode($this->consultar_datos($sql,true),true);



}



public function ano_lectivo(){

$sql ='select * from ano_lectivo'; 

return $datos = json_decode($this->consultar_datos($sql,true),true);





}



public function asistencia_genero($asignacion=false){
  $sql='
SELECT count(`genero`) as femenino,(select count(`genero`) FROM usuario,inscripcion WHERE
inscripcion.id_estudiante=usuario.id_usuario and 
 `genero` = "m"';
if($asignacion<>false){
  $sql.=' and inscripcion.id_asignacion ="'.$asignacion.'"';
}
$sql.=' ) as masculino  FROM usuario,inscripcion WHERE inscripcion.id_estudiante=usuario.id_usuario and  `genero` = "f" ';

if($asignacion<>false){

  $sql.=' and inscripcion.id_asignacion ="'.$asignacion.'"';

}

#echo $sql;

return $datos = json_decode($this->consultar_datos($sql,true),true);



}

public function informacion_horario_Asignacion(){

  $sql='select * from horario where id_asignacion='.$this->id_asignacion;

  return $datos = json_decode($this->consultar_datos($sql,true),true);

}



public function pendientes(){

  $mismateriashoy= $this->consultar_horario_Asignacion();

$cantidad_materias_hoy=count($mismateriashoy);

#require_once 'clases/fecha.Class.php';

$fecha=new Fecha();

foreach ($mismateriashoy  as $key => $value) {

echo "<pre>";

echo "<a style='color: black;text-decoration:none;' href="

.SGA_CURSOS_URL.'/curso.php?asignacion='.$value["id_asignacion"]." >";

echo $value['nombre_materia'].' (Hoy) <br> desde '.$fecha->formato_hora_corta($value['hora_inicio']).' hasta '.$fecha->formato_hora_corta($value['hora_fin']);

echo "</a>";

echo "</pre>";

}

#require_once 'clases/Actividad.Class.php';

$actividad=new actividad();  

$mismateriashoy= $actividad->actividades_pendientes_por_fecha();

if(!empty($mismateriashoy)){

foreach ($mismateriashoy  as $key => $row) {

echo "<pre>";

$persona =  new Persona($row['identificacion']);

print_r($persona->nombre);

echo "<br>";

print_r($row['contenido']);

echo "<br>";

$fecha= new Fecha();

print_r($fecha->formato_hora_corta($row['hora_inicio']));

echo " a ";

print_r($fecha->formato_hora_corta($row['hora_fin']));

echo "</pre>";

}

}

$notaspendientes= $this->notaspendientes();

foreach ($notaspendientes  as $keynota => $nota) {

  $datos_materia=$academico->consultar_materia($nota['id_asignacion']);

?>

<div class="modal fade" id="exampleModal<?php echo $nota['id_nota']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title" id="exampleModalLabel"><?php echo $datos_materia[0]->nombre_materia.'('.Fecha::formato_fecha($nota['fecha_nota']).'/'.Fecha::formato_hora($nota['hora_nota']) ?></h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <div class="modal-body">



         <div class="form-group">

            <label for="message-text" class="col-form-label"><?php echo $nota['nota'] ?>:</label>

          </div>

     

      </div>

      <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

 

      </div>

    </div>

  </div>

</div>

<?php

$idnota=$nota['id_nota'];

echo "<pre>";

echo '<p  data-toggle="modal" data-target="#exampleModal'.$idnota.'" data-whatever="@mdo" title='.$nota['nota'].'>'.(Comun::puntos_suspensivos($nota['nota'],20)).'('.$datos_materia[0]->nombre_materia.')</p>';

echo(Fecha::formato_fecha($nota['fecha_nota']));

echo "<a href=".SGA_CURSOS_URL.'/edunotas.php?estado='.$idnota."><img title='Desfijar' width='7%' src='".SGA_COMUN_URL."/img/negativo.png' ></img></a>";

echo "</pre>";

}

}





public function valoraciones_pendientes($fecha="",$limite=10){

if($fecha<>""){ $fecha=date('Y-m-d'); }

 $sql ='select * from asignacion,actividad,materia  where 

materia.id_materia = asignacion.id_asignacion and

asignacion.id_asignacion = actividad.id_asignacion and evaluable ="SI" and actividad.visible="SI" and actividad.fecha_entrega >= "'.date('Y-m-d').'" and actividad.id_asignacion in (Select asignacion.id_asignacion from inscripcion,asignacion,materia where inscripcion.id_asignacion = asignacion.id_asignacion and materia.id_materia = asignacion.id_asignacion and inscripcion.id_estudiante ="'.$_SESSION['id_usuario'].'" ';

$datos = json_decode($this->consultar_datos($sql,true),true);

if(!empty($datos)){

  return $datos ;

}else{

  return "";

}



}





}



?>