function GetRTime(){
  var StartTime = new Date(starttime);
  var EndTime = new Date(endtime);
  var NowTime = new Date();
  var temp = NowTime.getTime() - StartTime.getTime();
  var t = EndTime.getTime() - NowTime.getTime();
  var d=0;
  var h=0;
  var m=0;
  var s=0;
  if (temp >= 0) {
    if(t >= 0){
      d=Math.floor(t/1000/60/60/24);
      h=Math.floor(t/1000/60/60%24);
      m=Math.floor(t/1000/60%60);
      s=Math.floor(t/1000%60);
    }
    else {
      alert('考试结束');
      window.history.back(-1);
    } 
  }
  else {
    alert('考试未开始');
    window.history.back(-1);
  }
    document.getElementById("t_d").innerHTML = d + "天";
    document.getElementById("t_h").innerHTML = h + "时";
    document.getElementById("t_m").innerHTML = m + "分";
    document.getElementById("t_s").innerHTML = s + "秒";
  }
  setInterval(GetRTime,1000);