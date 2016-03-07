<?php

$date = isset($_GET['d']) ? $_GET['d'] : date('Y/m/d');


$filename = 'quiniela-'.str_replace('/', '-', $date);
$filepath = __DIR__.'/sorteos/'.$filename;
if (!is_file($filepath)) {
    $content = getUrlContent('http://www.dejugadas.com/quinielas/datospizarra.php', true, 'fecha='.$date);
    file_put_contents($filepath, $content);
} else {
    $content = file_get_contents($filepath);
}



$html = <<<HTML
<script type="text/javascript">
$(document).ready(function() {

	$('#t_datos tr:even').addClass('even');
	$('#t_datos tr:odd').addClass('odd');

	$("._click").click(function(){
		pizarra_loteria($(this));
	});

});
</script>


<table width="500" border="0" align="center" class="texto_titulo"  >
<tr>
<td valign="top">

<table align="center"  width="600" cellpadding="3" cellspacing="0" bgcolor="#FFFFFF" border="0"  bordercolor="#000000" >



<thead>
<!--//<tr  width="95%" class="texto_cabezas" bgcolor="#28447a" border="1" cellpadding="0" cellspacing="0" bordercolor="#28447a" align="center">
<td ><font color="yellow">Loter&iacute;a</td><td ><font color="yellow">Premios</td><td><font color="yellow">Primera</font></td><td><font color="yellow">Matutino</font></td><td><font color="yellow">Vespertino</font></td><td><font color="yellow">Nocturno</font></td>
</tr>//-->
<tr  width="500" class="texto_cabezas" bgcolor="#000000" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000" align="center">
<td width= "100"><font color="#FFFFFF">Loter&iacute;a</td><td  width= "100"><font color="#FFFFFF">Pizarra</font></td><td  width= "100"><font color="#FFFFFF">La Primera</font></td><td  width= "100"><font color="#FFFFFF">Matutino</font></td><td  width= "100"><font color="#FFFFFF">Vespertino</font></td><td  width= "100"><font color="#FFFFFF">Nocturno</font></td>
</tr>
</thead>

	<tbody id="t_datos">

           <tr bgcolor="#FFFFE8"  class="texto_titulo">
             <td class="">Nacional</td>
             <td class="_click"><div align="center"><a style="cursor:hand" class="texto"><font color="#000000">[Ver]</font></a></div></td>
             <td><div align="center">6318</div></td>
             <td><div align="center">3426</div></td>
             <td><div align="center">5268</div></td>
             <td><div align="center">2840</div></td>
			 <td style="display:none"><input type="hidden" class='idloteria' value="25" name="id[]" ></td>

           </tr>

           <tr bgcolor="#FFFFE8"  class="texto_titulo">
             <td class="">Provincia</td>
             <td class="_click"><div align="center"><a style="cursor:hand" class="texto"><font color="#000000">[Ver]</font></a></div></td>
             <td><div align="center">2545</div></td>
             <td><div align="center">5629</div></td>
             <td><div align="center">1758</div></td>
             <td><div align="center">8070</div></td>
			 <td style="display:none"><input type="hidden" class='idloteria' value="24" name="id[]" ></td>

           </tr>

           <tr bgcolor="#FFFFE8"  class="texto_titulo">
             <td class="">Santa Fe</td>
             <td class="_click"><div align="center"><a style="cursor:hand" class="texto"><font color="#000000">[Ver]</font></a></div></td>
             <td><div align="center">4382</div></td>
             <td><div align="center">6061</div></td>
             <td><div align="center">5017</div></td>
             <td><div align="center">04239</div></td>
			 <td style="display:none"><input type="hidden" class='idloteria' value="38" name="id[]" ></td>

           </tr>

           <tr bgcolor="#FFFFE8"  class="texto_titulo">
             <td class="">Montevideo</td>
             <td class="_click"><div align="center"><a style="cursor:hand" class="texto"><font color="#000000">[Ver]</font></a></div></td>
             <td><div align="center">----</div></td>
             <td><div align="center">6459</div></td>
             <td><div align="center">----</div></td>
             <td><div align="center">2583</div></td>
			 <td style="display:none"><input type="hidden" class='idloteria' value="23" name="id[]" ></td>

           </tr>

           <tr bgcolor="#FFFFE8"  class="texto_titulo">
             <td class="">Entre RÃ­os</td>
             <td class="_click"><div align="center"><a style="cursor:hand" class="texto"><font color="#000000">[Ver]</font></a></div></td>
             <td><div align="center">6108</div></td>
             <td><div align="center">5114</div></td>
             <td><div align="center">5905</div></td>
             <td><div align="center">0681</div></td>
			 <td style="display:none"><input type="hidden" class='idloteria' value="39" name="id[]" ></td>

           </tr>

           <tr bgcolor="#FFFFE8"  class="texto_titulo">
             <td class="">Mendoza</td>
             <td class="_click"><div align="center"><a style="cursor:hand" class="texto"><font color="#000000">[Ver]</font></a></div></td>
             <td><div align="center">----</div></td>
             <td><div align="center">9646</div></td>
             <td><div align="center">5371</div></td>
             <td><div align="center">5155</div></td>
			 <td style="display:none"><input type="hidden" class='idloteria' value="53" name="id[]" ></td>

           </tr>

           <tr bgcolor="#FFFFE8"  class="texto_titulo">
             <td class="">CÃ³rdoba</td>
             <td class="_click"><div align="center"><a style="cursor:hand" class="texto"><font color="#000000">[Ver]</font></a></div></td>
             <td><div align="center">8284</div></td>
             <td><div align="center">8312</div></td>
             <td><div align="center">8090</div></td>
             <td><div align="center">8961</div></td>
			 <td style="display:none"><input type="hidden" class='idloteria' value="28" name="id[]" ></td>

           </tr>

           <tr bgcolor="#FFFFE8"  class="texto_titulo">
             <td class="">Corrientes</td>
             <td class="_click"><div align="center"><a style="cursor:hand" class="texto"><font color="#000000">[Ver]</font></a></div></td>
             <td><div align="center">2367</div></td>
             <td><div align="center">2947</div></td>
             <td><div align="center">6671</div></td>
             <td><div align="center">0573</div></td>
			 <td style="display:none"><input type="hidden" class='idloteria' value="42" name="id[]" ></td>

           </tr>

           <tr bgcolor="#FFFFE8"  class="texto_titulo">
             <td class="">Chaco</td>
             <td class="_click"><div align="center"><a style="cursor:hand" class="texto"><font color="#000000">[Ver]</font></a></div></td>
             <td><div align="center">3711</div></td>
             <td><div align="center">4847</div></td>
             <td><div align="center">7293</div></td>
             <td><div align="center">9348</div></td>
			 <td style="display:none"><input type="hidden" class='idloteria' value="52" name="id[]" ></td>

           </tr>

           <tr bgcolor="#FFFFE8"  class="texto_titulo">
             <td class="">Santiago</td>
             <td class="_click"><div align="center"><a style="cursor:hand" class="texto"><font color="#000000">[Ver]</font></a></div></td>
             <td><div align="center">----</div></td>
             <td><div align="center">9936</div></td>
             <td><div align="center">9109</div></td>
             <td><div align="center">7111</div></td>
			 <td style="display:none"><input type="hidden" class='idloteria' value="48" name="id[]" ></td>

           </tr>

           <tr bgcolor="#FFFFE8"  class="texto_titulo">
             <td class="">NeuquÃ©n</td>
             <td class="_click"><div align="center"><a style="cursor:hand" class="texto"><font color="#000000">[Ver]</font></a></div></td>
             <td><div align="center">5742</div></td>
             <td><div align="center">9955</div></td>
             <td><div align="center">6659</div></td>
             <td><div align="center">46365</div></td>
			 <td style="display:none"><input type="hidden" class='idloteria' value="41" name="id[]" ></td>

           </tr>

           <tr bgcolor="#FFFFE8"  class="texto_titulo">
             <td class="">San Luis</td>
             <td class="_click"><div align="center"><a style="cursor:hand" class="texto"><font color="#000000">[Ver]</font></a></div></td>
             <td><div align="center">0300</div></td>
             <td><div align="center">2895</div></td>
             <td><div align="center">9079</div></td>
             <td><div align="center">7955</div></td>
			 <td style="display:none"><input type="hidden" class='idloteria' value="49" name="id[]" ></td>

           </tr>

           <tr bgcolor="#FFFFE8"  class="texto_titulo">
             <td class="">Salta</td>
             <td class="_click"><div align="center"><a style="cursor:hand" class="texto"><font color="#000000">[Ver]</font></a></div></td>
             <td><div align="center">9762</div></td>
             <td><div align="center">7964</div></td>
             <td><div align="center">9812</div></td>
             <td><div align="center">5286</div></td>
			 <td style="display:none"><input type="hidden" class='idloteria' value="51" name="id[]" ></td>

           </tr>

           <tr bgcolor="#FFFFE8"  class="texto_titulo">
             <td class="">Jujuy</td>
             <td class="_click"><div align="center"><a style="cursor:hand" class="texto"><font color="#000000">[Ver]</font></a></div></td>
             <td><div align="center">1248</div></td>
             <td><div align="center">4747</div></td>
             <td><div align="center">6218</div></td>
             <td><div align="center">9441</div></td>
			 <td style="display:none"><input type="hidden" class='idloteria' value="50" name="id[]" ></td>

           </tr>

           <tr bgcolor="#FFFFE8"  class="texto_titulo">
             <td class="">TucumÃ¡n</td>
             <td class="_click"><div align="center"><a style="cursor:hand" class="texto"><font color="#000000">[Ver]</font></a></div></td>
             <td><div align="center">2762</div></td>
             <td><div align="center">3663</div></td>
             <td><div align="center">8390</div></td>
             <td><div align="center">6891</div></td>
			 <td style="display:none"><input type="hidden" class='idloteria' value="55" name="id[]" ></td>

           </tr>

           <tr bgcolor="#FFFFE8"  class="texto_titulo">
             <td class="">Chubut</td>
             <td class="_click"><div align="center"><a style="cursor:hand" class="texto"><font color="#000000">[Ver]</font></a></div></td>
             <td><div align="center">5531</div></td>
             <td><div align="center">1046</div></td>
             <td><div align="center">3837</div></td>
             <td><div align="center">7921</div></td>
			 <td style="display:none"><input type="hidden" class='idloteria' value="56" name="id[]" ></td>

           </tr>

           <tr bgcolor="#FFFFE8"  class="texto_titulo">
             <td class="">Formosa</td>
             <td class="_click"><div align="center"><a style="cursor:hand" class="texto"><font color="#000000">[Ver]</font></a></div></td>
             <td><div align="center">0333</div></td>
             <td><div align="center">4279</div></td>
             <td><div align="center">7641</div></td>
             <td><div align="center">4541</div></td>
			 <td style="display:none"><input type="hidden" class='idloteria' value="59" name="id[]" ></td>

           </tr>

           <tr bgcolor="#FFFFE8"  class="texto_titulo">
             <td class="">Misiones</td>
             <td class="_click"><div align="center"><a style="cursor:hand" class="texto"><font color="#000000">[Ver]</font></a></div></td>
             <td><div align="center">8135</div></td>
             <td><div align="center">1175</div></td>
             <td><div align="center">5927</div></td>
             <td><div align="center">6055</div></td>
			 <td style="display:none"><input type="hidden" class='idloteria' value="60" name="id[]" ></td>

           </tr>

           <tr bgcolor="#FFFFE8"  class="texto_titulo">
             <td class="">Catamarca</td>
             <td class="_click"><div align="center"><a style="cursor:hand" class="texto"><font color="#000000">[Ver]</font></a></div></td>
             <td><div align="center">6087</div></td>
             <td><div align="center">8235</div></td>
             <td><div align="center">9417</div></td>
             <td><div align="center">0402</div></td>
			 <td style="display:none"><input type="hidden" class='idloteria' value="61" name="id[]" ></td>

           </tr>

           <tr bgcolor="#FFFFE8"  class="texto_titulo">
             <td class="">San Juan</td>
             <td class="_click"><div align="center"><a style="cursor:hand" class="texto"><font color="#000000">[Ver]</font></a></div></td>
             <td><div align="center">----</div></td>
             <td><div align="center">----</div></td>
             <td><div align="center">----</div></td>
             <td><div align="center">----</div></td>
			 <td style="display:none"><input type="hidden" class='idloteria' value="62" name="id[]" ></td>

           </tr>

           <tr bgcolor="#FFFFE8"  class="texto_titulo">
             <td class="">La Rioja</td>
             <td class="_click"><div align="center"><a style="cursor:hand" class="texto"><font color="#000000">[Ver]</font></a></div></td>
             <td><div align="center">382</div></td>
             <td><div align="center">061</div></td>
             <td><div align="center">---</div></td>
             <td><div align="center">239</div></td>
			 <td style="display:none"><input type="hidden" class='idloteria' value="63" name="id[]" ></td>

           </tr>

	</tbody>


</table>


</td>
</tr>
</table>
HTML;

preg_match(<<<TAG
/<tbody id="t_datos">(.|\n|\s)*<\/tbody>/U
TAG
, $html, $matches);

print_r($matches);

$dom = new DOMDocument();
$dom->loadHTML($matches[1]);

$tr = $dom->getElementsByTagName('tr');
foreach ($tr->attributes as $attr) {
    $name = $attr->nodeName;
    $value = $attr->nodeValue;
    echo "Attribute '$name' :: '$value'<br />";
}









echo $matches[1];








/**
 * GetUrlContent
 *
 * @param $url
 * @param int $isPost
 * @param string $postFields
 *
 * @return bool|mixed
 */
function getUrlContent($url, $isPost = true, $postFields = '')
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);

    if ($isPost === true) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    }

    $data = utf8_decode(curl_exec($ch));
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ($httpcode>=200 && $httpcode<300) ? $data : false;
}