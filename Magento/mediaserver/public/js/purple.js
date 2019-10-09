/**
 * funzione che effettua il cambio di slide
 * @param slide_time_id
 * @param slide_path
 */
function changeSlide(slide_time_id, slide_path) {
	$('#slide_time_id').val(slide_time_id);
	$('#slide_selected').attr('src', slide_path);
}

/**
 * richiama il metodo di cambio slide e, contestualmente esegue il seek del video
 * @param slide_time_id
 * @param slide_path
 * @param seconds
 */
function changeSlideAndSetTime(slide_time_id, slide_path, seconds) {
	changeSlide(slide_time_id, slide_path);
	var myPlayer = videojs('videojs_id');
	// aumento il valore di seconds di 1 secondo per assicurarmi di non far sballare le slide con un cambio e ricambio dovuto ad arrotondamenti
	// che occorrono nel recupero del currentTime sull'evento di seeked
	seconds++;
	myPlayer.currentTime(seconds);
}

/**
 * functione richiamato o dall'evento di seeked, o dal timer interno JS per valutare un cambio di slide o meno
 * @param video_id
 * @param force
 */
function getCurrentSlide(video_id, force) {
	var myPlayer = videojs('videojs_id');
	var isPlaying = !myPlayer.paused();
	// se il video e' attivo, eseguo la richiesta
	if (isPlaying || force) {
		var seconds = myPlayer.currentTime();
		var url = "/slides/currentslide";
	    $.ajax({
	        cache: false,
	        type: 'GET',
	        url: url,
	        dataType: "json",
			data: { video_id : video_id, seconds: seconds },
	        success: function (j) {
	            if (j.slide_time_id !== "undefined" && j.slide_time_id != $('#slide_time_id').val()) {
	            	changeSlide(j.slide_time_id, j.slide_path);
	            }
	        }
	    });
	}
}

/**
 * blocco document.ready con le funzioni da richiamare per recuperare le informazioni di video, slides e avviare il timer
 * per il check periodico delle slide
 */
$( document ).ready(function() {
	if ($("#videojs_id").length > 0) {
		var product_id = $("#product_id").val();
		var myPlayer = videojs('videojs_id');
		$.getJSON("/video/jsonDetails?product_id=" + product_id, function( data ) {
			myPlayer.src({ type: "video/mp4", src: data.video_path });
			// gestisco le slides times
			if (data.video_with_slides == 1) {
				$('#slides_container').show();
				var count = 0;
				$.each(data.slides_times, function(i) {
					if (count == 0) {
						changeSlide(data.slides_times[i]['slide_time_id'], data.slides_times[i]['slide_path']);
					}
					var li_html = '<li><span style="cursor: pointer;" onclick="changeSlideAndSetTime(\'' + data.slides_times[i]['slide_time_id'] + '\', \'' + data.slides_times[i]['slide_path'] + '\', \'' + data.slides_times[i]['slide_opening_time'] + '\');"><img src="' + data.slides_times[i]['slide_path_thumb'] + '"></img></span></li>';
					$('#slides_times_container').after(li_html);
					count++;
				});
				// avvio il sistema di monitoraggio del cambio slide
				var slidePoller = setInterval(function () { getCurrentSlide(data.video_id, false) }, 2500);
			}
			// gestisco l'evento di ended
			videojs("videojs_id").ready(function() {
				// implemento la funzione di end del video
				this.on("ended", function() {
					console.log('finished');
				});
				// triggero l'evento di seeked
				this.on("seeked", function() {
					getCurrentSlide(data.video_id, true);
				});
			});
		});
	}
});


//EDITOR.PHTML

//aggiunta tempo slide
function addTime(slide_id, video_id, auth_token)
{
	var opening_time = getPlayerSeconds();	
	    
	if(opening_time == '-1')
	{
		alert('il video non e\' attivo');
		return;
	}	
	else
	{	
		/*if($('#stbox_' + slide_id).length > 0) {		            	
        	var old_st_id = $("#stbox_" + slide_id).children("a").attr("id");        	
        	deleteTime(old_st_id, slide_id, false);
    	}*/
    	
		
	    $.ajax
	    ({ 
	        url: '/slidestimes/addtime',
	        data: {"slide_id": slide_id, "opening_time" : opening_time, 'auth_token' : auth_token},
	        dataType: 'json',
	        type: 'post',
	        success: function(result)
	        {        	 
	        	populateBoxSlidesWithTimes(video_id, auth_token);
	            //alert(result['message']);	            
	            /*var obj = result['st']; 	            
	            if(obj.slide_time_id != null)
	            {	            	
	            	var showmex = true;
		        	var idbox = "slideid_"+slide_id;
		        	var boxst = '<a href="javascript: void(0)" onclick="deleteTime('+obj.slide_time_id+', '+slide_id+', '+showmex+')" id="'+obj.slide_time_id+'">[x]</a>';
		        	boxst+= '<span>'+obj.slide_opening_time+'</span>';        	
		        	$('#stbox_'+slide_id).html(boxst);
	            }*/	            
	        }
	    });
	}   
}

function getPlayerSeconds()
{	
	var myPlayer = videojs('videojs_editor_id');
	var isPlaying = !myPlayer.paused();
	// se il video e' attivo, eseguo la richiesta	
	if (isPlaying)
	{		
		var seconds = myPlayer.currentTime();
		return Math.round(seconds);
	}	
	else
	{	
		return -1
	}
}

//cancellazione tempo della slide
function deleteTime(slide_time_id, video_id, auth_token){	
    $.ajax({ 
        url: '/slidestimes/deletetime',
        data: {"slide_time_id": slide_time_id, "auth_token" : auth_token},
        type: 'post',
        success: function(result)
        {
        	//alert(result);
        	populateBoxSlidesWithTimes(video_id, auth_token);
        }
    });        
}


function populateBoxSlidesWithTimes(video_id, auth_token)
{		
	if(video_id != null && video_id != '')
	{	
		$.ajax
	    ({ 
	        url: '/slidestimes/getSlidesWithTimes',
	        data: {"video_id": video_id},
	        type: 'post',
	        dataType: 'json',
	        success: function(result)
	        {	        	
		        var showmex = true;		     
	        	var html = '<table>';
	        	html += "<tr>";  	        	
	        	for (var i = 0; i < result.length; i++) {
	        	    var data = result[i];
	
	        	    html += "<td>";
	        	    html += "<img src='" + data.slide_path + "' border=0 height=50 /><br />";
	        	    html += "[" + data.slide_opening_time + "]<br />";
	        	    html += '<a href="javascript: void(0)" onclick="deleteTime(' + data.slide_time_id + ', ' + video_id + ', \'' + auth_token + '\')">[remove]</a>';
	        	    html += "</td>";	        	    	        	  
	        	}		        	     
	
	        	html += "</tr>";
	        	html += "</table>";  
	
	        	$('#slidewithtimebox').html('');
	        	$('#slidewithtimebox').html(html); 	  
	        }
	    });
	}	
}
//-------- EDITOR.PHTML