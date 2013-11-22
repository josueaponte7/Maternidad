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
require_once '../../modelo/Perfil.php';

$objmod        = new Perfil();
$result_perfil = $objmod->getPerfil();
//$result        = $objmod->getPerfilPrivilegio();
$result_priv   = $objmod->getPrivilegioAll();
$result_mod    = $objmod->getModuloAll();
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_boostrap; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_estilos; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_select2; ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_pretty_checkable; ?>"/>

        <script src="<?php echo _ruta_librerias_js . _js_jquery; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_dataTable; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_select2_es; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_pretty_checkable; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_script_js . 'perfil.js' ?>" type="text/javascript"></script>
    </head>
    <body>
        <div class="panel panel-default" id="divperfil" style="width : 90%;margin: auto;height: auto;position: relative; top:25px;">
            <div class="panel-heading" style="font-weight: bold;font-size: 12px;">Registro de Perfil</div>
            <div class="panel-body">
                <table width="679" border="0" align="center">
                    <tr>
                        <td align="center">
                            <form name="frmperfil" id="frmperfil" method="post" enctype="multipart/form-data">
                                <table width="610" align="center">
                                    <tr>
                                        <td width="184" height="40" align="right">Perfil:</td>
                                        <td width="225">
                                            <div style="margin-top: 10px" class="form-group">
                                                <input class="form-control input-sm" style="text-transform: capitalize" type="text" id="perfil" name="perfil" value="" maxlength="20" />
                                            </div>
                                        </td>
                                        <td width="185"><span class="obligatorio">*</span></td>
                                    </tr>
                                    <tr>
                                        <td  colspan="3" align="right"><span style="color: #ff0000;margin-left">Campo Obligatorio *</span></td>
                                    </tr>
                                    <tr>
                                        <td  colspan="3" align="center">
                                            <div id="botones">
                                                <input class="btn btn-default" id="btnaccion"  name="btnaccion"  type="button" value="Agregar" />
                                                <input class="btn btn-default" id="btnlimpiar" name="btnlimpiar" type="button" value="Limpiar" />
                                                <input class="btn btn-default" id="btnlistar" name="btnlistar" type="button" disabled="disabled" value="Privilegios" />
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <table  align="center" cellspacing="1" class="dataTable"  id="tabla_perfil" >
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Perfil</th>
                                        <th>Modificar</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    for ($i = 0; $i < count($result_perfil); $i++) {
                                        ?>
                                        <tr>
                                            <td title="Click para ver Privilegios"><?php echo $result_perfil[$i]['codigo_perfil']; ?></td>
                                            <td title="Click para ver Privilegios"><?php echo $result_perfil[$i]['perfil']; ?></td>
                                            <td>
                                                <img class="modificar"  title="Modificar" style="cursor: pointer" src="<?php echo _img_datatable . _img_datatable_modificar ?>" alt="Modificar"/>
                                            </td>
                                            <td>
                                                <img class="eliminar"  title="Eliminar" style="cursor: pointer" src="<?php echo _img_datatable . _img_datatable_eliminar ?>"  alt="Eliminar"/>
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
        <div class="panel panel-default" id="privilegios" style="width : 90%;margin: auto;height: auto; position: relative; top:25px;display: none">
            <div class="panel-heading" style="font-weight: bold;font-size: 12px;">Perfiles de Usuarios</div>
            <div class="panel-body">
                <table width="679" border="0" align="center">
                    <tr>
                        <td align="center">
                            <form name="frmprivilegio" id="frmprivilegio" method="post" enctype="multipart/form-data">
                                <table width="408" align="center">
                                    <tr>
                                        <td width="93" height="40">Perfil:</td>
                                        <td width="327">
                                            <div style="margin-top: 15px;width: 290px;">
                                                <span style="font-size: 15px;" class="label label-default" id="nom_perfil"></span>
                                            </div>
                                        </td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td  width="93" height="40">Modulos:</td>
                                        <td width="327">
                                            <select style="width: 290px;height: 25px;" id="modulo" name="modulo">
                                                <option value="0">Seleccione</option>
                                                <?php
                                                for ($i = 0; $i < count($result_mod); $i++) {
                                                    ?>
                                                    <option value="<?php echo $result_mod[$i]['cod_modulo'] ?>"><?php echo $result_mod[$i]['modulo'] ?></option>
                                                <?php }
                                                ?>
                                            </select>
                                        </td>
                                        <td width="93"><span class="obligatorio">*</span></td>
                                    </tr>
                                    <tr>
                                        <td width="93" height="40">SubModulos:</td>
                                        <td width="327">
                                            <select style="width: 290px;height: 25px" id="sub_modulo" name="sub_modulo">
                                                <option value='0'>Seleccione</option>
                                            </select>
                                        </td>
                                        <td><span class="obligatorio">*</span></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">Privilegios:</td>
                                        <td>
                                            <?php
                                            for ($i = 0; $i < count($result_priv); $i++) {
                                                ?>
                                                <input type="checkbox" data-color="<?php echo $result_priv[$i]['tipo'] ?>" data-label="<?php echo $result_priv[$i]['privilegio'] ?>" class="privilegio" name="privilegio[]" id="privilegio" value="<?php echo $result_priv[$i]['codigo_privilegio']; ?>">
                                            <?php }
                                            ?>
                                        </td>
                                        <td><span class="obligatorio">*</span></td>
                                    </tr>
                                    <tr>
                                        <td  colspan="3" align="right"><span style="color: #ff0000;margin-left">Campo Obligatorio *</span></td>
                                    </tr>
                                    <tr>
                                        <td  colspan="3" align="center">
                                            <div id="botones">
                                                <input class="btn btn-default"  id="btnaccpriv"  name="btnaccpriv"  type="button" value="Agregar" />
                                                <input class="btn btn-default"  id="btnlimpriv"  name="btnlimpriv"  type="button" value="Limpiar" />
                                                <input class="btn btn-default"  id="btnrestablecer"  name="btnrestablecer"  type="button" value="Restablecer" />
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <table  align="center" cellspacing="1" class="dataTable" id="tabla_privilegios">
                                <thead>
                                    <tr>
                                        <th>Modulo</th>
                                        <th>SubModulo</th>
                                        <th>Privilegios</th>
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
    </body>
</html>