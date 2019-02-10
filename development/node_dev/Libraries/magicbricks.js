/**
 * 
 * @author Parveen Yadav
 */
const attrNameProcessors = attrName => attrName.toLowerCase();
const parseString = require('xml2js').Parser({attrkey:'attributes',trim:true,normalizeTags:true,normalize:true,explicitArray:false,attrNameProcessors:[attrNameProcessors]}).parseString;
const request = require('request');
const pullData = () => {
    return new Promise((resolve, reject) => {
        const postObj = {
            url: 'http://rating.magicbricks.com/mbRating/download.xml?key=fMUkHOCpYUsg5K70nqFAUw~~~~~~3D~~~~~~3D&startDate=20190101&endDate=20190101',
            timeout: 10000,
            strictSSL: false,
            headers: {
                "Content-Type": "application/xml",
                "Accept": "text/xml",
                "User-Agent": "request",
            }
        };
        //console.log(postObj);
        request.get(postObj, (err, resp, body) => {
            try {
                if (err) throw new Error(err);
                console.log(body);
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