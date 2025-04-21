$(document).ready(function() {



   // is this file loading and running jquery?

   //$('div').css('border', '1px solid orange');	// yep












    $('.save-acl-button').click(function(){
        input_div = $(this).parents('.acl-input-row');
        //input_div.css('border', '2px dotted purple');

        formData = "";


        acl_candidate_id = $(input_div).find('input:hidden[name=\'ACL[category_id]\']').val();
        acl_type = $(input_div).find('select[name=\'ACL[type]\']').val();
        acl_level_id = $(input_div).find('select[name=\'ACL[level_id]\']').val();
        acl_name = $(input_div).find('select[name=\'ACL[name]\']').val();

      console.log(acl_candidate_id + ' | ' + acl_type + ' | ' + acl_level_id + ' | ' + acl_name);



        $.post("?r=a-c-l/ajax-manage",
        {
			'action': 'create',
			'ACL[category_id]': acl_candidate_id,
			'ACL[type]': acl_type,
			'ACL[level_id]': acl_level_id,
			'ACL[name]': acl_name
        },
        function(data, status){

          console.log("Data: " + data + "\nStatus: " + status);

    				data = JSON.parse(data);

                   newAclRow = "<tr><td   class='d-none'   >" + data.id + "</td><td   class='d-none'   >" + data.category_id + "</td><td>" + data.level_name + "</td><td>" + data.type + "</td><td>" + data.name + "</td><td><button class='btn btn-danger remove-acl-button' type='button' data-acl-id='" + data.id + "' data-acl-name='" + data.name + "'>Remove</button></td></tr>";

                  $('#aclTable > tbody:last-child').append(newAclRow);

                  //$('#acl-form').trigger('reset');

        });






    });







// do this in  $(document).on("click")  so that newly created remove buttons will also get this listener

$(document).on("click", "button.remove-acl-button", function(){

    var aclId = $(this).attr("data-acl-id");
    var aclName = $(this).attr("data-acl-name");

    if (confirm('Are you sure you want to delete this ACL record? (#' + aclId + ')'))
    {

		  row_to_delete = $(this).closest("tr");

      $.post("?r=a-c-l/ajax-manage",
      {
			  'action': 'delete',
			  'id': aclId
		  },
      function(data, status){
			  console.log("Data: " + data + "\nStatus: " + status);
			  row_to_delete.remove();
      });

    }

    return false;
});



















var options_string = '';

  for(var acl_type in acl_options) {
    options_string += '<option value="' + acl_type + '"';

    if (!Object.keys(acl_options[acl_type]).length)
    {
      options_string += ' disabled';
    }

    options_string += '>' +  capitalize_first(acl_type) + '</option>';
  }

$('#ACL\\[type\\]').empty().append(options_string);







function getOptionsOfType(selected_type)
{
  var options_string = '';

  //for(var acl_type in options) {
    for(var acl_name in acl_options[selected_type]) {
      if (acl_options[selected_type].hasOwnProperty(acl_name)) {
          console.log(selected_type + ' | ' + acl_name + ' | ' + acl_options[selected_type][acl_name]);
          //document.write(acl_type + ' | ' + acl_name + ' | ' + acl_options[acl_type][acl_name] + '<br>');
        options_string += '<option value="' + acl_name + '">' +  acl_options[selected_type][acl_name] + '</option>';
      }
    }
  //}

  return options_string;
}





$('#ACL\\[type\\]').on('change', function() {
  var options = getOptionsOfType(this.value);
  $('#ACL\\[name\\]').empty().append(options);

  $('.acl_type_label_text').text(capitalize_first(this.value));

} ).trigger( 'change' );



$('#name-select').on('change', function() {
  var selected_value = this.value;
  var selected_text = $("option:selected", this).text();;
  $('#some-info').html(selected_value + ' | ' + selected_text);
});







});





function capitalize_first(string)
{
	return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
}


