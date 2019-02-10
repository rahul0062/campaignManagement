/**
 * @author Parveen Yadav
 */
const attrNameProcessors = attrName => attrName.toLowerCase();
const parseString = require('xml2js').Parser({attrkey:'attributes',trim:true,normalizeTags:true,normalize:true,explicitArray:false,attrNameProcessors:[attrNameProcessors]}).parseString;
const request = require('request');
const pullData = () => {
    return new Promise((resolve, reject) => {
        const postData = "<?xml version='1.0'?><query><user_name>REALUNITYPVTLTD</user_name><pswd>hardik123</pswd><start_date>2019-01-05 00:00:00</start_date><end_date>2019-01-05 23:59:59</end_date></query>";
        const postObj = {
            url: 'http://www.99acres.com/99api/v1/getmy99Response/OeAuXClO43hwseaXEQ/uid/',
            formData: {xml:postData},
            timeout: 10000,
            strictSSL: false,
            headers: {
                "Content-Type": "application/xml",
                "Accept": "text/xml",
                "User-Agent": "request",
            }
        };
        //console.log(postObj);
        request.post(postObj, (err, resp, body) => {
            try {
                if (err) throw new Error(err);
                parseString(body, (err2, result) => {
                    if(err2) throw new Error(err2);
                    resolve(result);
                });   
            } catch (error) {
                reject(error);
            }
        });   
    });
}
module.exports = {pullData};