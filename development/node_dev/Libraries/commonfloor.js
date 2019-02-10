const request = require('request');
const pullData = () => {
    return new Promise((resolve, reject) => {
        const postObj = {
            url: 'https://www.commonfloor.com/agent/pull-leads/v1?id=5b7eab937adff&key=65e278149d4d3494&start=20181230&end=20190103&format=json',
            timeout: 10000,
            strictSSL: false,
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "User-Agent": "request",
            }
        };
        //console.log(postObj);
        request.post(postObj, (err, resp, body) => {
            try {
                if (err) throw new Error(err);
                //console.log(body);
                resolve(body);
            } catch (error) {
                reject(error);
            }
        });   
    });
}
module.exports = {pullData};