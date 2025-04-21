$(document).ready(function() {



   // is this file loading and running jquery?

   //$('div').css('border', '1px solid orange');	// yep









//$.ajaxSetup({
//  cache:false
//});




	$('#file-upload-form')
		.submit( function( e ) {

    	e.preventDefault();			// don't actually submit the form, we'll handle it here


		var selectedFile = $('#selected-file').val().trim();
		var selectedType = $('#selected-type').val().trim();
	
		if (selectedFile && selectedType) 
		{


		    $.ajax( {
    	    	//url: 'https://httpbin.org/post', // a public, HTTP POST sandbox that returns the POST data
        		url: '?r=file/ajax-file-upload',
        		type: 'POST',
	        	data: new FormData( this ),
    	    	processData: false,
	    		contentType: false,
				dataType: 'json',
		    } )

			    .done(function(data){
						console.log('done: %o', data);

				        if (data)
						{
							newFileRow = "<tr class='fileRow'>" +
											"<td>"+ data.type_name +"</td>" +
											"<td><a href='?r=file/show-document&id="+ data.id +"' target='_blank'>"+ data.filename  +"</a></td>" +
											"<td>"+ data.description +"</td>" +
											"<td>"+ data.poststamp +"</td>" +
											"<td>"+ data.filesize_bytes +" | "+ data.filetype +"</td>" +
											"<td>"+ data.uploader_uname +"</td>" +
        		                        	"<td><button class='btn btn-danger remove-file-button' type='button' data-file-id='" + data.id + "' data-filename='" + data.filename + "'>Remove</button></td>" +
                		                "</tr>";

							$('#fileTable > tbody:last-child').append(newFileRow);

							$('#file-table-div').removeClass('d-none');																				// show the files list table if it were previously empty

							$('#file-upload-form').trigger('reset');																				// so they don't re-upload the same file
							$('#file-upload-form .selected-file-indicator').html('<span class=\'text-muted\'>Select a file to upload</span>');		// remove the label that shows which file they've selected

							$('#no-files-message').html('');																						// remove the message saying there are no files yet

						}

        		});

				
		}


	  } );






	// do this in  $(document).on("click")  so that newly created remove buttons will also get this listener

	$(document).on("click", "button.remove-file-button", function(){

	    var fileId = $(this).attr("data-file-id");
	    var fileFilename = $(this).attr("data-filename");



		fileId = 3677;		// try this; an actual file id but don't actually delete it



	    if (confirm('Are you sure you want to delete the file ' + fileFilename + '?'))
	    {

			alert('ok, going to delete #' + fileId );
	
			row_to_delete = $(this).closest("tr");


			$.ajax({
				method: "POST",
				url: "?r=file/delete&id=" + fileId
			  })
				.done(function(data, status) {

					console.log("Data: " + data + "\nStatus: " + status);

					row_to_delete.fadeTo(1000, 0.01, function(){
						$(this).slideUp(150, function() {
							$(this).remove();
						});
					});
					
				});


		}

		
		return false;

	});










});
