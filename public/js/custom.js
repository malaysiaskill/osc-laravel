/* Ajax */
function Ajx(ajax){ ajax.method = "POST"; ajax.setVar('_token', Laravel.csrfToken); ajax.onCompletion = function(){ var text = ajax.response; eval(text);}; ajax.runAJAX();}

function jump(targ,selObj,restore){
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

/* Remove File */
function RemoveFile(filename) {
    var ajax = new sack();
    ajax.requestFile = "/dev-team/projek/padam-kertas-kerja/" + filename;
    Ajx(ajax);    
}

/* Sweet Alert*/
function SweetAlert(mode,title,txt,func) {
    if (typeof(func)==='undefined')
    {
        if (mode == "success") {
            swal({title: title, text: txt, type: 'success', html: true});
        } else if (mode == "error" || mode == "danger") {
            swal({title: title, text: txt, type: 'error', html: true});
        } else {
            swal(txt);
        }
    }
    else
    {
        swal({
            title: title,
            text: txt,
            type: mode,
            showCancelButton: false,
            closeOnConfirm: true,
            html: true
        },function(){ eval(func); });
    }
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
        closeOnConfirm: false,
        showLoaderOnConfirm: true
    },
    function()
    {
		var ajax = new sack();
		ajax.requestFile = "/dev-team/delete/" + id;
	    Ajx(ajax);
    });
}

/* Projek Kumpulan Dev Team */
function ClearAddProjek() {
    $('#_devteam').val('').trigger('change');
    $('#_nama_projek').val('');
    $('#_objektif').val('');
    $('#_detail').val('');
    $('#_projekid').val('0');
}
function AddProjekDialog() {
    $('#ProjekDialog').modal();
}
function EditProjek(id) {
    var ajax = new sack();
    ajax.requestFile = "/dev-team/projek/edit/" + id;
    Ajx(ajax);
}
function DeleteProjek(id) {
    swal({
        title: "Padam Projek ?",
        text: "Anda pasti untuk memadam projek ini ?",
        type: "warning",
        html: true,
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya",
        cancelButtonText: "Batal",
        closeOnConfirm: false,
        showLoaderOnConfirm: true
    },
    function()
    {
        var ajax = new sack();
        ajax.requestFile = "/dev-team/projek/delete/" + id;
        Ajx(ajax);
    });
}
