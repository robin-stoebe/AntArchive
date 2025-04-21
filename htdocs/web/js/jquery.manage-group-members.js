$(document).ready(function() {



   // is this file loading and running jquery?

   //$('div').css('border', '2px solid orange');	// yep










var options = {
	valueNames: [ 'fullname', 'dept' ],
};


var myList = new List('my-list', options);





$('#searchInput').keyup(function() {
   var searchString = $(this).val();
   myList.search(searchString, ['fullname']);    // only search this  field
                                                            // try 'a'  should only return 'a' names, not statistics
});



// Assigning references to the filter buttons

//var filterDeptButton = document.getElementById("filterDept");
var filterDeptButton = document.getElementsByClassName("filterDept");

var removeFiltersButton = document.getElementById("removeFilters");

// When the filter button is clicked. The list is filtered by calling the filter function of the list object and passing in a function that accepts the list items.

//filterDeptButton.addEventListener("click", function() {


for (var i = 0; i < filterDeptButton.length; i++)
{
    filterDeptButton[i].addEventListener('click', function() {
      var dataDept = this.dataset.dept;
      myList.filter(function(item) {
        if (item.values().dept == dataDept) {
          return true;
        } else {
          return false;
        }
      });
    });
}





$('select#filterDept').change(function () {
    var selected = $('option:selected', this).attr('value');

	if (selected)
		myList.filter(function(item) {
	        if (item.values().dept == selected) {
	          return true;
	        } else {
	          return false;
	        }
		});
	else
		myList.filter();

  })
  .change();




/*

// replaced 'remove filters button' with 'All Depts' option in Select which will call the empty filter function

// When the remove filter button is clicked, the filters are removed by calling the filter function once again with no parameters.
removeFiltersButton.addEventListener("click", function() {
  myList.filter();
});
*/











$(document).on('click', '.list-js-item:not(.active)', function(e) {

	$(this).toggleClass('active');     // do checkmark?

	var groupID = $('#results').data('groupID');

	dataUname = $(this).data('uname');
	dataType = $(this).data('type');
	dataFullname = $(this).data('fullname');
	dataDepartment = $(this).data('department');

	console.log( ' createGroupMember(' + groupID + ', ' + dataType + ', ' + dataUname + ') ' );


	createGroupMember(groupID, dataType, dataUname).done(function(data)
	{
		//console.log("Data: " + data + "\nStatus: " + status);
		$('#results').append(data);

		$('#results li:last-child').addClass('bg-light');		// optional highlighting of newly added item

	});


});



	function createGroupMember(group_id, type, uname)
	{
		return $.ajax({
			url: base+"/group-member/create",
			type: "post",
			data: { 'GroupMember[group_id]': group_id,
					'GroupMember[type]': type,
					'GroupMember[uname]': uname }
		});
	}









$(document).on('click', '.remove-button', function(e) {

	var groupID = $('#results').data('groupID');

	var listItemToRemove = $(this).closest('li');
	var groupMemberId = $(listItemToRemove).data('id');
	var uname = $(listItemToRemove).data('uname');

	deleteGroupMember(groupMemberId).done(function(data)
	{
		//console.log("Data: " + data + "\nStatus: " + status);
   		$(listItemToRemove).removeClass('bg-light');
   		$(listItemToRemove).css('background','tomato');
			$(listItemToRemove).fadeOut(500, function(){
			  $(listItemToRemove).remove();
				$('.list-js-item[data-uname="' + uname + '"]').toggleClass('active');
			});
	});

});



	function deleteGroupMember(id)
	{
		return $.ajax({
			url: base+"/group-member/delete?id=" + id,
			type: "post",
			data: ""
		});
	}








	/*

    $('#process').click(function(){

		$('ul#results li.list-group-item').each( function() {

			var dataUsername = $(this).data('username');

			//alert(dataUsername);

          //$('#process-results').append(' <div class="border p-1 m-1"> Level:' + dataLevel + ' Uname:' + dataUname + ' Type:' + dataType + '</div>');

		});

    });

	*/













});




