$('#dataBody .change').click(function(){
	var newurl = '/index.php/Home/Admin/changestatus';
	var table_row = $(this).parent().parent();
	var uid = table_row.attr('id');
	$.ajax({
		type: "get",
		// url: "http://localhost/index.php/Home/Exam/addexamproblem",
		url: newurl,
		data: {"uid": uid},
		error: function(request) {
			alert("Connection error");
		},
		success: function(data) {
			if (data.status == 1) {
				alert('成功');
				var change_element = table_row.find('.status');
				change_element.text(change_element.text()^1);
			}
			else {
				alert('失败');
			}
		}
	})
});

$('#dataBody .up').click(function(){
	var table_row = $(this).parent().parent();
	var uid = table_row.attr('id');
	var change_element = table_row.find('.usertype');
	if (change_element.text()<3) {
		var newurl = '/index.php/Home/Admin/upusertype';
		$.ajax({
		type: "get",
		// url: "http://localhost/index.php/Home/Exam/addexamproblem",
		url: newurl,
		data: {"uid": uid},
		error: function(request) {
			alert("Connection error");
		},
		success: function(data) {
			if (data.status == 1) {
				alert('成功');
				change_element.text(parseInt(change_element.text())+1);
			}
			else {
				alert('失败');
			}
		}
	})
	}
	else {
		alert('权限已经达到最高');
	}
})

$('#dataBody .down').click(function(){
	var table_row = $(this).parent().parent();
	var uid = table_row.attr('id');
	var change_element = table_row.find('.usertype');
	if (change_element.text()>1) {
		var newurl = '/index.php/Home/Admin/downusertype';
		$.ajax({
		type: "get",
		// url: "http://localhost/index.php/Home/Exam/addexamproblem",
		url: newurl,
		data: {"uid": uid},
		error: function(request) {
			alert("Connection error");
		},
		success: function(data) {
			if (data.status == 1) {
				alert('成功');
				change_element.text(parseInt(change_element.text())-1);
			}
			else {
				alert('失败');
			}
		}
	})
	}
	else {
		alert('权限已经达到最高');
	}
})