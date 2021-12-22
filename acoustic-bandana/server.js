//
// This is where the app starts, and sets things up
// We require the packages we need, body parser and express, and then set up body parser to accept
// JSON and URL encoded values. We then include the `routes.js` file, in which we define the API
// end-points we're going to be using, and we pass it the `app` variable. Lastly, we specify the
// port to listen to for requests. In this case, port 3000.
// 
var express = require("express");
var bodyParser = require("body-parser");
var request = require('request');

var app = express();
 
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({extended: true}));
 
var routes = require("./routes.js")(app);
 
var server = app.listen(3000, function () {
  console.log("Listening on port %s", server.address().port);
});