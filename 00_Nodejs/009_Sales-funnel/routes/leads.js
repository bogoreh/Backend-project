const express = require('express');
const router = express.Router();
const leadController = require('../controllers/leadController');

// Lead routes
router.get('/', leadController.getAllLeads);
router.get('/stats', leadController.getFunnelStats);
router.get('/status/:status', leadController.getLeadsByStatus);
router.get('/:id', leadController.getLead);
router.post('/', leadController.createLead);
router.put('/:id', leadController.updateLead);
router.delete('/:id', leadController.deleteLead);

module.exports = router;