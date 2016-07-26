<div class="row">
	<!-- Success Message -->
	<div id="success" class="hidden alert alert-success alert-dismissable">
  		<button class="close" data-dismiss="alert" area-hidden="true">&times</button>
	    <p id="smsg"></p>
	</div>
	<!-- Error Message -->
	<div id="error" class="hidden alert alert-danger alert-dismissable">
  		<button class="close" data-dismiss="alert" area-hidden="true">&times</button>
	    <p id="emsg"></p>
	</div>
</div>
<div class="row">
	<h3>Employees Panel</h3>
	<!-- Modal Add Employee -->
	<button type="button" onclick="resetForm()" class="btn btn-info pull-right emp_add" data-toggle="modal" data-target="#myModal">Add Employee</button>
	<!-- Modal Comfirm Delete -->
	  <div class="modal fade" id="myModalDelete" role="dialog">
	    <div class="modal-dialog modal-sm">
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Delete This Employee!!</h4>
	        </div>
	        <div class="modal-footer">
	          <button type="button" class="btn btn-default emp_delete_confirm">OK</button>
	          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        </div>
	      </div>
	    </div>
	  </div>
	<!-- Modal Employee Form-->
	  <div class="modal fade" id="myModal" role="dialog">
	    <div class="modal-dialog">
	      <!-- Modal content-->
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Employee Form</h4>
	        </div>
	        <div class="modal-body">
	          <form enctype="multipart/form-data" role="form" action="add" method="POST" id="FormId" class="Form_Ajax">
	          	<div class="form-group">
			      <label for="name">Name</label>
			      <input type="text" name="name" class="form-control emp_name" id="name" placeholder="Enter name" required>
			      <input type="hidden" class="emp_id" name="edit_id">
			    </div>
			    <div class="form-group">
			      <label for="email">Email:</label>
			      <input type="email" name="email" class="form-control emp_email" id="email" placeholder="Enter email" required="required">
			    </div>
			    <div class="form-group">
			      <label for="image">Image</label>
			      <input type="file" name="image" class="form-control" id="image" required="required">
			    </div>
			    <button type="submit" class="btn btn-default action">Submit</button>
			  </form>
	        </div>
	        <div class="modal-footer">
	          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        </div>
	      </div>
	      
	    </div>
	  </div>

	<table class="table table-hover items_table">
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Image</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php 
    	if(!empty($data['employees']))
    	{
    		foreach ($data['employees'] as $key => $value) {
    			echo 
    			"
    				<tr id='tr".$value['id']."'>
    					<td>".$value['id']."</td>
    					<td>".$value['name']."</td>
    					<td>".$value['email']."</td>
    					<td><a href='".PUBLIC_PATH."images/".$value['image']."' data-lightbox='img".$value['id']."'><img class='img-rounded' width='50' height='40' src='".PUBLIC_PATH."images/".$value['image']."' /></a></td>
    					<td>
    						<button type='button' class='btn btn-info emp_edit' data-toggle='modal' data-target='#myModal' data-name=".$value['name']." data-email=".$value['email']." data-id=".$value['id'].">Edit</button>
    						<button type='button' class='btn btn-danger emp_delete' data-id=".$value['id']." data-action='".PUBLIC_PATH."home/delete' data-toggle='modal' data-target='#myModalDelete'>Delete</button>
    					</td>
    				</tr>
    			";
    		}
    	}
    ?>
    </tbody>
  </table>
</div>

<script type="text/javascript">
$(document).ready(function(){
	//when click edit add this values to form 
	$(".emp_edit").click(function(){
		var name = $(this).data("name");
		var email = $(this).data("email");
		var id = $(this).data("id");
      	var action = $(".Form_Ajax").attr("action","edit");
      	//reset form before change data
		resetForm();
		$("#image").removeAttr("required");
		$(".emp_name").val(name);
		$(".emp_email").val(email);
		$(".emp_id").val(id);
		$('html, body').animate({ scrollTop: 0 }, 0);
	});

	//when click add buttom add required attr to image input and action of form to add function
	$(".emp_add").click(function(){
		$("#image").attr("required","required");
      	var action = $(".Form_Ajax").attr("action","add");
	});
});


