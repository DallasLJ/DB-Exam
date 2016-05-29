function transformPassword() {
	var password = document.forms["loginForm"]["password"].value;
	document.forms["loginForm"]["password"].value = hex_md5(password);
}