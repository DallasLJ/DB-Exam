$('#subbtn').click(function(){
	var pidbool = true;
	//alert($('#proid').val());
	for (var i = 0; i < $('.problem_id').length; i++) {
		if($('.problem_id:eq('+i+')').text() == $('#proid').val()) {
			pidbool = false;
			break;
		}
	}
	if(pidbool) {
	$.ajax({
		type: "post",
		// url: "http://localhost/index.php/Home/Exam/addexamproblem",
		url: "/index.php/Home/Exam/addexamproblem",
		data: $('#addprb').serialize(),
		error: function(request) {
			alert("Connection error");
		},
		success: function(data) {
			if (data.status == 1 && $('#dataBody').length != 0) {
				var str = '';
				for (var i = 0; i < $('input').length; i++) {
					if(i == 1) {
						str += '<td class=\"problem_id\">' + $('input:eq('+i+')').val() + '</td>';
					}
					else {
						str += '<td>' + $('input:eq('+i+')').val() + '</td>';
					}
				}
				str += '<td><p id="' + $('input:eq(1)').val() + '">删除</p></td>';
				var strbody = '';
				strbody += '<tr>' + str + '</tr>'
				$('#dataBody').append(strbody);
				alert('添加题目成功');
			}
			else if($('#dataBody').length == 0) {
				var strtable = '<table class="bordered"><thead><tr><th>考试id</th><th>题目id</th><th>题目序号</th><th>最大提交次数</th><th>删除</th></tr></thead><tbody id="dataBody">';
				var str = '';
				for (var i = 0; i < $('#prbinput input').length; i++) {
					if(i == 1) {
						str += '<td class=\"problem_id\">' + $('input:eq('+i+')').val() + '</td>';
					}
					else {
						str += '<td>' + $('input:eq('+i+')').val() + '</td>';
					}
				}
				str += '<td><p id="' + $('input:eq(1)').val() + '">删除</p></td>';
				var strbody = '';
				strbody += strtable + '<tr>' + str + '</tr>' + '</tbody></table>';
				$('#problemlist').empty();
				alert('添加题目成功');
				$('#problemlist').append(strbody);
				
			}
			else {
				alert('添加题目失败');
			}
		}
	})
	return false;
	}
	else{
		alert("考试中已有相同题目");
	}
});

$('#dataBody p').click(function(){
	var pid = $(this).attr('id');
	var newurl = '/index.php/Home/Exam/deleteexamproblem/eid/' + eid + '/order/' + pid;
	var table_row = $(this).parent().parent();

	$.ajax({
		type: "get",
		// url: "http://localhost/index.php/Home/Exam/addexamproblem",
		url: newurl,
		error: function(request) {
			alert("Connection error");
		},
		success: function(data) {
			if (data.status == 1) {
				table_row.remove();
				alert('题目删除成功');
				
			}
			else {
				alert('题目删除失败');
			}
		}
	})
});