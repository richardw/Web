<?php
print_r($_params);
$data = array();
$page = array();
$page['section'] = 72;
if(isset($_params[0])) {
    if($_params[0]=='form') {
$data['heading'] = 'Returns form';
$page['description'] = '';
$page['keywords'] = '';
$data['content'] = <<<EOD
<p><strong>Contact Information</strong><br /><br />
Please begin the returns process by filling in and submitting the contact details form below.<br />
You will then be asked for general information about your returns followed by up to ten serial numbers of the items you are returning.<br /><br />
Once you have correctly completed these forms you will be emailed a returns form (as a PDF document attached to the email) pre-filled in with your details, this should be printed, checked and returned with your delivery.<br /><br />
If you experience difficulties while using this page please telephone our Service Department on +44(0)1785 218500 for assistance.<br /><br />
    </p>
<form><input><th>Contact Information</th>
<strong>Contact;Name;:</strong>
;<input>
<strong>Company;:</strong>
;<input>
<strong>Address;;:</strong>
;<input>
<strong>;;:</strong>
;<input>
<strong>;;:</strong>
;<input>
<strong>Town/City;:</strong>
;<input>
<strong>County;:</strong>
;<input>
<strong>Postal;Code;:</strong>
;<input>
<strong>Country;:</strong>
;<input>
<strong>Telephone;:</strong>
;<input>
<strong>Fax;:</strong>
;<input>
<strong>Email;Address;:</strong>
;<input>
<br /><input>;<input></form>
EOD;
    }
    else
        require_once($_SERVER['DOCUMENT_ROOT'].'/app/protected/classes/cms_frontend.php');
}
else
    require_once($_SERVER['DOCUMENT_ROOT'].'/app/protected/classes/cms_frontend.php');

view('header',$page);
view('page',$data,false,0); //cache time in minutes
view('footer');
?>