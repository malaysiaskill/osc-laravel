/* Ajax */
function Ajx(ajax){ ajax.method = "POST"; ajax.setVar('_token', Laravel.csrfToken); ajax.onCompletion = function(){ var text = ajax.response; eval(text);}; ajax.runAJAX();}

function jump(targ,selObj,restore){
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

/* Avatar */
function AvatarTemplate(avatar) {
    if (!avatar.id) { return avatar.text; }
    var $avatar = $(
        '<span><img src="/avatar/' + avatar.element.value.toLowerCase() + '" class="img-avatar img-avatar32 push-5-r" /> ' + avatar.text + '</span>'
    );
    return $avatar;
}

/* Pengguna */
function ClearAddUser() {
	$('#_name').val('');
	$('#_email').val('');
	$('#_pwd').val('');
	$('#_usergroups').val('').trigger('change');
	$('#_kodjpn').val('').trigger('change');
	$('#_kodppd').val('').trigger('change');
	$('#_kodsek').val('').trigger('change');
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

/* Kumpulan DevTeam */
function ClearAddGroup() {
	$('#_kodppd').val('').trigger('change');
	$('#_name').val('');
	$('#_ketua').val('').trigger('change');
	$('#_jtk').val('').trigger('change');
	$('#_gid').val('0');
}
function AddGroupDialog() {
	$('#GroupDialog').modal();
}
function EditDevTeam(id) {
	var ajax = new sack();
	ajax.requestFile = "/dev-team/edit/" + id;
    Ajx(ajax);
}
function DeleteDevTeam(id) {
    swal({
        title: "Padam Rekod ?",
        text: "Anda pasti untuk memadam rekod ini ?",
        type: "warning",
        html: true,
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya",
        cancelButtonText: "Batal",
        closeOnConfirm: true,
        showLoaderOnConfirm: false
    },
    function()
    {
		var ajax = new sack();
		ajax.requestFile = "/dev-team/delete/" + id;
	    Ajx(ajax);
    });
}
