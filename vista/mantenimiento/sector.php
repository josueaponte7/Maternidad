<?php
session_start();
define('BASEPATH', dirname(__DIR__) . '/');
define('BASEURL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(BASEPATH))));

require_once '../../modelo/Seguridad.php';

$seguridad = new Seguridad();
if (isset($_GET['modulo'])) {
    $seguridad->url($_SERVER['SCRIPT_FILENAME'], $_GET['modulo']);
}

require '../../librerias/globales.php';

require_once '../../modelo/Sector.php';
$objmod = new Sector();
$result = $objmod->getSectorAll();

$result_mun = $objmod->getMunicipio();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_estilos; ?>"/>

        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_select2; ?>"/>

        <script src="<?php echo _ruta_librerias_js . _js_jquery; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_dataTable; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_tooltip; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2_es; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_validarcampos; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_script_js . 'sector.js' ?>" type="text/javascript"></script> 
        <style type="text/css">
            .error_select{
                border-color:1px solid #953B39;
                -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 6px #d59392;
                box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 6px #d59392;
            }
            a.select2-choice.error{
                border:1px solid #953B39;
            }

        </style>
    </head>

    <body>
        <div class="panel panel-default" style="width : 90%;margin: auto;height: auto;position: relative; top:25px;">
            <div class="panel-heading" style="font-weight: bold;font-size: 12px;">Registro de Sector</div>
            <div class="panel-body">
                <table width="679" border="0" align="center">
                    <tr>
                        <td align="center">
                            <form name="frmsector" id="frmsector" method="post" enctype="multipart/form-data">
                                <table width="610" align="center">
                                    <tr> 
                                        <td width="80" height="40" align="right">Municipio:</td>
                                        <td width="199">
                                            <select  style="width: 199px;" name="municipio" id="municipio">
                                                <option value="0">Seleccione</option>
                                                <?php
                                                for ($i = 0; $i < count($result_mun); $i++) {
                                                    ?>
                                                    <option style="font-size: 10px;" value="<?php echo $result_mun[$i]['codigo_municipio']; ?>"><?php echo $result_mun[$i]['municipio'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td width="33"><img style="cursor: pointer" id="imgsector1" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/></td>  
                                        <td width="62" height="40" align="right">Sector:</td>
                                        <td width="200">
                                            <div id="div_sector" style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="sector" name="sector" value="" maxlength="50" />
                                            </div>
                                        </td>
                                        <td width="8"><img style="cursor: pointer" id="imgsector" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/></td>
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
                        <td  align="center">
                            <table width="717"  border="0" cellspacing="1" class="dataTable" id="tabla">
                                <thead>
                                    <tr>
                                        <th>Municipio</th>
                                        <th>Sector</th>
                                        <th>Modificar</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    for ($i = 0; $i < count($result); $i++) {
                                        ?>
                                        <tr>
                                            <td><?php echo $result[$i]['municipio']; ?></td>
                                            <td><?php echo $result[$i]['sector']; ?></td>
                                            <td>
                                                <img id="<?php echo $result[$i]['codigo_municipio'] . ',' . $result[$i]['cod_sector']; ?>" class="modificar"  title="Modificar" style="cursor: pointer" src="<?php echo _img_datatable . _img_datatable_modificar ?>" width="18" height="18" alt="Modificar"/>
                                            </td>
                                            <td>
                                                <img id="<?php echo $result[$i]['cod_sector']; ?>" class="eliminar"  title="Eliminar" style="cursor: pointer" src="<?php echo _img_datatable . _img_datatable_eliminar ?>" width="18" height="18"  alt="Eliminar"/>
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