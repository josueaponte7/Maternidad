<?php
session_start();
define('BASEPATH', dirname(__DIR__) . '/');
define('BASEURL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(BASEPATH))));

require_once '../../modelo/Seguridad.php';

$seguridad = new Seguridad();
if (isset($_GET['modulo'])) {
    $seguridad->
url($_SERVER['SCRIPT_FILENAME'], $_GET['modulo']);
}


require '../../librerias/globales.php';

require_once '../../modelo/Asignar.php';
$objmod = new Asignar();
//$result      = $objmod->getAsignarAll();

$result_cons = $objmod->getConsultorio();
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap; ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_estilos; ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_select2; ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css ?>standalone.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css ?>skin1.css"/>
    <script src="<?php echo _ruta_librerias_js . _js_jquery; ?>" type="text/javascript"></script>
    <script src="<?php echo _ruta_librerias_js . _js_bootstrap; ?>" type="text/javascript"></script>
    <script src="<?php echo _ruta_librerias_js ?>jquery.tools.js" type="text/javascript"></script>
    <script src="<?php echo _ruta_librerias_js . _js_dataTable; ?>" type="text/javascript"></script>
    <script src="<?php echo _ruta_librerias_js . _js_select2; ?>" type="text/javascript"></script>
    <script src="<?php echo _ruta_librerias_js . _js_select2_es; ?>" type="text/javascript"></script>
    <script src="<?php echo _ruta_librerias_js . _js_librerias; ?>" type="text/javascript"></script>
    <script src="<?php echo _ruta_librerias_js . _js_validarcampos; ?>" type="text/javascript"></script>
    <script src="<?php echo _ruta_librerias_script_js . 'asignar.js' ?>" type="text/javascript"></script>
    <style type="text/css">
            table#consultorio_turno{
                background-color: #C9C9C9;
                border:1px solid #e5eff8;
                border-collapse:collapse;
                display: none;
                width:85%
            }
            table#consultorio_turno thead th{
                background:#B5DDF4;
                text-align:center;
                color:#000000;
                border: 1px solid #e5eff8;
                width:225px;
            }
            table#consultorio_turno td{
                color:#000000;
                border:1px solid #e5eff8;
                text-align:center;
                background:#FFFFFF;
                width:225px;
            }
            #consultorio_turno tr{
                cursor: pointer;
            }
            #tabla_asignar_length{
                display: none;
            }
        </style>
