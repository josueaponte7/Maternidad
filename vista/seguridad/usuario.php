<?php
session_start();
define('BASEPATH', dirname(__DIR__) . '/');
define('BASEURL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(BASEPATH))));

$_SESSION['url'] = 'vista/seguridad/usuario.php';
if (isset($_GET['modulo'])) {
    $_SESSION['s_modulo'] = $_GET['modulo'];
}

require '../../librerias/globales.php';

require_once '../../modelo/Autenticar.php';
$obj           = new Autenticar();
$result_perfil = $obj->getPerfil();
$result        = $obj->getusuariosAll();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_estilos; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_select2; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_select2_bootstrap; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap_switch; ?>"/>

        <script src="<?php echo _ruta_librerias_js . _js_jquery; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_dataTable; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2_es; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_tooltip; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_bootstrap_switch; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_validarcampos; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_script_js . 'usuario.js' ?>" type="text/javascript"></script>
    </head>
    <body>

        <div class="panel panel-default" style="width : 90%;margin: auto;height: auto;position: relative; top:25px;">
            <div class="panel-heading" style="font-weight: bold;font-size: 12px;">Datos del Usuario</div>
            <div class="panel-body">
                <table width="681" border="0" align="center">
                    <tr>
                        <td width="675" align="center">
                            <form name="frmusuario" id="frmusuario" method="post" enctype="multipart/form-data">
                                <table width="676" align="center">
                                    <tr>
                                        <td width="80" height="40" align="left">Usuario:</td>
                                        <td width="235">
                                            <div id="div_usuario" style="margin-top: 10px" class="form-group">
                                                <input type="text" class="form-control input-sm" id="usuario" name="usuario" value="" maxlength="20"/>
                                            </div>
                                        </td>
                                        <td width="59"><span class="obligatorio">*</span></td>

                                        <td width="38" height="40" align="left">Clave:</td>
                                        <td width="221">
                                            <div id="div_clave" style="margin-top: 10px" class="form-group">
                                                <input type="password" class="form-control input-sm" id="clave" name="clave"  value="" maxlength="20"/>
                                            </div>
                                        </td>
                                        <td width="15"><span class="obligatorio">*</span></td>
                                    </tr>
                                    <tr>
                                        <td width="80" height="40" align="left">Repetir Clave:</td>
                                        <td width="235">
                                            <div id="div_repclave" style="margin-top: 10px" class="form-group">
                                                <input type="password" class="form-control " id="repclave" name="repclave" value="" maxlength="20"/>
                                            </div>
                                        </td>
                                        <td width="59"><span class="obligatorio">*</span></td>

                                        <td width="38" height="40" align="left">Perfil:</td>
                                        <td width="221">
                                            <div id="div_perfil" style="margin-top: 10px;" class="form-group">
                                                <select id="perfil" name="perfil" class="form-control select2">
                                                    <option value="0">Seleccione</option>
                                                    <?php
                                                    for ($i = 0; $i < count($result_perfil); $i++) {
                                                        ?>
                                                        <option value="<?php echo $result_perfil[$i]['codigo_perfil'] ?>"><?php echo $result_perfil[$i]['perfil'] ?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td width="15"><span class="obligatorio">*</span></td>
                                    </tr>
                                    <tr>
                                        <td width="80" height="40" align="left">Estatus:</td>
                                        <td width="235">
                                            <div style="width: 250px;" id="swestatus" class="make-switch" data-on-label="Activo" data-off-label="Inactivo" data-on="success" data-off="danger">
                                                <input type="checkbox" checked="checked">
                                                <input type="hidden" id="estatus" name="estatus" value="TRUE" />
                                            </div>
                                        </td>
                                        <td width="59"><span class="obligatorio">*</span></td>
                                    </tr>

                                    <tr>
                                      <td  colspan="6" align="right"><span style="color: #ff0000;margin-left">Campo Obligatorio *</span></td>
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
                            <table id="tabla_usuarios" border="0" cellspacing="1"  class="dataTable" >
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Perfil</th>
                                        <th>Estatus</th>
                                        <th>Fecha</th>
                                        <th>Modificar</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    for ($i = 0; $i < count($result); $i++) {
                                        ?>
                                        <tr>
                                            <td><?php echo $result[$i]['usuario']; ?></td>
                                            <td><?php echo $result[$i]['perfil']; ?></td>
                                            <td><?php echo $result[$i]['estatus']; ?></td>
                                            <td><?php echo $result[$i]['fecha']; ?></td>
                                            <td>
                                                <img class="modificar"  title="Modificar" style="cursor: pointer" src="<?php echo _img_datatable . _img_datatable_modificar ?>" width="18" height="18" alt="Modificar"/>
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
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>