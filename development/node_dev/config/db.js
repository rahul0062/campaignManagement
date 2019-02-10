/**
 * @author Parveen Yadav
 */
const mysql = require('mysql');
const db = mysql.createConnection({
    host: process.env.DB_HOST,
    user: process.env.DB_USER,
    database: process.env.DB_NAME,
    password: process.env.DB_PASS,
    multipleStatements: true
});

db.connect((error) => {
    if(error) throw new Error(error);
});

module.exports = db;