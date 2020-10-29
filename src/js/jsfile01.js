/*var a=getElementById ("alert_me");
a.onclick ("hey dont alert me ");*/
var a=document.getElementById ("alert_me");
a.onclick=function (){
	alert ("hey dont alert me !!!");
};