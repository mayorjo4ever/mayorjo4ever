$(function(){
			 // send request to fetch all bible 
			 load_books($('.book_ref'));
			 
			 $('div.verse_ref p').on('click',function(){
				 $(this).css('font-weight','bold');
			 });
			  
			 
			 $('input.text-search').keypress(function (e) {
				 var key = e.which;
				 if(key == 13)  // the enter key code
				  {
					e.preventDefault(); elem = 'div.verse_ref'; texts = $(this).val(); 
					if(texts == ""){ alert('No Scripture To Read'); }
					else {readBookbySearch(texts,elem); }					
				  }
				}); 
				/***************************/
				$('button.read-book').on('click',function(e){
					e.preventDefault(); elem = 'div.verse_ref'; texts = $('input.text-search').val();
					if(texts == ""){ alert('No Scripture To Read ' ); }
					else {readBookbySearch(texts,elem); }	
				});
				/***************************/
				$('button.search-book').on('click',function(e){
					e.preventDefault(); 
					elem = 'div.verse_ref'; texts = $('input.text-search').val();
					if(texts == ""){ alert('No Text To Search' ); }
					else {	searchBibleWords(texts,elem);  }					
				});
				/***************************/	 
				$('.datepicker').datepicker({
						// format : 'yyyy-mm-dd hh:ii:ss',
						 format : 'yyyy-mm-dd',						 
						 autoclose : true,
						 todayBtn:  1,
						 todayHighlight: 1,
					});
				/***************************/
				count_total_messages('span.total_notes');
				/***************************/
		}); // end jQuery 
		
		
		function load_books(elem){
			$.ajax({
				url: "ajax.php",
				type: "POST",
				data: { load_books:"all"},
				cache: false,
				beforeSend : function (){  console.log('fetching bibles'); }, 
				success: function(response) {
					elem.html(response);	$('div.chp_ref').html('');						
					}
			  }); 
			}
		
		
		
		function save_bible_passage(){ 
			passage = $('input.text-search').val(); 
			if(passage!=""){ $.ajax({
					url: "ajax.php", type: "POST",	data: { save_bible_passage:"this", passage:passage  },								
					cache: false, beforeSend : function (){  console.log('saving '+passage); }, 
					success: function(response) { alert(response);	}	 
				});  
				}
			}
		
		/********************************/
		
		function get_bible_passage(elem){ 			
			 $.ajax({
					url: "ajax.php", type: "POST",	data: { get_bible_passage:"all" },								
					cache: false, beforeSend : function (){  },       
					success: function(response) { $(elem).html(response);	}	 
				});  				
			}
		
		/********************************/
		function load_chp(book_id,elem){
			 
				$.ajax({
					url: "ajax.php",
					type: "POST",
					data: { load_chapters:"all", book_id:book_id},
					cache: false,
					beforeSend : function (){  console.log(book_id); }, 
					success: function(response) {
						// alert(response); 
						$(elem).html(response); $('div.vs_ref').html('');				
					}
			  }); 

		}		
		/********************************/
		function load_vs(book_id, chapter, elem){
			 
				$.ajax({
					url: "ajax.php",
					type: "POST",
					data: { load_verses:"all", book_id:book_id, chapter:chapter },
					cache: false,
					beforeSend : function (){  console.log(book_id); }, 
					success: function(response) {
						// alert(response); 
						$(elem).html(response);							
					}
			  }); 

		}
		/********************************/
		function set_active_list(body,child){			
                 $(body + ' ul > li').removeClass('active');
                 $(child).addClass('active');    
				 console.log($(child)); 
		}
		/********************************/
		function setIds(texts,elem){ 
			// create ID for selected bible references 			
			$(elem).val(texts); 			
		}
		
		function show_verse(elem){
			id = $('#vid1').val()+$('#vid2').val()+$('#vid3').val();
			$.ajax({
					url: "ajax.php",type: "POST",data: { show_verse:"all", id:id  },					
					cache: false, beforeSend : function (){  console.log(id); }, 
					success: function(response) { $(elem).html(response);	}	 
				}); 
			  
			var scT = $('p.active'); var conT = $(elem); 			  
			conT.animate({ scrollTop:scT.offset().top - conT.offset().top + conT.scrollTop(),scrollLeft:0},0); 		
		}
		/********************************/
		function readBookbySearch(texts,elem){ 
			$.ajax({
					url: "ajax.php", type: "POST",	data: { read_text_verse:"all", book:texts  },								
					cache: false, beforeSend : function (){  console.log(texts); }, 
					success: function(response) { $(elem).html(response);	}	 
				});  
			}
		/********************************/
		function searchBibleWords(texts,elem){ 
			$.ajax({
					url: "ajax.php", type: "POST",	data: { search_bible_words:"all", texts:texts  },							
					cache: false, beforeSend : function (){ $(elem).html('Searching, Plese Wait...'); }, 
					success: function(response) { $(elem).html(response);	}	 
				});  
			}
		/********************************/
		function addPreliminaries(){ 
			 $('span.date').html($('input.datepicker').val());
			 $('span.preacher').html($('input.preacher').val());
			 $('span.topic').html($('input.topic').val());
			 $('span.note-title').html($('input.note-title').val());
			}
		/********************************/	
		function delete_bible_ref(){
			 if(confirm("Do you want to delete all saved bible references ? ")){
				  $.ajax({
					url: "ajax.php", type: "POST",	data: { delete_bible_passage:"all" },								
					cache: false, beforeSend : function (){  },       
					success: function(response) {  get_bible_passage('.bible_ref');	}	 
				});  
			 }
		}
		/********************************/	
		function get_message_params(){
			
			var _notes = $('textarea.note_messages').val(); 
			var date = $('span.date').html();
			var preacher = $('span.preacher').html();
			var topic = $('span.topic').html();
			var note_title = $('span.note-title').html();
			
			return [date,_notes,preacher,topic,note_title]; 
			// return ['note':_notes,'date':date,'preacher':preacher,'topic':topic,'title':note_title]; 
		}
		
		function reset_message_params(){
			
			  $('textarea.note_messages').val(''); 
			  $('span.date').html('');
			  $('span.preacher').html('');
			  $('span.topic').html('');
			  $('span.note-title').html('');
			
			return [date,_notes,preacher,topic,note_title]; 
			// return ['note':_notes,'date':date,'preacher':preacher,'topic':topic,'title':note_title]; 
		}
		
		function  save_message_draft(){
			variables = get_message_params(); 
			   $.ajax({
					url: "ajax.php", type: "POST",	data: { save_message_as_draft:"all", variables:variables },								
					cache: false, beforeSend : function (){  },       
					success: function(response) { alert(response); get_bible_passage('.bible_ref');	}	 
				});  
		}
		
		/********************************/	
		function  save_message_final(){
			variables = get_message_params();
			 if(confirm("Do you want to finalize your message notes ")){
				  
			  $.ajax({
					url: "ajax.php", type: "POST",	data: { save_message_as_final:"all", variables:variables },								
					cache: false, beforeSend : function (){  },       
					success: function(response) { alert(response); reset_message_params(); get_bible_passage('.bible_ref');
					
					}	 
				});  
			 } // end if 
		}
		
		function count_total_messages(elem){ 			
			 $.ajax({
					url: "ajax.php", type: "POST",	data: { count_total_messages:"all" },								
					cache: false, beforeSend : function (){  },       
					success: function(response) { $(elem).html(response);	}	 
				});  				
			}
		
		