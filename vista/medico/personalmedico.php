<?php
session_start();
define('BASEPATH', dirname(__DIR__) . '/');
define('BASEURL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(BASEPATH))));

$_SESSION['url'] = 'vista/medico/personalmedico.php';
if (isset($_GET['modulo'])) {
    $_SESSION['s_modulo'] = $_GET['modulo'];
}

require '../../librerias/globales.php';

require_once '../../modelo/personalMedico.php';
$objmod = new personalMedico();
$result = $objmod->getPersonalMedicoAll();
$result_esp = $objmod->getEspecialidad();
$result_cod = $objmod->getCod();

?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_estilos; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_select2; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_select2_bootstrap; ?>"/>

        <script src="<?php echo _ruta_librerias_js . _js_jquery; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_dataTable; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2_es; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_tooltip; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_validarcampos; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_librerias; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_script_js .'personalmedico.js'?>" type="text/javascript"></script>
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
        <div class="panel panel-default" style="width : 90%;margin: auto;height: auto;position: relative; top:25px;">
            <div class="panel-heading" style="font-weight: bold;font-size: 12px;">Datos del Medico</div>
            <div class="panel-body">
                <table width="799" border="0" align="center">
                    <tr>
                        <td width="755" align="center">
                            <form name="frmpersonalmedico" id="frmpersonalmedico" method="post" enctype="multipart/form-data">
                                <table width="712" align="center">
                                    <tr>
                                        <td width="80" height="40" align="left">C&eacute;dula:</td>
                                        <td width="222">
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
                                                <input readonly id="text_nac" style="font-size: 11px;position: relative;top:8px;width: 40px;height:29px;background-color: transparent;border: none;padding-left: 2px;" maxlength="2"/>
                                                <input type="text" class="form-control input-sm" id="cedula_pm" name="cedula_pm"  value="" maxlength="9" style="margin-top:-30px;padding-left: 13px;"/>
                                            </div>
                                        </td>
                                        <td width="94">
                                            <img style="cursor: pointer" id="imgcedula" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                        <td width="73" height="40" align="left">Nombre:</td>
                                        <td width="204">
                                          <div id="div_nombre" style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="nombre" name="nombre"  value="" maxlength="20" />
                                            </div>
                                        </td>
                                        <td width="11">
                                            <img style="cursor: pointer" id="imgnombre" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="80" height="40" align="left">Apellido:</td>
                                        <td width="222">
                                          <div id="div_apellido" style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="apellido" name="apellido" value="" maxlength="20" />
                                            </div>
                                        </td>
                                        <td width="94">
                                            <img style="cursor: pointer" id="imgapellido" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                        <td width="73" height="40" align="left">Tel&eacute;fono:</td>
                                        <td width="204">
                                         <div id="div_telefono" class="input-group">
                                                <div class="input-group-btn">
                                                    <button id="btn_codlocal" type="button" class="btn btn-default dropdown-toggle input-sm" data-toggle="dropdown">Cod
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul id="cod_local" class="dropdown-menu">
                                                        <li><span id="0">Cod</span></li>
                                                        <?php
                                                            for ($i = 0; $i < count($result_cod); $i++) {
                                                            ?>
                                                            <li><span id="<?php echo $result_cod[$i]['cod_telefono'];?>">0<?php echo $result_cod[$i]['codigo'];?></span></li>
                                                            <?php
                                                            }
                                                        ?>
                                                    </ul>
                                                </div>
                                                <input type="hidden" id="hcod_telefono" name="hcod_telefono" />
                                                <input readonly id="cod_telefono" style="position: relative;top:8px;width: 40px;height:29px;background-color: transparent;border: none;padding-left: 2px;" maxlength="2"/>
                                                <input style="margin-top:-30px;padding-left: 30px;" type="text" class="form-control input-sm" id="telefono" name="telefono" value="" maxlength="7" />
                                            </div>
                                        </td>
                                        <td width="11">
                                            <img style="cursor: pointer" id="imgtelefono" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="80" height="40" align="left">Direcci&oacute;n:</td>
                                        <td colspan="4">
                                             <div id="div_direccion" class="form-group">
                                                 <textarea class="form-control" id="direccion" name="direccion" rows="2" cols="62" maxlength="150"></textarea>
                                            </div>
                                        </td>
                                        <td width="11">
                                            <img style="cursor: pointer" id="imgdireccion" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="80" height="40" align="left">Especialidad:</td>
                                        <td>
                                            <div id="div_cod_esp" style="margin-top: 10px;" class="form-group">
                                                <select name="cod_esp"  id="cod_esp" class="form-control select2">
                                                    <option value="0">Seleccione</option>
                                                    <?php
                                                    for ($i = 0; $i < count($result_esp); $i++) {
                                                        ?>
                                                        <option value="<?php echo $result_esp[$i]['cod_especialidad'] ?>"><?php echo $result_esp[$i]['especialidad'] ?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td width="94"><img style="cursor: pointer" id="imgespecialidad" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/></td>
                                        <td width="73" height="40" align="left">Consultorio:</td>
                                        <td>
                                            <div id="div_num_cons" style="margin-top: 10px;" class="form-group">
                                                <select name="num_cons" id="num_cons" class="form-control select2">
                                                    <option value="0">Seleccione</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td width="11"><img style="cursor: pointer" id="imgconsutorio" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/></td>
                                    </tr>
                                    <tr>
                                        <td width="80" height="40" align="left">Turno:</td>
                                        <td>
                                            <div id="div_turno" style="margin-top: 10px;" class="form-group">
                                                <select  name="turno"  id="turno" class="form-control select2">
                                                    <option value="0">Seleccione</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td width="94"><img style="cursor: pointer" id="imgturno" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
    									<td>&nbsp;</td>
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
                                    <tr>
                                      <td  colspan="6" align="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td  colspan="6" align="center">
                                      <table style="width:100%" border="0" cellspacing="1"  class="dataTable" id="tabla">
                                        <thead>
                                          <tr>
                                            <th>Cedula</th>
                                            <th>Nombres</th>
                                            <th>Especialidad</th>
                                            <th>Consultorio</th>
                                            <th>Turno</th>
                                            <th>Modificar</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <?php
                                            for ($i = 0; $i < count($result); $i++) {
                                                ?>
                                                      <tr>
                                                        <td><?php echo $result[$i]['cedula_pm']; ?></td>
                                                        <td><?php echo $result[$i]['nombres']; ?></td>
                                                        <td><?php echo $result[$i]['especialidad']; ?></td>
                                                        <td><?php echo $result[$i]['consultorio']; ?></td>
                                                        <td><?php echo $result[$i]['turno']; ?></td>
                                                        <td><img class="modificar"  title="Modificar" style="cursor: pointer" src="<?php echo _img_datatable . _img_datatable_modificar ?>" width="18" height="18" alt="Modificar"/></td>
                                                      </tr>
                                                      <?php
                                            }
                                        ?>
                                        </tbody>
                                      </table>
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