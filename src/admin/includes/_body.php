	<div class="container">
	  <div class="ls_div">
		
		<form role="form" class="ls_form" method="post">
		  <div class="form-group ls_form_group">
			<input type="text" class="form-control ls_input" id="keyword" placeholder="Type to start searching" autocomplete="off">
		  </div>
		</form>
		
		<div class="ls_result_div" id="ls_result">
			<div class="ls_result_main"> 
				<ul class="ls_result_ul"></ul>
			</div>
		</div>
		
	  </div>
	</div><!-- /.container -->
		
	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">Search Result</h4>
		  </div>
		  <div class="modal-body">
			...
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<!-- <button type="button" class="btn btn-primary">Save changes</button> -->
		  </div>
		</div>
	  </div>
	</div>
	
	<div class="container">
		<div class="row">
            <div class="col-lg-12" id="loading_gif_div">
                <img src="../assets/images/ajax-loader.gif"/>
            </div>

			<div class="col-lg-12" id="table_div">

			</div>
		</div>
	</div>