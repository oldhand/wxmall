// JavaScript Document
$(function(){	
    Oalert($('.btn-ft'),$('.alert'));
	
	
function mmenuURL()//主导航、二级导航显示函数
{
var thisURL = document.URL;
tmpUPage = thisURL.split( "/" );  
thisUPage_s = tmpUPage[ tmpUPage.length-2 ]; 
thisUPage_s= thisUPage_s.toLowerCase();//小写
//thisUPage=thisUPage.substring(0,4)

		if(thisUPage_s=="test.hichina.com"||thisUPage_s=="www.net.cn"||thisUPage_s=="www.hichina.com")
		{
			getObject("mm1").className="menuhover"
			getObject("mb1").className = "";
		}
		else if(thisUPage_s=="domain")
		{
			getObject("mm2").className="menuhover"
			getObject("mb2").className = "";
		}
		else if(thisUPage_s=="hosting")
		{
			getObject("mm3").className="menuhover"
			getObject("mb3").className = "";
		}	
		else if(thisUPage_s=="mail")
		{
			getObject("mm4").className="menuhover"
			getObject("mb4").className = "";
		}
		else if(thisUPage_s=="solutions"||thisUPage_s=="site"){
			getObject("mm5").className="menuhover"
			getObject("mb5").className = "";
		}
		else if(thisUPage_s=="promotion"){
			getObject("mm6").className="menuhover"
			getObject("mb6").className = "";
		}
		else if(thisUPage_s=="trade"||thisUPage_s=="phonetic"||thisUPage_s=="switchboard"||thisUPage_s=="note"){
			getObject("mm7").className="menuhover"
			getObject("mb7").className = "";
		}
		else if(thisUPage_s=="benefit"){
			getObject("mm8").className="menuhover"
			getObject("mb8").className = "";
		}
		else if(thisUPage_s=="userlogon"||thisUPage_s=="domain_service"||thisUPage_s=="hosting_service"||thisUPage_s=="mail_service"||thisUPage_s=="Payed"||thisUPage_s=="unPayed"||thisUPage_s=="Invoice"||thisUPage_s=="Finance"||thisUPage_s=="RegInfoModify"){
			getObject("mm9").className="menuhover"
			getObject("mb9").className = "";
		}
		else
		{
			getObject("mm1").className="";
			getObject("mb1").className = "";
		}
}

window.load=mmenuURL()

});