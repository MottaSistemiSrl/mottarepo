/*
**Video js created on 12-12-2012
*/

function showVideo()
{ 
	document.getElementById('vid').style.display="block"; 
	jwplayer().play(true);             
}

function stopVideo(){
           
           document.getElementById('vid').style.display="none"; 
           jwplayer().stop();  
            
 }
 
 function showHide(){
     alert("helooo");
 }

 