</head>
<body>
    <div class="panel panel-default" style="width : 100%;margin: auto;height:auto;padding-bottom:20px;">
        <div class="panel-heading" style="font-weight: bold;font-size: 12px;">Asignación de Citas</div>
        <div class="panel-body">
            <form name="frmasignar" id="frmasignar" method="post" enctype="multipart/form-data">
                <table width="783" height="453" align="center">
                    <tr>
                        <td width="27" height="40" align="left">&nbsp;</td>
                        <td width="73" align="left">Cedula:</td>
                        <td width="235">
                            <div id="div_cedula" class="input-group">
                                <div class="input-group-btn">
                                    <button style="" id="btn_nac" type="button" class="btn btn-default dropdown-toggle input-sm" data-toggle="dropdown">
                                        N
                                        <span class="caret"></span>
                                    </button>
                                    <ul id="nacionalidad" class="dropdown-menu">
                                        <li>
                                            <span id="N">NACIONALIDAD</span>
                                        </li>
                                        <li>
                                            <span id="V">VENEZOLANO</span>
                                        </li>
                                        <li>
                                            <span id="E">EXTRANJERO</span>
                                        </li>
                                    </ul>
                                </div>
                                <input type="hidden" id="hnac" name="hnac" />
                                <input readonly id="text_nac" style="font-size: 11px;position: relative;top:8px;width: 40px;height:29px;background-color: transparent;border: none;padding-left: 2px;" maxlength="2"/>
                                <input type="text" class="form-control input-sm" id="cedula_p" name="cedula_p"  value="" maxlength="9" style="margin-top:-30px;padding-left: 13px;"/>
                                <span class="input-group-btn ">
                                    <input id="btnbuscar" type="button" class="btn btn-primary input-sm" value="Buscar"/>
                                </span>
                            </div>
                        </td>
                        <td width="70">
                            <img style="cursor: pointer" id="imgsector1" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                        </td>
                        <td width="75" height="40" align="left">Nombre:</td>
                        <td width="223">
                            <div style="margin-top: 10px" class="form-group">
                                <input type="text" class="form-control input-sm" id="nombre" name="nombre"  disabled="disabled" maxlength="20" />
                            </div>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="27" height="40" align="left">&nbsp;</td>
                        <td width="73" height="40" align="left">Apellido:</td>
                        <td width="235">
                            <div style="margin-top: 10px" class="form-group">
                                <input type="text" class="form-control input-sm" id="apellido" name="apellido" disabled="disabled" maxlength="50" />
                            </div>
                        </td>
                        <td width="70">&nbsp;</td>
                        <td width="75" height="40" align="left">Telefono:</td>
                        <td width="223">
                            <div style="margin-top: 10px" class="form-group">
                                <input type="text" class="form-control input-sm" id="telefono" name="telefono" disabled="disabled"  maxlength="50" />
                            </div>
                        </td>
                        <td width="48">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="27" height="40" align="left">&nbsp;</td>
                        <td width="73" height="40" align="left">Consultorio:</td>
                        <td>
                            <div style="margin-top: 10px" class="form-group">
                                <select style="width: 235px;" name="num_consultorio" id="num_consultorio">
                                    <option value="0">Seleccione</option>
                                    <?php
                                        for ($i = 0; $i < count($result_cons); $i++) {
                                            ?>
                                    <option value="<?php echo $result_cons[$i]['num_consultorio'] ?>"><?php echo $result_cons[$i]['consultorio'] ?></option>
                                    <?php
                                        }
                                        ?>
                                </select>
                            </div>
                        </td>
                        <td width="70">
                            <img style="cursor: pointer" id="imgsector2" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                        </td>
                        <td width="75" height="40" align="left">Fecha de Cita:</td>
                        <td width="223">
                            <div id="div_fecha" style="margin-top: 10px" class="form-group">
                                <input class="form-control input-sm" style="color: #000000;width:235px;" type="text" id="fecha" name="fecha" value="" />
                            </div>
                        </td>
                        <td width="48">
                            <img style="cursor: pointer" id="imgsector3" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                        </td>
                    </tr>
                    <tr id="tr_turno" style="display:none">
                        <td height="21" align="right">&nbsp;</td>
                        <td height="40" align="left">Medico:</td>
                        <td>
                            <div style="margin-top: 10px" class="form-group">
                                <input type="text" class="form-control input-sm" id="medico" name="medico" disabled="disabled" maxlength="50" />
                            </div>
                        </td>
                        <td height="21" align="right">&nbsp;</td>
                        <td height="40" align="left">Horario:</td>
                        <td>
                            <div style="margin-top: 10px" class="form-group">
                                <input type="text" class="form-control input-sm" id="turno" name="turno" disabled="disabled" maxlength="50" />
                                <input type="hidden" id="hcod_turno" name="hcod_turno" />
                                <input type="hidden" id="cod_consu_horario" name="cod_consu_horario" />
                                <input type="hidden" id="modifcar_cita" name="modifcar_cita" value="0"/>
                                <input type="hidden" id="dias" name="dias" value=""/>
                            </div>
                        </td>
                        <td height="21" align="right">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="7" height="26" align="center">
                            <table align="center" border="1"  id="consultorio_turno">
                                <thead>
                                    <tr>
                                        <th>Medico</th>
                                        <th>Turno</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <br/>
                            <br/>
                            <span id="mensaje_tabla" class="alert alert-danger" style="display:none;font-weight:bold"></span>
                            <br/>
                        </td>
                    </tr>
                    <tr>
                        <td height="31"  colspan="7" align="center">&nbsp;</td>
                    </tr>
                    <tr>
                        <td  colspan="7" align="center">
                            <div id="botones">
                                <input class="btn btn-default" id="btnaccion" name="btnaccion" type="button" value="Asignar" disabled="disabled" />
                                <input class="btn btn-default" id="btnlimpiar" name="btnlimpiar" type="button" value="Limpiar" />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td  colspan="7" align="center">
                            <div style="margin:auto;width:95%">
                                <table  id="tabla_asignar" border="0" cellspacing="1" class="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Fecha Cita</th>
                                            <th>Medico</th>
                                            <th>Consultorio</th>
                                            <th>Observación</th>
                                            <th>Modificar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</body>
</html>