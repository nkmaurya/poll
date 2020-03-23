$(document).ready(function(){
    $('#add_btn').click(function(){
      let removeBtn = '<div class="removeBtn col-lg-1 col-md-1">'+'<label>&nbsp;</label>'+
         '<a class="btn btn-danger" href="javascript:;" onclick="removeElement(this)" >- Remove</a></div>';
      if ($("#answers").children().length <= 1)  { 
        $("#first_answer").append(removeBtn);
      }
      let newElement = $("#first_answer").clone();
      //$(newElement).append(removeBtn);
      $("#answers").append(newElement);
    });

});


function removeElement(element){
    $(element).parent().parent().remove();
    if ($("#answers").children().length <= 1) {
        $(".removeBtn").remove();
    }
}

function deleteRecord(event){
  var x = confirm("Are you sure want to delete this record?");
  if (x){
    return true;
  } else {
    event.preventDefault(); 
  }
}
