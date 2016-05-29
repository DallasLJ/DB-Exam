$('#subbtn').click(function(){
	var arr = new Array();

	$('input:checkbox[name="pid"]:checked').each(function () {
		var	object = {
			'pid': this.value,
			'exam_id': examid
		}
    	arr.push(object);
	});
	console.log(arr);
	console.log(examid);
	
	var newurl = '/index.php/Home/Exam/addexamproblemwithoutorder';
	$.ajax({
		type: "post",
		// url: "http://localhost/index.php/Home/Exam/addexamproblem",
		url: newurl,
		data: {
			selectnums:arr,
		},
		error: function(request) {
			alert("Connection error");
		},
		success: function(data) {
			if (data.status == 1) {
				alert('添加题目成功');
				
			}
			else if (data.status == 2) {
				alert('添加题目存在已有现象');
			}
			else {
				alert('添加题目失败');
			}
		}
	})
});