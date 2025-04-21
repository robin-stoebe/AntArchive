$(document).ready(function() {



   // is this file loading and running jquery?

   //$('div').css('border', '2px solid orange');	// yep











	$('.add-button').click(function(){
        var buttonLevel = $(this).data('level');

        $('.members-select option:selected').each( function() {


			// find which option was selected and from which list
			var selIndex = $(this).parent().prop('selectedIndex');
			if (!selIndex)
				var selIndex = $(this).parent().parent().prop('selectedIndex');		// when dept 'optgroups' were added for individuals they were one more level down

			var selectType = $(this).parent().data('type');
			if (!selectType)
				var selectType = $(this).parent().parent().data('type');

			var optionValue = $(this).val();


			// compare new entry with each li in the list for that level to see if it already exists
			var foundInLevel = false;
			var listID = "#Add-" + buttonLevel;
			$(listID).find('li').each( function() {

				// extract these data fields from the entries already in the list
				eachName = $(this).data('name');
				eachType = $(this).data('type');

				if (eachName == optionValue && eachType == selectType)
					foundInLevel = true;

          });

			console.log(' foundInLevel = ' + foundInLevel );

          if (!foundInLevel)
          {

        	acl_candidate_id = $('input:hidden[name=\'ACL[category_id]\']').val();

			createACL(acl_candidate_id, selectType, buttonLevel, optionValue).done(function(data)
			{
				//console.log("Data: " + data + "\nStatus: " + status);
				$(listID).append(data);

				//$('#results li:last-child').addClass('bg-light');		// optional highlighting of newly added item

			});

          }


        });

       $('.members-select option:selected').css('background', '#eee');
       $('.members-select').val('');    // clear selected item; couldn't find a better way to do it

      });



	function createACL(category_id, type, level_id, name)
	{
		return $.ajax({
			url: "?r=a-c-l/create",
			type: "post",
			data: { 'ACL[category_id]': category_id,
					'ACL[type]': type,
					'ACL[level_id]': level_id,
					'ACL[name]': name
 			}
		});
	}






	$(document).on('click','.remove-button',function(e) {

		listItemToRemove = $(this).closest('li');
	    var aclId = $(listItemToRemove).data('id');

	    //if (confirm('Are you sure you want to delete this ACL record? (#' + aclId + ')'))		// confirmation may be needed for more critical operations
	    if (1)
	    {

			deleteACL(aclId).done(function(data)
			{
				//console.log("Data: " + data + "\nStatus: " + status);
				$(listItemToRemove).css('background','tomato');
				$(listItemToRemove).fadeOut(500, function(){
					$(listItemToRemove).remove();
				});
			});

		}

		return false;

	});



	function deleteACL(id)
	{
		return $.ajax({
			url: "?r=a-c-l/delete&id=" + id,
			type: "post",
			data: ""
		});
	}




















	function getRemoteData(type, id)
	{
		//console.log( 'getRemoteData(' + type + ', ' + id + ')' );

		//var url = '?r=' + type + '/list-members-j-s-o-n';
		var url = '?r=' + type + '/list-members-h-t-m-l';

	    return $.ajax({
	        url: url,
	        data: { 'id': id },
	        //dataType: 'json'
		});

	}



	//$('.details-popover').on('show.bs.popover', function (e) {
	//    var type = $(this).data('type');
	//    var id = $(this).data('id');
	//    getRemoteData(type, id);
	//});

	$(document).on('show.bs.popover', '.details-popover', function(e) {
	    var type = $(this).data('type');
	    var id = $(this).data('id');

		getRemoteData(type, id).done(function(data) {

			var listHTML = "";
			var stuff = JSON.parse(data);

			stuff.forEach(function(item) {
				listHTML += "<li class='list-group-item bg-light p-1'>" + item + "</li>";
			});

			$('.popover-body').html("<ul class='list-group'>" + listHTML + "</ul>");

		});


	});




 $(document).popover({

    selector: '.details-popover',
		trigger: 'focus',
		placement: 'bottom',
	    html: true,
		container: 'body',
	    content: '',
	    template: '<div class="popover bg-info"><div class="arrow"></div><h3 class="popover-header bg-info"></h3><div class="popover-body p-1"></div><div class="popover-footer"></div></div>'

});












$('.details-modal').click(function(){
	var id = this.id;
	var splitid = id.split('_');
	var userid = splitid[1];

	var type = 'group';
	var id = userid;


	var type = $(this).data('type');
	var id = $(this).data('id');
	var title = $(this).data('title');


	getRemoteData(type, id).done(function(data) {

		$('.modal-title').html(title);

		$('.modal-body').html(data);
		$('#empModal').modal('show');

	});

 });









});













