<?php
define('BASEPATH', dirname(__DIR__) . '/');
define('BASEURL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(BASEPATH))));


require '../../librerias/globales.php';

require_once '../../modelo/Paciente.php';
$objmod = new Paciente();
$result = $objmod->getPacienteAll();

$result_mun = $objmod->getMunicipio();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="<?php echo _ruta_librerias_css . _css_estilos; ?>"/>

        <script src="<?php echo _ruta_librerias_js . _js_jquery; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_alphanumeric; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_dataTable; ?>" type="text/javascript"></script>
        <script src="<?php echo _ruta_librerias_js . _js_alerts; ?>" type="text/javascript"></script>  
        <script src="<?php echo _ruta_librerias_js . _js_librerias; ?>" type="text/javascript"></script>

        <script src="librerias/js/formatearfecha.js" type="text/javascript"></script>  
        <script src="librerias/script/paciente.js" type="text/javascript"></script>  

        <script type="text/javascript">
            //var patron = new Array(2, 2, 4);
        </script>        
    </head>
    <body>
        <div class="div_borde" >
            <table width="556" border="0" align="center">
                <tr>
                    <td height="21" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td width="29"><img src="imagenes/sistema/pacientes.png"  alt="Pacientes"/></td>
                    <td width="511">Paciente</td>
                </tr>
                <tr>
                    <td colspan="2"><hr/></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <form name="frmpaciente" id="frmpaciente" method="POST" enctype="multipart/form-data">
                            <table width="675" align="center" >
                                <tr>
                                    <td width="78" height="37">Cedula:</td>
                                    <td width="289">
                                        <input style="" type="text" id="cedula_p" name="cedula_p"  value="" maxlength="9"/>
                                        <span class="obligatorio">*</span>
                                    </td>
                                    <td width="58" height="37">Nombre:</td>
                                    <td width="251">
                                        <input type="text" id="nombre" name="nombre" value="" maxlength="20"/>
                                        <span class="obligatorio">*</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="78" height="37">Apellido:</td>
                                    <td width="289">
                                        <input type="text" id="apellido" name="apellido" value="" maxlength="20"/>
                                        <span class="obligatorio">*</span>
                                    </td>
                                    <td width="58" height="37">Telefono:</td>
                                    <td width="251">
                                        <input type="text" id="telefono" name="telefono" value="" maxlength="11"/>
                                        <span class="obligatorio">*</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="78" height="37">Fech. Naci.:</td>
                                    <td width="289">
                                        <input  type="text" id="fecha_nacimiento" name="fecha_nacimiento" value="" maxlength="10" onkeyup="this.value = formateafecha(this.value);"/>
                                        <span class="obligatorio">*</span>
                                    </td>
                                    <td width="58" height="37">Celular:</td>
                                    <td width="251">
                                        <input type="text" id="celular" name="celular" value="" maxlength="11"/>
                                        <span class="obligatorio">*</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="78" height="37">Municipio:</td>
                                    <td><select style="width: 199px;" name="municipio" id="municipio">
                                            <option value="0">Seleccione</option>
                                            <?php
                                            for ($i = 0; $i < count($result_mun); $i++) {
                                                ?>
                                                <option style="font-size: 10px;" value="<?php echo $result_mun[$i]['codigo_municipio']; ?>"><?php echo $result_mun[$i]['municipio'] ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="obligatorio">*</span>
                                    </td>
                                    <td width="58" height="37">Sector:</td>
                                    <td><select style="width: 199px;" name="sector" id="sector">
                                            <option value="0">Seleccione</option>
                                        </select>
                                        <span class="obligatorio">*</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="37">Direcci&oacute;n:</td>
                                    <td colspan="3">
                                        <textarea id="direccion" name="direccion" rows="2" cols="64"> </textarea>
                                        <span class="obligatorio">*</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="33" colspan="4" align="right">
                                        <span style="color: #ff0000;margin-left">Campo Obligatorio *</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="26" colspan="4" align="center">
                                        <input id="btnaccion" name="btnaccion" type="button" value="Agregar" />
                                        &nbsp;
                                        <input id="btnlimpiar" name="btnlimpiar" type="button" value="Limpiar" />
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
                                        <td><?php echo $result[$i]['cedula_p']; ?></td>
                                        <td><?php echo $result[$i]['nombres']; ?></td>
                                        <td><?php echo $result[$i]['fecha']; ?></td>
                                        <td><?php echo $result[$i]['telefono']; ?></td>
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
                    <td colspan="2" align="center">&nbsp;</td>
                </tr>
            </table>
        </div>
    </body>
</html>