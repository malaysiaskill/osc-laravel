/* Ajax */
function Ajx(ajax){ ajax.method = "POST"; ajax.setVar('_token', Laravel.csrfToken); ajax.onCompletion = function(){ var text = ajax.response; eval(text);}; ajax.runAJAX();}

/* Pengguna */
function ClearAddUser() {
	$('#_name').val('');
	$('#_email').val('');
	$('#_pwd').val('');
	$('#_usergroups').select2().val('');
	$('#_kodjpn').select2().val('');
	$('#_kodppd').select2().val('');
	$('#_kodsek').select2().val('');
	$('#_uid').val('0');
}
function AddUserDialog() {
	ClearAddUser();
	$('#UserDialog').modal();
    setTimeout(function(){
        $('#_name').focus();
    },500);
}
function EditUser(id) {
	var ajax = new sack();
	ajax.requestFile = "/admin/users/getuser/" + id;
    Ajx(ajax);
}