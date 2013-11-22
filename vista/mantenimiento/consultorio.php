<?php
session_start();
define('BASEPATH', dirname(__DIR__) . '/');
define('BASEURL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(BASEPATH))));

require_once '../../modelo/Seguridad.php';
$seguridad = new Seguridad();
if (isset($_GET['modulo'])) {
    $seguridad->url($_SERVER['SCRIPT_FILENAME'], $_GET['modulo']);
}

require_once '../../librerias/globales.php';
require_once '../../modelo/Consultorio.php';

$objmod             = new Consultorio();
$result_especiaidad = $objmod->getEspecialidad();
$result_turno       = $objmod->getTurno();

$result = $objmod->getConsultorioAll();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_estilos; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_select2; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_pretty_checkable; ?>"/>

        <script src="<?php echo _ruta_librerias_js . _js_jquery; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_dataTable; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_tooltip; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2_es; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_validarcampos; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_pretty_checkable; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_script_js . 'consultorio.js' ?>" type="text/javascript"></script>
    </head>
    <body>
        <div class="panel panel-default" style="width : 90%;margin: auto;height: auto;position: relative; top:25px;">
            <div class="panel-heading" style="font-weight: bold;font-size: 12px;">Registro de Consultorio</div>
            <div class="panel-body">
                <table width="787" border="0" align="center">
                    <tr>
                        <td align="center">
                            <form name="frmconsultorio" id="frmconsultorio" method="post" enctype="multipart/form-data">
                                <table width="734" border="0">
                                    <tr>
                                        <td width="73" height="40" align="left">Consultorio:</td>
                                        <td width="253">
                                            <div id="div_consultorio" style="margin-top: 10px;width:252px;" class="form-group">
                                                <input type="text" class="form-control input-sm" id="consultorio" name="consultorio" />
                                            </div>
                                        </td>
                                        <td width="45">
                                            <img style="cursor: pointer" id="imgconsultorio" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                        <td width="79">Especialidad:</td>
                                        <td width="253">
                                            <select  style="width: 253px;" id="especialidad" name="especialidad">
                                                <option value="0">Seleccione</option>
                                                <?php
                                                for ($i = 0; $i < count($result_especiaidad); $i++) {
                                                    ?>
                                                    <option value="<?php echo $result_especiaidad[$i]['cod_especialidad'] ?>"><?php echo $result_especiaidad[$i]['especialidad'] ?></option>
                                                <?php }
                                                ?>
                                            </select>
                                        </td>
                                        <td width="8">
                                            <img style="cursor: pointer" id="imgespecialidad" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Turno:</td>
                                        <td>
                                            <?php
                                            for ($i = 0; $i < count($result_turno); $i++) {
                                                if ($result_turno[$i]['cod_turno'] == 1) {
                                                    $color = 'green';
                                                } else {
                                                    $color = 'red';
                                                }
                                                ?>
                                                <input type="checkbox" data-color="<?php echo $color; ?>" value="<?php echo $result_turno[$i]['cod_turno']; ?>" data-label="<?php echo $result_turno[$i]['turno'] ?>" class="turno" name="turno[]" id="<?php echo $result_turno[$i]['cod_turno'] ?>" >
                                            <?php }
                                            ?>
                                        </td>
                                        <td>
                                            <img style="cursor: pointer" id="imgturno" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" align="center">
                                            <div id="botones">
                                                <input class="btn btn-default" id="btnaccion" name="btnaccion"   type="button" value="Agregar" />
                                                <input class="btn btn-default" id="btnlimpiar" name="btnlimpiar" type="button" value="Limpiar" />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" align="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" align="center">
                                            <table style="width:100%" border="0" class="dataTable" id="tabla">
                                                <thead>
                                                    <tr>
                                                        <th width="75">Consultorio</th>
                                                        <th width="159">Especialidad</th>
                                                        <th width="77">Turno </th>
                                                        <th width="137">Modificar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    for ($i = 0; $i < count($result); $i++) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $result[$i]['consultorio']; ?></td>
                                                            <td><?php echo $result[$i]['especialidad']; ?></td>
                                                            <td><?php echo $result[$i]['turno']; ?></td>
                                                            <td>
                                                                <img id="<?php echo $result[$i]['num_consultorio']; ?>" class="modificar"  title="Modificar" style="cursor: pointer" src="<?php echo _img_datatable . _img_datatable_modificar ?>" width="18" height="18" alt="Modificar"/>
                                                            </td>
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