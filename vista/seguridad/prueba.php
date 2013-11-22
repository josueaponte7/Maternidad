<?php
define('BASEPATH', dirname(__DIR__) . '/');
define('BASEURL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(BASEPATH))));

require '../../librerias/globales.php';

require_once '../../modelo/Modulo.php';

$objmod = new Modulo();
$resul = $objmod->getModuloAll();

require_once '../../modelo/SubModulo.php';
$objsubmod = new SubModulo();
$resulsub = $objsubmod->getSubModuloAll();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="../../librerias/css/estilos.css"/>
        <link rel="stylesheet" type="text/css" href="../../librerias/css/librerias.css"/>
        <!--        <link rel="stylesheet" type="text/css" href="../../librerias/css/estilos.css"/>-->
        <link rel="stylesheet" type="text/css" href="../../librerias/css/dataTables.css"/>


        <script src="../../librerias/js/jquery.1.10.js" type="text/javascript"></script>

        <script src="../../librerias/js/dataTables.js" type="text/javascript"></script>
        
        <script>

            $(document).ready(function() {
                var oTable = $('#tabla_modulo').dataTable({
                    "iDisplayLength": 5,
                    "iDisplayStart": 0,
                    "sPaginationType": "full_numbers",
                    "aLengthMenu": [5, 10, 20, 30, 40, 50],
                    "oLanguage": {"sUrl": "../../librerias/js/es.txt"},
                    "aoColumns": [
                        {"sClass": "agregar center", "sWidth": "10%"},
                        {"sClass": "center", "sWidth": "40%"},
                        {"sClass": "center", "sWidth": "10%"},
                        {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
                        {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
                    ]
                });


                $('#btnaccion').click(function() {
                    var row = $('#txtmodulo').val();
                    oTable.fnUpdate('Zebra', parseInt(row), 1);
                    oTable.fnUpdate('Zebra two', parseInt(row), 2);
                    oTable.fnUpdate('Zebra tree', parseInt(row), 3);
                });


            });



            function restoreRow(oTable1, nRow) {
                var aData = oTable1.fnGetData(nRow);
                var jqTds = $('>td', nRow);
                for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                    oTable1.fnUpdate(aData[i], nRow, i, false);
                }
                oTable1.fnDraw();
            }
        </script>      
    </head>
    <body>
        <div id="divmodulo" style="border: 1px solid #d3d3d3;width : 80%;margin: auto;height: auto;position: relative; top:25px;padding-bottom: 15px;" >
            <table width="547" border="0" align="center">
                <tr>
                    <td align="center">
                        <table style="width:100%;" border="0">
                            <tr>
                                <td height="21" colspan="2">&nbsp;
                                </td>
                            </tr>
                            
                            <tr>

                                <td width="95%">
                                    <span class="titulomenu">Registro de Modulo</span>
                                </td>
                            </tr>
                            
                            <tr>
                                <td height="6" colspan="2"><hr/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                
                <tr>
                    <td align="center">
                        <form name="frmmodulo" id="frmmodulo" method="post" enctype="multipart/form-data">
                            <table width="336" align="center">
                                <tr>
                                    <td width="110" height="37">Nombre Modulo:</td>
                                    <td width="214">
                                        <input id="cod_modulo"   name="cod_modulo"   type="hidden" value="" />
                                        <input type="text" id="txtmodulo" name="txtmodulo" value="" maxlength="20"/>
                                        <span class="obligatorio">*</span>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td height="35">Orden Modulo:</td>
                                    <td>
                                        <input type="text" id="txtorden_modulo" name="txtorden_modulo" value="" maxlength="1" />
                                        <span class="obligatorio" >*</span>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td height="43" colspan="4" align="right">
                                        <span style="color: #ff0000;margin-left">Campo Obligatorio *</span>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td height="31" colspan="4" align="center">
                                        <input id="fila"       name="fila"   type="hidden" value="" />
                                        <input id="hdaccion"   name="hdaccion"   type="hidden" value="" />
                                        <input id="btnaccion"  name="btnaccion"  type="button" value="Agregar" />
                                        <input id="btnlimpiar" name="btnlimpiar" type="button" value="Limpiar" />
                                        <input id="btnlistar" name="btnlistar" type="button" value="SubModulos" />
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </td>
                </tr>
                
                <tr>
                    <td align="center">
                        <table width="441" border="0" align="center" cellspacing="1" class="datatabla" id="tabla_modulo" >
                            <thead>
                                <tr>
                                    <th>Rendering engine</th>
                                    <th>Browser</th>
                                    <th>Platform(s)</th>
                                    <th>Engine version</th>
                                    <th>CSS grade</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <tr>
                                    <td>Trident</td>
                                    <td>Internet
                                        Explorer 4.0</td>
                                    <td>Win 95+</td>
                                    <td> 4</td>
                                    <td>X</td>
                                </tr>
                                
                                <tr>
                                    <td>Trident</td>
                                    <td>Internet
                                        Explorer 5.0</td>
                                    <td>Win 95+</td>
                                    <td>5</td>
                                    <td>C</td>
                                </tr>
                                
                                <tr>
                                    <td>Trident</td>
                                    <td>Internet
                                        Explorer 5.5</td>
                                    <td>Win 95+</td>
                                    <td>5.5</td>
                                    <td>A</td>
                                </tr>
                                
                                <tr>
                                    <td>Trident</td>
                                    <td>Internet
                                        Explorer 6</td>
                                    <td>Win 98+</td>
                                    <td>6</td>
                                    <td>A</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>