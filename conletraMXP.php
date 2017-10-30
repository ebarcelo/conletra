<?php
session_start();
function conletra($numero) {
 $alfa = array(array("", "un", "dos", "tres", "cuatro", "cinco", "seis", "siete", "ocho", "nueve"),                                                         //[0]
               array("diez", "once", "doce", "trece", "catorce", "quince", "", "", "", ""),                                                                 //[1]
               array("", "dieci", "veinti", "", "", "", "", "", "", ""),                                                                                    //[2]
               array("", "", "veinte", "treinta", "cuarenta", "cincuenta", "sesenta", "setenta", "ochenta", "noventa"),                                     //[3]
               array("", "ciento", "doscientos", "trescientos", "cuatrocientos", "quinientos", "seiscientos", "setecientos", "ochocientos", "novecientos"), //[4]
               array("", "mil"),                                                                                                                            //[5]
               array("", "millón", "millones", "billón", "billones", "trillón", "trillones", "cuatrillón", "cuatrillones", "quintillón", "quintillones",
                     "hexallón", "hexallones", "heptallón", "heptallones", "octallón", "octallones", "nonallón", "nonallones", "decallón", "decallones",
                     "endecallón", "endecallones", "duodecallón", "duodecallones", "tridecallón", "tridecallones", "tetradecallón", "tetradecallones",
                     "pentadecallón", "pentadecallones", "hexadecallón", "hexadecallones", "heptadecallón", "heptadecallones", "octadecallón", "octadecallones",
                     "nonadecallón", "nonadecallones", "bidecallón", "bidecallones"));                                                                      //[6]
 $numero = number_format(abs($numero), 2, ".", ""); //genera decimales truncados a dos posiciones
 $completo = str_split(strrev(substr($numero, 0, strpos($numero, ".")))); //guarda una copia del número original como arreglo invertido
 if ($numero >= 2) $texto = " pesos ".substr(ltrim(strstr($numero, "."), "."), 0, 2)."/100 M. N."; //calcula los decimales para más de un peso
 elseif ($numero >= 1) $texto = " peso ".substr(ltrim(strstr($numero, "."), "."), 0, 2)."/100 M. N."; //calcula los decimales para UN peso
 elseif ($numero >= 0) $texto = "cero pesos ".substr(ltrim(strstr($numero, "."), "."), 0, 2)."/100 M. N."; //texto completo para cantidad CERO
 $entero = array_chunk($completo, 6); //crea un arreglo bidimensional dividido por MILLONES, BILLONES, TRILLONES, ETC.
 for ($j=0;$j<count($entero);$j++) {
  if (($j > 0) && (array_sum(array_slice($completo, 0, 2*$j-1)) == 0) && !(strpos($texto, "de"))) $texto = "de ".$texto; //agrega el "de" a "pesos" en cantidades cerradas de MILLONES, BILLONES, ETC.
  if ((array_sum($entero[$j]) == 1) && ($entero[$j][0] == 1)) $texto = $alfa[6][2*$j-1]." ".$texto; //caso UN MILLÓN, BILLÓN, ETC.
  elseif (array_sum($entero[$j]) >= 1) $texto = $alfa[6][2*$j]." ".$texto;                          //todos los demás MILLONES, BILLONES, ETC.
  for ($i=0;$i<count($entero[$j]);$i++) {
   switch ($i) {
    case 5; //centenas de MILLARES
     if (($numero % 1000000) == 0) $texto = $texto; //si el número es mayor a un millón, y no tiene millares, evitar el texto "mil"
     elseif ($entero[$j][$i] <> 0 && $entero[$j][$i-1] == 0 && $entero[$j][$i-2] == 0) $texto = $alfa[5][1]." ".$texto; //cuando haya centenas de MILLARES, agregar el texto "mil"
    case 2; //centenas
     if ($entero[$j][$i] == 1 && $entero[$j][$i-1] == 0 && $entero[$j][$i-2] == 0) $texto = "cien ".$texto; //caso especial, el 100
     else $texto = $alfa[4][$entero[$j][$i]]." ".$texto;                                                    //del 101 al 999
     break;
    case 4; //decenas de MILLARES
     if (($numero % 1000000) == 0) $texto = $texto; //si el número es mayor a un millón, y no tiene millares, evitar el texto "mil"
     elseif ($entero[$j][$i] <> 0 && $entero[$j][$i-1] == 0) $texto = $alfa[5][1]." ".$texto; //cuando haya decenas de MILLARES, agregar el texto "mil"
    case 1; //decenas
     if (($entero[$j][$i] == 1) && ($entero[$j][$i-1] <= 5)) $texto = $alfa[1][$entero[$j][$i-1]]." ".$texto;  //casos 10, 11, 12, 13, 14, y 15
     elseif (($entero[$j][$i-1] == 0) && ($entero[$j][$i] > 1)) $texto = $alfa[3][$entero[$j][$i]]." ".$texto; //casos 20, 30, 40, 50, 60, 70, 80 y 90
     elseif ($entero[$j][$i] < 3) $texto = $alfa[2][$entero[$j][$i]].$texto;                                   //casos 16 al 29
     else $texto = $alfa[3][$entero[$j][$i]]." y ".$texto;                                                     // del 31 al 99 (excepto los casos anteriores)
     break;
    case 3; //unidades de MILLARES
     if ($entero[$j][$i] <> 0) $texto = $alfa[5][1]." ".$texto; //cuando haya unidades de MILLARES, agregar el texto "mil"
    case 0; //unidades
     if ((($entero[$j][$i+1] == 1) && ($entero[$j][$i] <= 5)) || (($entero[$j][$i] == 0) && ($entero[$j][$i+1] > 1))) $texto = $texto; //excepto 10, 11, 12, 13, 14, 15 y 20 al 90
     else $texto = $alfa[0][$entero[$j][$i]]." ".$texto; //del 1 al 9, excepto en los casos anteriores
     break; } } }
 return "(".ucfirst(ltrim($texto)).")"; }
?>
