/**
 * @author Parveen Yadav
 */
const db = require('../config/db');
const {PROJECT_TABLE} = require('../config/constants/db_constant');
const PROJECT_ID = 'id';
const PROJECT_NAME = 'project_name';
const getProjectId = (project_name) => {
    return new Promise((resolve, reject) => {
        const q = `SELECT ${PROJECT_ID} FROM ${PROJECT_TABLE} WHERE ${PROJECT_NAME}=? LIMIT 1`;
        db.query(q, [project_name], (err, rows, fields) => {
            if(err) return reject(err);
            if(rows.length === 0) return resolve(0);
            resolve(rows[0].id);
        });
    });
}

module.exports = {getProjectId}