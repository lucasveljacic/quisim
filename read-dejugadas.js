var request = require("request");
var fs = require('fs');

var date = process.argv[2] ? process.argv[2] : "2016/02/12";

var filepath = "data/"+date.replace(/\//g, '-')+".json";

if (!fs.existsSync(filepath)) {

    console.log('Obteniendo sorteo de la decha: '+date);

    request.post(
        "http://www.dejugadas.com/quinielas/datospizarra.php",
        {
            form: {
                fecha: date
            }
        },
        function (error, response, body) {
            if (!error && response.statusCode == 200) {

                var cheerio = require("cheerio");
                var $ = cheerio.load(body);
                var list = $("#t_datos");
                var output = [];

                var item = {source: null, sorteos: {}};

                $("#t_datos tr td").each(function(index) {

                    index = index % 7;

                    var td = $(this);

                    if (item.source != null && index % 7 == 0) {
                        output.push(item);
                        item = {source: null, sorteos: {}};
                    }

                    switch (index) {
                    case 0:
                        item.source = td.html();
                        break;
                    case 2:
                        item.sorteos.pri = $(td.html()).html();
                        break;
                    case 3:
                        item.sorteos.mat = $(td.html()).html();
                        break;
                    case 4:
                        item.sorteos.ves = $(td.html()).html();
                        break;
                    case 5:
                        item.sorteos.noc = $(td.html()).html();
                        break;
                    }

                });

                fs.writeFile(filepath, JSON.stringify(output), function(err) {
                    if (err) {
                        return console.log(err);
                    }

                    console.log("Se ha generado el archivo '"+filepath+"'!");
                });
            }
        }
    );
}