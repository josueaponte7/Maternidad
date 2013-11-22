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

require_once '../../modelo/Modulo.php';
require_once '../../modelo/SubModulo.php';
$objmod    = new Modulo();
$resul_mod = $objmod->getModuloAll();

$objsub    = new SubModulo();
$resul_sub = $objsub->getSubModuloAll();
$clase     = 'modificar_disabled';
$imagen    = _img_datatable . _img_datatable_modificar;
$title     = '';
$display   = '';
?>

<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_select2; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_estilos; ?>"/>

        <script src="<?php echo _ruta_librerias_js . _js_jquery; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_tooltip; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2_es; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_dataTable; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_validarcampos; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_script_js . 'modulo.js' ?>" type="text/javascript"></script>
        <style>
            .modificar{
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <!-- Inicio Modulo -->

        <div class="panel panel-default" id="divmodulo" style="width : 90%;margin: auto;height: auto;position: relative; top:25px;">
            <div class="panel-heading" style="font-weight: bold;font-size: 12px;">Registro de Modulo</div>
            <div class="panel-body">
                <table width="679" border="0" align="center">
                    <tr>
                        <td align="center">
                            <form name="frmmodulo" id="frmmodulo" method="post" enctype="multipart/form-data">
                                <table width="610" align="center">
                                    <tr>
                                        <td width="184" height="40" align="right">Modulo:</td>
                                        <td width="225">
                                            <div id="div_modulo" style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="modulo" name="modulo" value="" maxlength="20" />
                                            </div>
                                        </td>
                                        <td width="185">
                                            <img style="cursor: pointer" id="img_modulo" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  colspan="3" align="center">
                                            <div id="div_mensaje" class="" style="padding: 5px;display: none;width: 300px;height:30px;"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  colspan="3" align="center">
                                            <div id="botones">
                                                <input class="btn btn-default" id="btnaccion" name="btnaccion" type="button" value="Agregar" />
                                                <input style="display: none" class="btn btn-primary" id="btnlistar" name="btnlistar" type="button" value="SubModulos"/>
                                                <input class="btn btn-default" id="btnlimpiar" name="btnlimpiar" type="button" value="Limpiar" />
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </td>
                    </tr>
                    <tr><!-- DataTable-->
                        <td align="center">
                            <table style="width:100%;" border="0" align="center" cellspacing="1" class="dataTable" id="tabla_modulo" >
                                <thead>
                                    <tr>
                                        <th width="58">Codigo</th>
                                        <th width="64">Modulo</th>
                                        <th width="81">Modificar</th>
                                        <th width="131">Eliminar</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    for ($i = 0; $i < count($resul_mod); $i++) {
                                        ?>
                                        <tr>
                                            <td class="toltip-modulo"><?php echo $resul_mod[$i]['cod_modulo']; ?></td>
                                            <td class="toltip-modulo"><?php echo $resul_mod[$i]['modulo']; ?></td>
                                            <td style="display:<?php echo $display ?>">
                                                <img class="modificar"  title="Modificar" style="cursor: pointer" src="<?php echo $imagen ?>" width="18" height="18" alt="Modificar"/>
                                            </td>
                                            <td>
                                                <img class="eliminar"  title="Eliminar" style="cursor: pointer" src="<?php echo _img_datatable . _img_datatable_eliminar ?>" width="18" height="18"  alt="Eliminar"/>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </td>
                    </tr><!-- DataTable-->
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </div>
        </div><!-- Fin Modulo-->
        <!-- Inicio Sub Modulos -->

        <div class="panel panel-default" id="divsubmodulo" style="width : 90%;margin: auto;height: auto;position: relative; top:25px; display: none">
            <div class="panel-heading" style="font-weight: bold;font-size: 12px;">Registro de SubModulo</div>
            <div class="panel-body">
                <table width="679" border="0" align="center">
                    <tr>
                        <td align="center">
                            <form name="frmsubmodulo" id="frmsubmodulo" method="post" enctype="multipart/form-data">
                                <table width="328" align="center">
                                    <tr>
                                        <td width="82" height="40">Modulo:</td>
                                        <td width="235">
                                            <select  style="width: 230px;" name="nommodulo" id="nommodulo">
                                                <option value="0">Seleccione</option>
                                                <?php
                                                for ($i = 0; $i < count($resul_mod); $i++) {
                                                    ?>
                                                    <option style="font-size: 10px;" value="<?php echo  $resul_mod[$i]['cod_modulo']; ?>"><?php echo $resul_mod[$i]['modulo']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td width="117">
                                            <img style="cursor: pointer" id="img_nommodulo" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="82" height="40">SubModulo:</td>
                                        <td width="235">
                                            <div id="div_submodulo" style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="submodulo" name="submodulo"   value="" maxlength="50" />
                                            </div>
                                        </td>
                                        <td width="117">
                                            <img style="cursor: pointer" id="img_submodulo" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/>
                                        </td>
                                    </tr>
                                    <tr>
                                      <td height="40">Ruta:</td>
                                      <td>
                                      <div id="div_ruta" style="margin-top: 10px" class="form-group">
                                          <input type="text" class="form-control input-sm" id="ruta" name="ruta" maxlength="50"  value="" />
                                      </div></td>
                                      <td>
                                      <img style="cursor: pointer" id="img_ruta" src="../../imagenes/img_info.png" width="15" height="15" alt="img_info"/></td>
                                  </tr>
                                    <tr>
                                        <td  colspan="3" align="right">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td  colspan="3" align="center">
                                            <div id="botones">
                                                <input class="btn btn-default" id="btnaccionsub"   name="btnaccionsub"   type="button" value="Agregar" />
                                                <input class="btn btn-default" id="btnlimpiarsub"  name="btnlimpiarsub"  type="button" value="Limpiar" />
                                                <input class="btn btn-default" id="btnrestablecer" name="btnrestablecer" type="button" value="Restablecer" />                          
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <table style="width:100%;" border="0" align="center" cellspacing="1" class="dataTable" id="tabla_submodulo" >
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Modulo</th>
                                        <th>SubModulo</th>
                                        <th>Modificar</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody>

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
        <!-- Fin SubModulo -->
    </body>
</html>