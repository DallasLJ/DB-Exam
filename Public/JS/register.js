var regEmail = /^[A-Za-zd]+([-_.][A-Za-zd]+)*@([A-Za-zd]+[-.])+[A-Za-zd]{2,5}$/;
var regPhone = /^1[34578]\d{9}$/;
var regPassword = /^([a-zA-Z0-9@*#]{6,22})$/;

function validateForm() {
	var userid = document.forms["registerForm"]["userid"].value;
	var password = document.forms["registerForm"]["password"].value;
	var repassword = document.forms["registerForm"]["repassword"].value;
	var usertype = document.forms["registerForm"]["usertype"].value;
	var username = document.forms["registerForm"]["username"].value;
	var email = document.forms["registerForm"]["email"].value;
	var phone = document.forms["registerForm"]["phone"].value;

	//测试
	// console.log(userid) ;
	if (!userid) {
		alert("请输入正确的用户学号！");
		return false;
	};
	if (regPassword.test(password)==false) {
		alert("请输入合乎规范的密码！");
		return false;
	};
	if (!repassword) {
		alert("请再次输入密码！");
		return false;
	};
	if (!usertype) {
		alert("请输入您的账号类型，学生为1，老师为2！");
		return false;
	};
	if (password!=repassword) {
		alert("两次输入的密码不一致！");
		return false;
	};
	if (!username) {
		alert("请输入正确的姓名！");
		return false;
	}
	if (regEmail.test(email)==false) {
		alert("请输入正确的邮箱");
		return false;
	};
	if (regPhone.test(phone)==false) {
		alert("请输入正确的手机号");
		return false;
	};
	document.forms["registerForm"]["password"].value = hex_md5(password);
	document.forms["registerForm"]["repassword"].value = hex_md5(password);
}
