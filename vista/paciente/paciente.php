<?php
session_start();
define('BASEPATH', dirname(__DIR__) . '/');
define('BASEURL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(BASEPATH))));

require_once '../../librerias/globales.php';
require_once '../../modelo/Paciente.php';
require_once '../../modelo/Seguridad.php';
$seguridad = new Seguridad();
if (isset($_GET['modulo'])) {
    $seguridad->
            url($_SERVER['SCRIPT_FILENAME'], $_GET['modulo']);
}

$objmod = new Paciente();
$result = $objmod->getPacienteAll();

$result_mun = $objmod->getMunicipio();
$result_tel = $objmod->getCodLocal();
$result_cel = $objmod->getCodCelular();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_estilos; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_select2; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_select2_bootstrap; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . 'standalone.css' ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . 'skin1.css' ?>"/>
        <script src="<?php echo _ruta_librerias_js . _js_jquery; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js ?>jquery.tools.js" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_dataTable; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2_es; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_tooltip; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_validarcampos; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_script_js . 'paciente.js' ?>" type="text/javascript"></script>
        <style type="text/css">
            ul#cod_local,ul#cod_cel{
                min-width:50px !important;
                width: 50px !important;

            }
            ul#cod_local > li > span,ul#cod_cel > li > span{
                text-align:center !important;
                padding: 2px !important;
            }
        </style>
    </head>
    <body>
        <div class="panel panel-default" style="margin: auto;height: auto;">
            <div class="panel-heading" style="font-weight: bold;font-size: 12px;">Agregar Paciente</div>
            <div class="panel-body">
                <table width="681" border="0" align="center">
                    <tr>
                        <td width="675" align="center">
                            <form name="frmpaciente" id="frmpaciente" method="post" enctype="multipart/form-data">
                                <table width="676" align="center">
                                    <tr>
                                        <td width="63" height="34" align="left">Cedula:</td>
                                        <td width="211">
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
                                            </div>
                                        </td>
                                        <td width="96">
                                            <img style="cursor: pointer" id="imcedula" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                        <td width="59" height="34" align="left">Nombre:</td>
                                        <td width="199">
                                            <div id="div_nombre" style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="nombre" name="nombre" value="" maxlength="20" />
                                            </div>
                                        </td>
                                        <td width="20">
                                            <img style="cursor: pointer" id="imnombre" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="63" height="34" align="left">Apellido:</td>
                                        <td width="211">
                                            <div id="div_apellido" style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="apellido" name="apellido" value="" maxlength="20" />
                                            </div>
                                        </td>
                                        <td width="96">
                                            <img style="cursor: pointer" id="imcedula2" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                        <td width="59" height="34" align="left">Telefono:</td>
                                        <td width="199">
                                            <div id="div_telefono" class="input-group">
                                                <div class="input-group-btn">
                                                    <button id="btn_codlocal" type="button" class="btn btn-default dropdown-toggle input-sm" data-toggle="dropdown">
                                                        Cod
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul id="cod_local" class="dropdown-menu">
                                                        <li>
                                                            <span id="0">Cod</span>
                                                        </li>
                                                        <?php
                                                        for ($i = 0; $i < count($result_tel); $i++) {
                                                            ?>
                                                            <li>
                                                                <span id="<?php echo $result_tel[$i]['cod_telefono']; ?>">
                                                                    0<?php echo $result_tel[$i]['codigo']; ?>
                                                                </span>
                                                            </li>
                                                            <?php
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                                <input type="hidden" id="hcod_telefono" name="hcod_telefono" />
                                                <input readonly id="cod_telefono" style="position: relative;top:8px;width: 40px;height:29px;background-color: transparent;border: none;padding-left: 2px;" maxlength="2"/>
                                                <input style="margin-top:-30px;padding-left: 25px;" type="text" class="form-control input-sm" id="telefono" name="telefono" value="" maxlength="7" />
                                            </div>
                                        </td>
                                        <td width="20">
                                            <img style="cursor: pointer" id="imcedula4" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="63" height="48" align="left">Fech. Naci.:</td>
                                        <td width="199">
                                            <div id="div_fecha" style="margin-top: 10px;" class="form-group">
                                                <input type="text" class="form-control input-sm" id="fecha_nacimiento" name="fecha_nacimiento" value="" maxlength="7" style="width: 220px;"/>
                                            </div>
                                        </td>
                                        <td width="96">
                                            <img style="cursor: pointer" id="imcedula7" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                        <td width="59" height="48" align="left">Celular:</td>
                                        <td width="199">
                                            <div id="div_celular" class="input-group">
                                                <div class="input-group-btn">
                                                    <button id="btn_codcel" type="button" class="btn btn-default dropdown-toggle input-sm" data-toggle="dropdown">
                                                        Cod
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul id="cod_cel" class="dropdown-menu">
                                                        <li>
                                                            <span id="0">Cod</span>
                                                        </li>
                                                        <?php
                                                        for ($i = 0; $i < count($result_cel); $i++) {
                                                            ?>
                                                            <li>
                                                                <span id="<?php echo $result_cel[$i]['cod_telefono']; ?>
                                                                      ">0
                                                                          <?php echo $result_cel[$i]['codigo']; ?>
                                                                </span>
                                                            </li>
                                                            <?php
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                                <input type="hidden" id="hcod_celular" name="hcod_celular" />
                                                <input readonly id="cod_celular" style="position: relative;top:8px;width: 40px;height:29px;background-color: transparent;border: none;padding-left: 2px;" maxlength="2"/>
                                                <input style="margin-top:-30px;padding-left: 25px;" type="text" class="form-control input-sm" id="celular" name="celular" value="" maxlength="7" />
                                            </div>
                                        </td>
                                        <td width="20">
                                            <img style="cursor: pointer" id="imcedula5" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="63" height="58" align="left">Municipio:</td>
                                        <td>
                                            <div id="div_municipio" style="margin-top: 10px;" class="form-group">
                                                <select name="municipio" id="municipio" class="form-control select2">
                                                    <option value="0">Seleccione</option>
                                                    <?php
                                                    for ($i = 0; $i < count($result_mun); $i++) {
                                                        ?>
                                                        <option style="font-size: 10px;" value="<?php echo $result_mun[$i]['codigo_municipio']; ?>">
                                                            <?php echo $result_mun[$i]['municipio'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td width="96">
                                            <img style="cursor: pointer" id="imcedula8" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                        <td width="59" height="58" align="left">Sector:</td>
                                        <td>
                                            <div id="div_sector" style="margin-top: 10px;" class="form-group">
                                                <select name="sector" id="sector" class="form-control select2">
                                                    <option value="0">Seleccione</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td width="20">
                                            <img style="cursor: pointer" id="imcedula6" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="63" height="49" align="left">Direcci&oacute;n:</td>
                                        <td colspan="4">
                                            <div id="div_direccion" class="form-group">
                                                <textarea class="form-control" id="direccion" name="direccion" rows="2" cols="95"></textarea>
                                            </div>
                                        </td>
                                        <td width="20">
                                            <img style="cursor: pointer" id="imcedula9" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  colspan="9" align="right">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td  colspan="6" align="center">
                                            <div id="botones">
                                                <input class="btn btn-default" id="btnaccion" name="btnaccion" type="button" value="Agregar" />
                                                <input class="btn btn-default" id="btnlimpiar" name="btnlimpiar" type="button" value="Limpiar" />
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <table width="660" border="0" cellspacing="1" id="tabla" class="dataTable" style="margin: auto">
                                <thead>
                                    <tr>
                                        <th>Cedula</th>
                                        <th>Nombres</th>
                                        <th>Fech. Naci.</th>
                                        <th>Telefono</th>
                                        <th>Modificar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    for ($i = 0; $i < count($result); $i++) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $result[$i]['cedula_p']; ?></td>
                                            <td>
                                                <?php echo $result[$i]['nombres']; ?></td>
                                            <td>
                                                <?php echo $result[$i]['fecha']; ?></td>
                                            <td>
                                                <?php echo $result[$i]['telefono']; ?></td>
                                            <td>
                                                <img class="modificar"  title="Modificar" style="cursor: pointer" src="<?php echo _img_datatable . _img_datatable_modificar ?>" width="18" height="18" alt="Modificar"/>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>