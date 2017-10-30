<?php
function conletraUSD($numero) {
 $alfa = array(array("", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine"),                    //[0]
               array("ten", "eleven", "twelve"),                                                                     //[1]
               array("", "", "", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eighteen", "nineteen"), //[2]
               array("", "", "twenty", "thirty", "fourty", "fifty", "sixty", "seventy", "eighty", "ninety"),         //[3]
               array("", "hundred"),                                                                            //[4]
               array("", "thousand"),                                                                                //[5]
               array("", "million", "billion", "trillion"));                                                         //[6]
 $numero = number_format(abs($numero), 2, ".", ""); //genera decimales truncados a dos posiciones
 $completo = str_split(strrev(substr($numero, 0, strpos($numero, ".")))); //guarda una copia del número original como arreglo invertido
 if ($numero >= 2) $texto = substr(ltrim(strstr($numero, "."), "."), 0, 2)."/100 US Dollars"; //calcula los decimales para más de un peso
 elseif ($numero >= 1) $texto = substr(ltrim(strstr($numero, "."), "."), 0, 2)."/100 US Dollar"; //calcula los decimales para UN peso
 elseif ($numero >= 0) $texto = "Zero ".substr(ltrim(strstr($numero, "."), "."), 0, 2)."/100 US Dollars"; //texto completo para cantidad CERO
 $entero = array_chunk($completo, 6); //crea un arreglo bidimensional dividido por MILLONES, BILLONES, TRILLONES, ETC.
 for ($j=0;$j<count($entero);$j++) {
  if (($j > 0) && (array_sum(array_slice($completo, 0, 2*$j-1)) == 0) && !(strpos($texto, "de"))) $texto = "".$texto; //agrega el "de" a "pesos" en cantidades cerradas de MILLONES, BILLONES, ETC.
  if (array_sum($entero[$j]) >= 1) $texto = $alfa[6][$j]." ".$texto;                          //todos los demás MILLONES, BILLONES, ETC.
  for ($i=0;$i<count($entero[$j]);$i++) {
   switch ($i) {
    case 5; //centenas de MILLARES
     if (($numero % 1000000) == 0) $texto = $texto; //si el número es mayor a un millón, y no tiene millares, evitar el texto "mil"
     elseif ($entero[$j][$i] <> 0 && $entero[$j][$i-1] == 0 && $entero[$j][$i-2] == 0) $texto = $alfa[5][1]." ".$texto; //cuando haya centenas de MILLARES, agregar el texto "mil"
    case 2; //centenas
     if (($entero[$j][$i] <> 0) && ($entero[$j][$i-1] == 0) && ($entero[$j][$i-2] == 0)) $texto = $alfa[0][$entero[$j][$i]]." ".$alfa[4][1].$texto;
     elseif ($entero[$j][$i] <> 0) $texto = $alfa[0][$entero[$j][$i]]." ".$alfa[4][1]." and ".$texto;                                                    //del 101 al 999
     break;
    case 4; //decenas de MILLARES
     if ($entero[$j][$i] <> 0 && $entero[$j][$i-1] == 0) $texto = $alfa[5][1]." ".$texto; //cuando haya decenas de MILLARES, agregar el texto "mil"
    case 1; //decenas
     if ($entero[$j][$i] > 1) $texto = $alfa[3][$entero[$j][$i]]." ".$texto;         // del 20 al 99 (excepto los casos anteriores)
     elseif (($entero[$j][$i-1] < 3) && ($entero[$j][$i] <> 0)) $texto = $alfa[1][$entero[$j][$i-1]]." ".$texto; //casos 10, 11 y 12
     else $texto = $alfa[2][$entero[$j][$i-1]]." ".$texto;                             //casos 13 al 19
     break;
    case 3; //unidades de MILLARES
     if ($entero[$j][$i] <> 0) $texto = $alfa[5][1]." ".$texto; //cuando haya unidades de MILLARES, agregar el texto "mil"
    case 0; //unidades
     if (($entero[$j][$i] == 0) || ($entero[$j][$i+1] == 1)) $texto = $texto; //excepto del 10 al 19 y del 20 al 90
     else $texto = $alfa[0][$entero[$j][$i]]." ".$texto; //del 1 al 9, excepto en los casos anteriores
     break; } } }
 return "(".ucfirst(ltrim($texto)).")"; }

print(preg_replace('/\s+/', ' ',conletraUSD($argv[1],$argv[2],$argv[3],$argv[4]))."\n");
?>
