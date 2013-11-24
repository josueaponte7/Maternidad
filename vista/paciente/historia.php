<?php
session_start();
define('BASEPATH', dirname(__DIR__) . '/');
define('BASEURL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(BASEPATH))));

require_once '../../librerias/globales.php';
require_once '../../modelo/Historia.php';
require_once '../../modelo/Seguridad.php';
$seguridad = new Seguridad();
if (isset($_GET['modulo'])) {
    $seguridad->url($_SERVER['SCRIPT_FILENAME'], $_GET['modulo']);
}

$objmod = new Historia();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_estilos; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css ?>standalone.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css ?>skin1.css"/>

        <script src="<?php echo _ruta_librerias_js . _js_jquery; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_tooltip; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js ?>jquery.tools.js" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_dataTable; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_validarcampos; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_script_js . 'historia.js' ?>" type="text/javascript"></script>

    </head>
    <body>
        <div class="panel panel-default" style="margin: auto;height: auto;">
            <div class="panel-heading" style="font-weight: bold;font-size: 12px;">Historia Medica</div>
            <div class="panel-body">
                <table width="679" border="0" align="center">
                    <tr>
                        <td align="center">
                            <form name="frmhistoria" id="frmhistoria" method="post" enctype="multipart/form-data">
                                <table width="707" align="center">
                                    <tr>
                                        <td height="34" align="left">Cedula:</td>
                                        <td>
                                            <div id="div_cedula" class="input-group">
                                                <div class="input-group-btn">
                                                    <button style="" id="btn_nac" type="button" class="btn btn-default dropdown-toggle input-sm" data-toggle="dropdown">N
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul id="nacionalidad" class="dropdown-menu">
                                                        <li><span id="N">NACIONALIDAD</span></li>
                                                        <li><span id="V">VENEZOLANO</span></li>
                                                        <li><span id="E">EXTRANJERO</span></li>
                                                    </ul>
                                                </div>
                                                <input type="hidden" id="hnac" name="hnac" />
                                                <input id="text_nac" style="font-size: 11px;position: relative;top:8px;width: 40px;height:29px;background-color: transparent;border: none;padding-left: 2px;" value="" maxlength="2" readonly/>
                                                <input type="text" class="form-control input-sm" id="cedula_p" name="cedula_p"  value="" maxlength="9" style="margin-top:-30px;padding-left: 13px;"/>
                                                <span class="input-group-btn ">
                                                    <input id="btnbuscar" type="button" class="btn btn-primary input-sm" value="Buscar"/>
                                                </span>
                                            </div>
                                        </td>
                                        <td width="49">
                                            <img style="cursor: pointer" id="imgsector1" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                        <td width="54" height="34" align="left">Historia:</td>
                                        <td width="265">
                                            <div style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="historia" name="historia" value="" disabled="disabled" maxlength="20"/>
                                                <input type="hidden" id="hhistoria" name="hhistoria" value="" />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="96" height="34" align="left">Nombre:</td>
                                        <td width="266">
                                            <div style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="nombre" name="nombre" value="" disabled="disabled" maxlength="20" />
                                            </div>
                                        </td>
                                        <td width="49">&nbsp;</td>
                                        <td width="54" height="34" align="left">Apellido:</td>
                                        <td width="265">
                                            <div style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="apellido" name="apellido" value="" disabled="disabled" maxlength="20"/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="96" height="34" align="left">Fech. Naci.:</td>
                                        <td width="266">
                                            <div style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="fecha_nacimiento" name="fecha_nacimiento" value="" disabled="disabled" maxlength="20"/>
                                            </div>
                                        </td>
                                        <td width="49">&nbsp;</td>
                                        <td width="54" height="34" align="left">Edad:</td>
                                        <td width="265">
                                            <div style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="edad" name="edad" value="" disabled="disabled" maxlength="20"/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                      <td height="34" align="left">Tama&ntilde;o:</td>
                                      <td>
                                          <div style="margin-top: 10px" class="form-group">
                                              <input style="width:260px;" type="text" class="form-control input-sm" id="tamano" name="tamano" value="" maxlength="10"/>
                                          </div>
                                      </td>
                                      <td>&nbsp;</td>
                                      <td height="34" align="left">Peso:</td>
                                      <td><div style="margin-top: 10px;" class="form-group">
                                        <input style="width:260px;" type="text" class="form-control input-sm" id="peso" name="peso" value="" maxlength="10"/>
                                      </div>
                                        <img style="cursor: pointer;float:right;margin-top:-40px;;margin-right:-18px;;" id="imgsector3" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/></td>
                                    </tr>
                                    <tr>
                                      <td height="34" align="left">Tensi&oacute;n:</td>
                                      <td>
                                          <div style="margin-top: 10px" class="form-group">
                                              <input style="width:260px;" type="text" class="form-control input-sm" id="tension" name="tension" value="" maxlength="30"/>
                                          </div>
                                      </td>
                                      <td>&nbsp;</td>
                                      <td height="34" align="left">FPP:</td>
                                      <td>
                                          <div style="margin-top: 10px;" class="form-group">
                                              <input style="width:260px;" type="text" class="form-control input-sm" id="fpp" name="fpp" value="" maxlength="10"/>
                                          </div>
                                          <img style="cursor: pointer;float:right;margin-top:-40px;;margin-right:-18px;;" id="imgsector2" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                      </td>
                                    </tr>
                                    <tr>
                                        <td width="96" height="34" align="left">FUR:</td>
                                        <td width="266">
                                            <div style="margin-top: 10px" class="form-group">
                                                <input style="width:260px;" type="text" class="form-control input-sm" id="fur" name="fur" value="" maxlength="10"/>
                                            </div>
                                        </td>
                                        <td width="49">&nbsp;</td>
                                        <td width="54" height="34" align="left">&nbsp;</td>
                                        <td width="265">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td width="96" height="40" align="left">Lugar de Control:</td>
                                        <td colspan="4">
                                            <textarea class="form-control" name="lugar_control" id="lugar_control" cols="50" rows="2"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="96" height="37" align="left">Diagnostico:</td>
                                        <td colspan="4">
                                            <textarea class="form-control" name="diagnostico" id="diagnostico" rows="2" cols="50" ></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="96" height="37" align="left">Observaci&oacute;n:</td>
                                        <td colspan="4">
                                            <textarea class="form-control" name="observacion" id="observacion" rows="2" cols="50"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  colspan="5" align="right">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td  colspan="5" align="center">
                                            <div id="botones">
                                                <input class="btn btn-default" id="btnaccion" name="btnaccion" type="button" value="Agregar" disabled="disabled" />
                                                <input class="btn btn-default" id="btnlimpiar" name="btnlimpiar" type="button" value="Limpiar" />
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>