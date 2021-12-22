
var request = require('request');

var Parser = require('./parse.js');

var parser = new Parser();
  

//
// This defines three routes that our API is going to use.
//

var routes = function(app) {
//
// This route processes GET requests, by using the `get()` method in express, and we're looking for them on
// the root of the application (in this case that's https://rest-api.glitch.me/), since we've
// specified `"/"`.  For any GET request received at "/", we're sending some HTML back and logging the
// request to the console. The HTML you see in the browser is what `res.send()` is sending back.
//
  app.get("/", function(req, res) {
     res.sendFile(__dirname + '/index.html');   
     console.log("Received GET");
  });
  
  app.get("/citeproc.js", function(req, res) {
     res.sendFile(__dirname + '/citeproc.js');   
     console.log("Received GET");
  });
  
  app.get("/locale.js", function(req, res) {
     res.sendFile(__dirname + '/locale.js');   
     console.log("Received GET");
  });  
  
  app.get("/parse.js", function(req, res) {
     res.sendFile(__dirname + '/parse.js');   
     console.log("Received GET");
  });
  
  app.get("/style.js", function(req, res) {
     res.sendFile(__dirname + '/style.js');   
     console.log("Received GET");
  });
  
  app.get("/xmldom.js", function(req, res) {
     res.sendFile(__dirname + '/xmldom.js');   
     console.log("Received GET");
  });  

  //---------------------------------------------------
  // fetch Wikispecies page
  app.get("/parse", function(req, res) {
    
    console.log("Received GET: "+JSON.stringify(req.body));
    
    var citation = parser.parse_reference(req.query.string)
  
    res.send(citation);
   
  });    
};
 
module.exports = routes;