//this function reset form by id
function resetForm()
{
    document.getElementById("FormId").reset();
}
</script>

<script type="text/javascript">
//When submit form in add/edit send data ajax
  $(document).ready(function(){
    $('.Form_Ajax').on('submit',function(){
      $.ajax({
        url:$(this).attr('action'),
        type:'POST',
        data: new FormData(this),
		contentType: false,
		cache: false,
		processData:false,  
        dataType:'json',
        success:function(data)
        {
	        if(data.add)
	        {
	          	resetForm();
	            $("#myModal").modal("hide");
	            var msg = data.name+" Added Successfully.";
	            success(msg);
	            $('html, body').animate({ scrollTop: 0 }, 0);
	            $('.items_table tbody').append("<tr id='tr"+data.id+"'><td class='emplo_id'>"+data.id+"</td><td class='emplo_name'>"+data.name+"</td><td class='emplo_email'>"+data.email+"</td><td class='emplo_img'><a href='"+data.url+"' data-lightbox='img"+data.id+"'><img class='img-rounded' width='50' height='40' src='"+data.url+"'/></a></td><td><button type='button' class='btn btn-info  emp_edit' data-toggle='modal' data-target='#myModal' data-name="+data.name+" data-email="+data.email+" data-id="+data.id+">Edit</button><button type='button' class='btn btn-danger emp_delete' data-id="+data.id+" data-action="+data.delete+"  data-toggle='modal' data-target='#myModalDelete'>Delete</button></td></tr>");
	         }
	        if(data.edit)
	        {
	          	resetForm();
	            $("#myModal").modal("hide");
	            var msg = data.name+" Updated Successfully.";
	            success(msg);
	            $('html, body').animate({ scrollTop: 0 }, 0);
	            var tr = "#tr"+data.id; 
	            $(tr).empty();
	            $(tr).append("<td class='emplo_id'>"+data.id+"</td><td class='emplo_name'>"+data.name+"</td><td class='emplo_email'>"+data.email+"</td><td class='emplo_img'><a href='"+data.url+"' data-lightbox='img"+data.id+"'><img class='img-rounded' width='50' height='40' src='"+data.url+"'/></a></td><td><button type='button' class='btn btn-info  emp_edit' data-toggle='modal' data-target='#myModal' data-name="+data.name+" data-email="+data.email+" data-id="+data.id+">Edit</button> <button type='button' class='btn btn-danger emp_delete' data-id="+data.id+" data-action="+data.delete+"  data-toggle='modal' data-target='#myModalDelete'>Delete</button></td>");
	        }
	        if(data.error)
	        {
	            $("#myModal").modal("hide");
	        	var msg = "There is something wrong in your image please choose (jpg , jpeg , png , gif).";
	            error(msg);
	            $('html, body').animate({ scrollTop: 0 }, 0);
	        }
	    }
      });
      return false;
    });

    //when click to delete employee
    $(".emp_delete").click(function(){
		var id     = $(this).data("id");
		var action = $(this).data("action");
    	$(".emp_delete_confirm").attr("data-id",id);
    	$(".emp_delete_confirm").attr("data-action",action);
    });

    //when confirm to delete employee and send data by ajax
    $(".emp_delete_confirm").click(function(){
		var id = $(this).data("id");
		var action = $(this).data("action");
    	$.ajax({
        url:action,
        type:'POST',
        data: {id:id}, 
        dataType:'json',
        success:function(data)
        {
	        if(data.delete)
	        {
	            $("#myModalDelete").modal("hide");
	            var msg = "Employee Deleted Successfully.";
	            success(msg);
	            $('html, body').animate({ scrollTop: 0 }, 0);
	            var tr = "#tr"+data.id; 
	            $(tr).empty();
	        }else{
	        	var msg = "Employee Not Deleted Successfully.";
	            error(msg);
	            $('html, body').animate({ scrollTop: 0 }, 0);
	        }
	    }
      });
      return false;
    });
  });

//This is a success message 
function success(msg)
{
 	$("#success").removeClass("hidden");
 	$("#smsg").text(msg);
}

//this is an error message
function error(msg)
{
 	$("#error").removeClass("hidden");
 	$("#emsg").text(msg);
}
</script>