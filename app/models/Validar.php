<?php
/**
 * Created by PhpStorm
 * User: CESARJOSE39
 * Date: 09/10/2020
 * Time: 12:25
 */
//Clase para Limpieza y Tratamiento de Datos
//Creada Por: César José Ruiz
class Validar{
    public function __construct(){}
    //Función única para validar la integridad de un parametro
    //$parametro = Nombre del parametro
    //$forma_envio = Si fue enviado por GET, POST o FILES
    //$vacio = true para validar si el parametro esta vacio, false para ignorar esa validacion
    //$ok = valor de la anterior validacion (si es false, no se valida nada)
    //$tamanho_maximo = Numero de caracteres maximo del parametro
    //$tipo de parametro = Para indicar si es texto, solo_texto, numero, email, fecha o archivo
    //$tipo_validacion = Subindicador del tipo de parametro (ej: archivo tiene tipo de archivos)
    public function validar_parametro($parametro, $forma_envio, $vacio, $ok, $tamanho_maximo, $tipo_parametro, $tipo_validacion = 0){
        //Verificamos si las validaciones siguen en pie o están caidas
        if($ok){
            //Primero hay que validar si está declarado el $parametro como $_POST o $_GET
            switch ($forma_envio){
                //De ahí, ver si la variable existe y si esta declarada
                case 'GET':
                    if(isset($_GET[$parametro])){
                        //Luego hay que limpiar el $parametro según el $tipo_parametro especificado
                        //Finalmente, validar si $parametro coincide con el las validaciones de $vacio, $tamanho_maximo y $tipo_parametro
                        //($tipo_validacion se usa en caso de que el $tipo_parametro tenga varias validaciones, como en el caso de fecha)
                        switch ($tipo_parametro){
                            case 'texto':
                                $_GET[$parametro] = $this->limpiar_string($_GET[$parametro]);
                                return $this->validar_texto($_GET[$parametro],$vacio,$tamanho_maximo);
                            case 'solo_texto':
                                $_GET[$parametro] = $this->limpiar_string($_GET[$parametro]);
                                return $this->validar_solo_texto($_GET[$parametro],$vacio,$tamanho_maximo);
                            case 'email':
                                $_GET[$parametro] = $this->limpiar_string($_GET[$parametro]);
                                return $this->validar_email($_GET[$parametro],$vacio,$tamanho_maximo);
                            case 'fecha':
                                $_GET[$parametro] = $this->limpiar_string($_GET[$parametro]);
                                return $this->validar_fechas($_GET[$parametro],$vacio,$tamanho_maximo, $tipo_validacion);
                            case 'numero':
                                $_GET[$parametro] = $this->limpiar_int($_GET[$parametro]);
                                return $this->validar_numeros($_GET[$parametro],$vacio,$tamanho_maximo);
                        }
                    } else {
                        return false;
                    }
                    break;
                case 'POST':
                    if(isset($_POST[$parametro])){
                        //Luego hay que limpiar el $parametro según el $tipo_parametro especificado
                        //Finalmente, validar si $parametro coincide con el las validaciones de $vacio, $tamanho_maximo y $tipo_parametro
                        //($tipo_validacion se usa en caso de que el $tipo_parametro tenga varias validaciones, como en el caso de fecha)
                        switch ($tipo_parametro){
                            case 'texto':
                                $_POST[$parametro] = $this->limpiar_string($_POST[$parametro]);
                                return $this->validar_texto($_POST[$parametro],$vacio,$tamanho_maximo);
                            case 'solo_texto':
                                $_POST[$parametro] = $this->limpiar_string($_POST[$parametro]);
                                return $this->validar_solo_texto($_POST[$parametro],$vacio,$tamanho_maximo);
                            case 'email':
                                $_POST[$parametro] = $this->limpiar_string($_POST[$parametro]);
                                return $this->validar_email($_POST[$parametro],$vacio,$tamanho_maximo);
                            case 'fecha':
                                $_POST[$parametro] = $this->limpiar_string($_POST[$parametro]);
                                return $this->validar_fechas($_POST[$parametro],$vacio,$tamanho_maximo,$tipo_validacion);
                            case 'numero':
                                $_POST[$parametro] = $this->limpiar_int($_POST[$parametro]);
                                return $this->validar_numeros($_POST[$parametro],$vacio,$tamanho_maximo);
                        }
                    } else {
                        return false;
                    }
                    break;
                case 'FILES':
                    if(isset($_FILES[$parametro])){
                        //Validar si $parametro coincide con el las validaciones de $vacio, $tamanho_maximo y $tipo_parametro
                        //($tipo_validacion se usa en caso de que el $tipo_parametro tenga varias validaciones, como en el caso de fecha)
                        return $this->validar_archivo($parametro, $vacio, $tipo_validacion);
                    } else {
                        return false;
                    }
                default:
                    return false;
                    break;
            }
        } else {
            return false;
        }
    }
    //Limpieza de Variables Tipo String para evitar ataques XSS
    function limpiar_string($value){
        try{
            $value = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
        } catch (Exception $e){
            $value = "";
        }
        return $value;
    }
    //Limpieza de Variables Tipo Int para evitar ataques XSS
    function limpiar_int($value){
        try{
            $value = filter_var(filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS), FILTER_SANITIZE_NUMBER_INT);
        } catch (Exception $e){
            $value = "";
        }
        return $value;
    }
    //Funciones para Validar la Correcta Estructura de una Variable

    //validar_texto (Valida si el dato cumple con la estructura indicada)
    //$valor = La cadena de texto a validar
    //$vacio = Indica si se debe validar si la cadena está vacia o no
    //$tamaho = Indica la longitud máxima que debe tener la variable
    function validar_texto($valor, $vacio, $tamaho){
        try{
            if($vacio){
                //Si $vacio es true, validará el valor de la cadena
                if(!empty($valor)){
                    //Acá validamos que la cadena tenga el tamaño correcto
                    if(strlen($valor) <= $tamaho){
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                //Acá validamos que la cadena tenga el tamaño correcto
                if(strlen($valor) <= $tamaho){
                    return true;
                } else {
                    return false;
                }
            }
        } catch (Exception $e){
            return false;
        }
    }
    //validar_solo_texto (Valida si el dato cumple con la estructura indicada y Que Sólo Tenga Texto)
    //$valor = La cadena de texto a validar
    //$vacio = Indica si se debe validar si la cadena está vacia o no
    //$tamanho = Indica la longitud máxima que debe tener la variable
    function validar_solo_texto($valor, $vacio, $tamanho){
        try{
            if($vacio){
                //Si $valor es true, validará que la cadena no este vacia
                if(!empty($valor)){
                    //Validamos que $valor tenga el tamaño correcto
                    if(strlen($valor) <= $tamanho){
                        //$patron = '/^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/';
                        //Función que valida que sólo se haya texto.
                        $patron = '/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]*$/';
                        if(preg_match($patron,$valor)){
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                //Validamos que $valor tenga el tamaño correcto
                if(strlen($valor) <= $tamanho){
                    return true;
                } else {
                    return false;
                }
            }
        } catch (Exception $e){
            return false;
        }
    }
    //validar_fechas (Valida que el texto ingresado tenga el forma de fecha correcto)
    //$valor = La cadena de texto a validar
    //$vacio = Indica si se debe validar si la cadena está vacia o no
    //$tamanho = Indica la longitud máxima que debe tener la variable
    //$tipo = Indica el tipo de fecha a validar (1: Fecha, 2: Fecha y Hora)
    function validar_fechas($valor, $vacio, $tamanho, $tipo){
        try{
            if($vacio){
                //Si $vacio es true, se validará que $valor no este $vacio
                if(!empty($valor)){
                    //Validamos que $valor tenga el tamaño correcto
                    //yyyy-mm-dd = 10 caracteres
                    //yyyy-mm-dd hh:mm:ss = 19 caracteres
                    if(strlen($valor) <= $tamanho){
                        //$patron = '/^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/';
                        switch ($tipo){
                            case 'fecha':
                                //Fecha: dd/mm/yyyy o dd-mm-yyyy
                                //$patron = '/^([0-2][0-9]|3[0-1])(\/|-)(0[1-9]|1[0-2])\2(\d{4})$/';
                                $patron = '/^(19|20)\d\d[\-\/.](0[1-9]|1[012])[\-\/.](0[1-9]|[12][0-9]|3[01])$/';
                                break;
                            case 'fecha-hora':
                                //Fecha, hora, minutos y segundos: dd/mm/yyyy hh:mm:ss o dd-mm-yyyy hh:mm:ss
                                //$patron = '/^([0-2][0-9]|3[0-1])(\/|-)(0[1-9]|1[0-2])\2(\d{4})(\s)([0-1][0-9]|2[0-3])(:)([0-5][0-9])(:)([0-5][0-9])$/';
                                $patron = '/^(19|20)\d\d[\-\/.](0[1-9]|1[012])[\-\/.](0[1-9]|[12][0-9]|3[01])(\s)([0-1][0-9]|2[0-3])(:)([0-5][0-9])(:)([0-5][0-9])$/';
                                break;
                            default:
                                return false;
                        }
                        if(preg_match($patron,$valor)){
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                //Validamos que $valor tenga el tamaño correcto
                if(strlen($valor) <= $tamanho){
                    return true;
                } else {
                    return false;
                }
            }
        } catch (Exception $e){
            return false;
        }
    }
    //validar_numeros (Valida que el texto ingresado sea un número)
    //$valor = La cadena de texto a validar
    //$vacio = Indica si se debe validar si la cadena está vacia o no
    //$tamanho = Indica la longitud máxima que debe tener la variable
    function validar_numeros($valor, $vacio, $tamanho){
        try{
            if($vacio){
                //Si $vacio es true, validará que $valor no este vacio
                if($valor != ""){
                    //Validamos que $valor tenga el tamaño correcto
                    if(strlen($valor) <= $tamanho){
                        //Validamos que $valor sea numerico
                        return $this->es_numero($valor);
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                //Validamos que $valor tenga el tamaño correcto
                if(strlen($valor) <= $tamanho){
                    if(strlen($valor) > 0){
                        //Validamos que $valor sea numerico
                        return is_numeric($valor);
                    } else {
                        return true;
                    }
                } else {
                    return false;
                }
            }
        } catch (Exception $e){
            return false;
        }
    }
    //validar_email (Valida que el texto ingresado sea un correo)
    //$valor = La cadena de texto a validar
    //$vacio = Indica si se debe validar si la cadena está vacia o no
    //$tamanho = Indica la longitud máxima que debe tener la variable
    function validar_email($valor, $vacio, $tamanho){
        try{
            if($vacio){
                //Si $vacio es true, validará que $valor no este vacio
                if(!empty($valor)){
                    //Validamos que $valor tenga el tamaño correcto
                    if(strlen($valor) <= $tamanho){
                        //Validamos que $valor tenga el valor correcto para email
                        if(filter_var($valor, FILTER_VALIDATE_EMAIL) !== false){
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                //Validamos que $valor tenga el tamaño correcto
                if(strlen($valor) <= $tamanho){
                    //Validamos que $valor tenga el valor correcto para email
                    if(strlen($valor) > 0){
                        if(filter_var($valor, FILTER_VALIDATE_EMAIL) !== false){
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return true;
                    }
                } else {
                    return false;
                }
            }
        } catch (Exception $e){
            return false;
        }
    }
    //validar_archivo (Valida que el archivo ingresado sea el formato deseado)
    //$valor = El archivo a validar
    //$tipo = Tipo de Archivo (pdf, word, excel, jpg, png)
    //Se puede modificar la función para que acepte más valores
    function validar_archivo($valor, $vacio, $tipo){
        try{
            //Validamos que el archivo tenga tamaño
            if($_FILES[$valor]['size'] > 0){
                $ext = strtolower(pathinfo($_FILES[$valor]['name'], PATHINFO_EXTENSION));
                //Valimos que el archivo tenga la correcta extension
                foreach ($tipo as $t){
                    switch ($t){
                        case 'pdf':
                            //Archivos PDF
                            if($ext == 'pdf'){ return true; } break;
                        case 'word':
                            //Archivos Word
                            if($ext == 'doc' || $ext == 'docx'){ return true; } break;
                        case 'excel':
                            //Archivos Excel
                            if($ext == 'xls' || $ext == 'xlsx'){ return true; } break;
                        case 'jpg':
                            //Archivos JPG
                            if($ext == 'jpg' || $ext == 'jpge'){ return true; } break;
                        case 'png':
                            //Archivos PNG
                            if($ext == 'png'){ return true; } break;
                        default:
                            return false;
                    }
                }
                return false;
            } else {
                if($vacio){
                    return false;
                } else {
                    return true;
                }
            }
        } catch (Exception $e){
            return false;
        }
    }
    //Función para validar si es un numero o no
    function es_numero($var)
    {
        if ($var == (string) (float) $var) {
            return (bool) is_numeric($var);
        }
        if ($var >= 0 && is_string($var) && !is_float($var)) {
            return (bool) ctype_digit($var);
        }
        return (bool) is_numeric($var);
    }
    function obtener_nombre_dia($day){
        switch ($day){
            case 1:
                $d = "Lunes";
                break;
            case 2:
                $d = "Martes";
                break;
            case 3:
                $d = "Miércoles";
                break;
            case 4:
                $d = "Jueves";
                break;
            case 5:
                $d = "Viernes";
                break;
            case 6:
                $d = "Sábado";
                break;
            case 7:
                $d = "Domingo";
                break;
        }
        return $d;
    }
    function obtener_nombre_mes($mes){
        switch ($mes){
            case '01':
                $mes_ = "Enero";
                break;
            case '02':
                $mes_ = "Febrero";
                break;
            case '03':
                $mes_ = "Marzo";
                break;
            case '04':
                $mes_ = "Abril";
                break;
            case '05':
                $mes_ = "Mayo";
                break;
            case '06':
                $mes_ = "Junio";
                break;
            case '07':
                $mes_ = "Julio";
                break;
            case '08':
                $mes_ = "Agosto";
                break;
            case '09':
                $mes_ = "Setiembre";
                break;
            case '10':
                $mes_ = "Octubre";
                break;
            case '11':
                $mes_ = "Noviembre";
                break;
            case '12':
                $mes_ = "Diciembre";
                break;
        }
        return $mes_;
    }
    function obtener_nombre_fecha($date,$format_given,$format_return,$language,$anno = false){
        $day="01";$mes_="Ene";$anho="1000";
        $final_date = $day." ".$mes_." ".$anho;
        if($format_given == "DateTime"){
            if($format_return=="Date"){
                $date_explode = explode('-',$date);
                $anho = $date_explode[0];
                $mes = $date_explode[1];
                if($language=="es"){
                    switch ($mes){
                        case '01':
                            $mes_ = "Ene";
                            break;
                        case '02':
                            $mes_ = "Feb";
                            break;
                        case '03':
                            $mes_ = "Mar";
                            break;
                        case '04':
                            $mes_ = "Abr";
                            break;
                        case '05':
                            $mes_ = "May";
                            break;
                        case '06':
                            $mes_ = "Jun";
                            break;
                        case '07':
                            $mes_ = "Jul";
                            break;
                        case '08':
                            $mes_ = "Ago";
                            break;
                        case '09':
                            $mes_ = "Set";
                            break;
                        case '10':
                            $mes_ = "Oct";
                            break;
                        case '11':
                            $mes_ = "Nov";
                            break;
                        case '12':
                            $mes_ = "Dic";
                            break;
                    }
                }
                $day = explode(' ',$date_explode[2]);
                $final_date = $day[0]." ".$mes_." ".$anho ;
            }elseif($format_return=="DateTime"){
                $date_explode = explode('-',$date);
                $anho = $date_explode[0];
                $mes = $date_explode[1];
                if($language=="es"){
                    switch ($mes){
                        case '01':
                            $mes_ = "Ene";
                            break;
                        case '02':
                            $mes_ = "Feb";
                            break;
                        case '03':
                            $mes_ = "Mar";
                            break;
                        case '04':
                            $mes_ = "Abr";
                            break;
                        case '05':
                            $mes_ = "May";
                            break;
                        case '06':
                            $mes_ = "Jun";
                            break;
                        case '07':
                            $mes_ = "Jul";
                            break;
                        case '08':
                            $mes_ = "Ago";
                            break;
                        case '09':
                            $mes_ = "Set";
                            break;
                        case '10':
                            $mes_ = "Oct";
                            break;
                        case '11':
                            $mes_ = "Nov";
                            break;
                        case '12':
                            $mes_ = "Dic";
                            break;
                    }
                }
                $day = explode(' ',$date_explode[2]);
                $time_explode = explode(':',$day[1]);
                ($time_explode[0]>12) ? $hour = $time_explode[0] - 12 : $hour = $time_explode[0];
                ($time_explode[0]>=12) ? $meridian = "pm" : $meridian = "am";
                $minute =$time_explode[1];
                $final_date = $day[0]." ".$mes_." ".$anho.", ". $hour . ":".$minute." ".$meridian ;
            }
        }elseif($format_given == "Date"){
            if($format_return=="Date"){
                $date_explode = explode('-',$date);
                $anho = $date_explode[0];
                $mes = $date_explode[1];
                if($language=="es"){
                    switch ($mes){
                        case '01':
                            $mes_ = "Ene";
                            break;
                        case '02':
                            $mes_ = "Feb";
                            break;
                        case '03':
                            $mes_ = "Mar";
                            break;
                        case '04':
                            $mes_ = "Abr";
                            break;
                        case '05':
                            $mes_ = "May";
                            break;
                        case '06':
                            $mes_ = "Jun";
                            break;
                        case '07':
                            $mes_ = "Jul";
                            break;
                        case '08':
                            $mes_ = "Ago";
                            break;
                        case '09':
                            $mes_ = "Set";
                            break;
                        case '10':
                            $mes_ = "Oct";
                            break;
                        case '11':
                            $mes_ = "Nov";
                            break;
                        case '12':
                            $mes_ = "Dic";
                            break;
                    }
                }
                $day = explode(' ',$date_explode[2]);
               // if(!$anho){
                    $final_date = $day[0]." ".$mes_." ".$anho ;
                //}else{
                  //  $final_date = $day[0]." ".$mes_;
                //}
            }
        }
        return $final_date;
    }
    function tiempo_entre_2_fechas($date_1, $date_2){
        try{
            $date1 = new DateTime($date_1);
            if ($date_2 == "hoy") {
                $date2 = new DateTime("now");
            } else {
                $date2 = new DateTime($date_2);
            }
            $diff = $date1->diff($date2);
            if ($diff->y !== 0) {
                $time = ($diff->y > 1) ? $diff->y . ' años ' : $diff->y . ' año ';
            } elseif ($diff->m !== 0) {
                $time = ($diff->m > 1) ? $diff->m . ' meses ' : $diff->m . ' mes ';
            } elseif ($diff->d !== 0) {
                $time = ($diff->d > 1) ? $diff->d . ' días ' : $diff->d . ' día ';
            } elseif ($diff->h !== 0) {
                $time = ($diff->h > 1) ? $diff->h . ' horas ' : $diff->h . ' hora ';
            } elseif ($diff->i !== 0) {
                $time = (($diff->days * 24) * 60) + ($diff->i) . ' minutos';
            } else {
                $time = "0 min";
            }
        } catch (Exception $e){
            $time = "no disponible";
        }

        return $time;
    }
}
