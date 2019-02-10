const app = require('express');
const router = app.Router();
const JobsController = require('../controllers/JobsController');

router.get('/job/pull/99acres-leads', JobsController.pull99acresLeads);
router.get('/job/pull/magicbricks-leads', JobsController.pullMagicbricksLeads);
router.get('/job/pull/commonfloor-leads', JobsController.pullCommonfloorLeads);
router.get('/test/facebook', JobsController.facebook);
module.exports = router;