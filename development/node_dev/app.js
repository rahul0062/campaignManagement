/**
 * @author Parveen Yadav
 * 
 */
const express = require('express');
const path = require('path');
const cookieParser = require('cookie-parser');
const logger = require('morgan');
const fs = require('fs');
const cors = require('cors');
const app = express();
require('dotenv').config();
const logDir = __dirname+'/log/';
fs.existsSync(logDir) || fs.mkdirSync(logDir)
const accessLogStream = fs.createWriteStream(logDir + 'access.log', {flags: 'a'});
app.use(cors());
app.use(logger('short', {stream: accessLogStream}));
app.use(express.json());
app.use(express.urlencoded({ extended: false }));
app.use(cookieParser());
app.use(express.static(path.join(__dirname, 'public')));
fs.readdirSync('./routes').forEach(file => {
   if(file.substr(-3) === '.js'){
       let router = require('./routes/'+file);
       app.use('/',router);
   }
});
app.use((err, req, res, next) => {
    console.error(err.stack)
    const erroLogStream = fs.createWriteStream(logDir + 'error.log', {flags: 'a'});
    erroLogStream.write(Date()+' '+ JSON.stringify(req.headers)+ ' '+JSON.stringify(req.body)+' ');
    erroLogStream.write(err.stack + "\n");
    erroLogStream.end();
    res.status(500).send('Something went wrong.')
});

module.exports = app;
