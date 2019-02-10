/**
 * @author Parveen Yadav
 */
const db = require('../config/db');
const {LEADRECORD_TABLE} = require('../config/constants/db_constant');
const ID = 'id';
const ENQUIRY_AT = 'enquiry_date';
const CUSTOMER_NAME = 'customer_name';
const CONTACT_NUMBER = 'contact_number';
const COUNTRY = 'country';
const EMAIL = 'email';
const PROJECT_ID = 'project_id';
const PROJECT_NAME = 'project_name';
const DEVELOPER_NAME = 'developer_name';
const PROJECT_COUNTRY = 'projct_country';
const PROJECT_STATE = 'projct_state';
const PROJECT_CITY = 'projct_city';
const PROJECT_ZONE = 'projct_zone';
const PROJECT_STATUS = 'projct_status';
const INQUIRY_REMARK = 'inquiry_remark';
const PROJECT_DOMAIN = 'project_domain';
const INQUIRY_SOURCE = 'inquiry_source';
const ASSIGNED_TEAM = 'assigned_team';
const TELECALLER_NAME = 'telecaller_name';
const ASSIGNED_TEAM_NAME = 'assigned_team_name';
const CONTACT_NUMBER_TEMPLATE = 'contact_number_template';
const EMAIL_TEMPLATE = 'email_template';
const INQUIRY_TEMP = 'inquiry_temp';
const FEEDBACK_CUSTOMER = 'feedback_customer';
const SPECIAL_ACTIVITY = 'special_activity';
const WEBINAR = 'webinar';
const CAB = 'cab';
const HOME_PRESENTATION = 'home_presentation';
const ACTIVITY_REMARK = 'activity_remark';
const ACTIVITY_REMINDER = 'activity_reminder';
const SITE_VISIT = 'site_visit';
const COLD_CHE = 'cold_che';
const CLOSE_CHE = 'close_che';
const HOT_CHE = 'hot_che';
const WARM_CHE = 'warm_che';
const PROPOSED_DATE_TIME = 'proposed_date_time';
const PROPOSED_DATE_REMIND = 'proposed_date_remind';
const BOOK_DATE = 'book_date';
const BUDGET_AMT = 'budget_amt';
const FLAT_TYPE = 'flate_type';
const POSSESSION_YEAR = 'possession_year';
const FEED_REMARK = 'feed_remark';
const SITE_VISIT_FORM = 'site_visit_form';
const SITE_VISIT_DATE = 'site_visit_date';
const SALE_PERSON_NAME = 'sales_person_name';
const SALE_PERSON_NUMBER = 'sales_person_number';
const SITE_VISIT_PROJECT = 'site_visit_project';
const PROJECT_LOCATION = 'project_location';
const CREATED_AT = 'created_at';
const UPDATED_AT = 'updated_at';

const addLead = (data) => {
    return new Promise((resolve, reject) => {
        let q = `INSERT INTO ${LEADRECORD_TABLE} (${ENQUIRY_AT}, ${CUSTOMER_NAME}, ${EMAIL}, ${CONTACT_NUMBER}, ${PROJECT_ID}, ${PROJECT_NAME}, ${INQUIRY_SOURCE}) VALUES ? `;
        db.query(q, [data], (err, rows, fields) => {
            if(err)
            {
                return reject(err);
            }
            resolve(rows.insertId)
        });
        //console.log(str.sql);
    });
}

module.exports = {addLead}