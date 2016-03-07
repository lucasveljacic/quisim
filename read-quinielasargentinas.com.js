var request = require("request");
var fs = require('fs');

var poolDim = 10;
var poolActives = 0;


var startDate = process.argv[2];
var days = process.argv[3];

arrayDate = startDate.split("-");
dateObj = new Date(arrayDate[0], arrayDate[1]-1, arrayDate[2]);

for (var d = 0; d < days; d++) {

    dateObj.setDate(dateObj.getDate() - 1);
    if (dateObj.getDay() == 0) {
        continue;
    }

    date = dateObj.getFullYear()+"-"+(dateObj.getMonth()+1)+"-"+dateObj.getDate();

    var filepath = "data/input/"+date+".json";
    if (!fs.existsSync("data/loaded/"+date+".json")
        && !fs.existsSync("data/input/"+date+".json")
        && !fs.existsSync("data/error/"+date+".json")
    ) {

        debug('Obteniendo sorteo de la decha: '+date);

        /*fs.readFile("quinielasargentinas.com.html", 'utf-8', function (err, data) {
            if (err) {
                return debug(err);
            }

            parse(data);
        });*/


        request.post(
            "http://www.quinielasargentinas.com/verResultadosQuiniela.php",
            {
                form: {
                    fecha_sorteo: date
                }
            },
            function (error, response, body) {
                if (!error && response.statusCode == 200) {
                    parse(body);
                }
            }
        );
    }
}

function leerFecha(data)
{
    found = data.match(/Resultado de los sorteos realizados el dia <b>(\d+\/\d+\/\d+)<\/b>/i);
    if (!found[1]) {
        return false;
    }

    var d = found[1].split("/");
    var date = d[2]+"-"+d[1]+"-"+d[0];

    return date;
}

function parse(data)
{
    date = leerFecha(data);

    var cheerio = require("cheerio");
    var $ = cheerio.load(data);
    var divs = $("div.frmCaption");
    var filepath = "data/input/"+date+".json";
    var output = {
        "date": date,
        "sorteos": {}
    };

    var item = {source: null, sorteos: {}};

    var loterias;


    divs.each(function(i) {
        var div = $(this);

        var name = leerSorteo(div.text());

        if (name != false) {

            var sorteo = {
                "loterias": {}
            };

            var table = div.next();
            loterias = [];
            $(table.find("tr")).each(function(i) {
                var tr = $(this);
                if (i == 0) {
                    tr.find("td").each(function() {
                        var loteria = leerLoteria($(this).text());

                        if (loteria != false) {
                            loterias.push(loteria);
                            sorteo.loterias[loteria] = {};
                        }
                    });
                } else {
                    var position;
                    var numeros = {};
                    tr.find("td").each(function(j) {
                        if (j == 0) {
                            position = $(this).text();
                        } else {
                            if (loterias[j-1] != undefined) {
                                sorteo["loterias"][loterias[j-1]][position] = $(this).text();
                            }
                        }
                    });
                }
            });

            output.sorteos[name] = sorteo;
        }

    });

    //debug(JSON.stringify(output));

    fs.writeFile(filepath, JSON.stringify(output), function(err) {
        if (err) {
            return console.log(err);
        }

        console.log("Se ha generado el archivo '"+filepath+"'!");
    });

}

function leerSorteo(text)
{
    if (text.toLowerCase().indexOf("primera") != -1) {
        return "sorteo.primera";
    }

    if (text.toLowerCase().indexOf("matutino") != -1) {
        return "sorteo.matutino";
    }

    if (text.toLowerCase().indexOf("vespertino") != -1) {
        return "sorteo.vespertino";
    }

    if (text.toLowerCase().indexOf("nocturno") != -1) {
        return "sorteo.nocturno";
    }

    return false;
}

function leerLoteria(text)
{
    if (text.toLowerCase().indexOf("bs") != -1) {
        return "loteria.provincia";
    }

    if (text.toLowerCase().indexOf("nacional") != -1) {
        return "loteria.nacional";
    }

    if (text.toLowerCase().indexOf("santa") != -1) {
        return "loteria.santa_fe";
    }

    if (text.toLowerCase().indexOf("entre") != -1) {
        return "loteria.entre_rios";
    }

    if (text.toLowerCase().indexOf("montevideo") != -1) {
        return "loteria.montevideo";
    }

    if (text.toLowerCase().indexOf("cordoba") != -1) {
        return "loteria.cordoba";
    }

    return false;
}

function debug(msg)
{
    console.log(msg);
}