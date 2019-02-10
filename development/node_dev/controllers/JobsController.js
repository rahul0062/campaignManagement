/**
 * @author Parveen Yadav
 */
const acres99 = require('../Libraries/99acres');
const magicbricks = require('../Libraries/magicbricks');
const commonfloor = require('../Libraries/commonfloor');
const {addLead} = require('../models/leadrecordModel');
const {getProjectId} = require('../models/projectModel');
const api = require('../Libraries/facebook');

const pull99acresLeads = async (req, res, next) => {
    try {
        let data = await acres99.pullData();
        if(!data.xml.resp) return res.status(200).json({msg: 'No Data', data: []});
        const resp = Array.isArray(data.xml.resp) ? data.xml.resp : [data.xml.resp];
        let leadsArr = [];
        for (let i=0; i<resp.length; i++)
        {
            let contactDetail = resp[i].cntctdtl;
            let queryDetail = resp[i].qrydtl;
            let projectId = await getProjectId(queryDetail.projname);
            let row = [];
            row.push(queryDetail.rcvdon); // ENQUIRY_AT
            row.push(contactDetail.name); //CUSTOMER_NAME
            row.push(contactDetail.email); //EMAIL
            row.push(contactDetail.phone); //CONTACT_NUMBER
            row.push(projectId); // PROJECT_ID
            row.push(queryDetail.projname); // PROJECT_NAME
            row.push("99acres"); // INQUIRY_SOURCE
            leadsArr.push(row); 
        }
        let insertId = await addLead(leadsArr);
        res.status(200).json({insert_id:insertId, data: leadsArr});   
    } catch (error) {
        next(error);
    }
}

const pullMagicbricksLeads = async (req, res, next) => {
    try {
        let data = await magicbricks.pullData();
        res.json({data: data}); return;
        //let data = await acres99.pullData();
        if(!data.xml.resp) return res.status(200).json({msg: 'No Data', data: []});
        const resp = Array.isArray(data.xml.resp) ? data.xml.resp : [data.xml.resp];
        let leadsArr = [];
        for (let i=0; i<resp.length; i++)
        {
            let contactDetail = resp[i].cntctdtl;
            let queryDetail = resp[i].qrydtl;
            let row = [];
            row.push(queryDetail.rcvdon); // ENQUIRY_AT
            row.push(contactDetail.name); //CUSTOMER_NAME
            row.push(contactDetail.email); //EMAIL
            row.push(contactDetail.phone); //CONTACT_NUMBER
            row.push(queryDetail.projid || 0); // PROJECT_ID
            row.push(queryDetail.projname); // PROJECT_NAME
            row.push(queryDetail.qryinfo); // INQUIRY_REMARK
            leadsArr.push(row); 
        }
        let insertId = await addLead(leadsArr);
        res.status(200).json({insert_id:insertId, data: leadsArr});   
    } catch (error) {
        next(error);
    }
}

const pullCommonfloorLeads = async (req, res, next) => {
    try {
        let data = await commonfloor.pullData();
        data = JSON.parse(data);
        //res.json(data);
        let leadsArr = [];
        for (let i=0; i<data.length; i++)
        {
            let projectId = await getProjectId(data[i].project_or_locality_name);
            let row = [];
            row.push(data[i].shared_on); // ENQUIRY_AT
            row.push(data[i].contact_name); //CUSTOMER_NAME
            row.push(data[i].contact_email); //EMAIL
            row.push(data[i].contact_mobile); //CONTACT_NUMBER
            row.push(projectId || 0); // PROJECT_ID
            row.push(data[i].project_or_locality_name); // PROJECT_NAME
            row.push("Commonfloor"); // INQUIRY_SOURCE
            leadsArr.push(row); 
        }
        let insertId = await addLead(leadsArr);
        res.status(200).json({insert_id:insertId, data: leadsArr});   
    } catch (error) {
        next(error);
    }
}
const facebook = (req, res, next) => {
    res.json(Object.getOwnPropertyNames(api));
}
module.exports = {pull99acresLeads, pullMagicbricksLeads,pullCommonfloorLeads,facebook}