
var fs = require('fs');


fs.readFile("quinielasargentinas.com.html", 'utf-8', function (err, data) {

    hallado = data.match(/Resultado de los sorteos realizados el dia <b>(\d+\/\d+\/\d+)<\/b>/i);

    console.log(hallado[1]);
});
