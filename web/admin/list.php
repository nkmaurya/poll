<div id="page-wrapper">
            <div id="page-inner">
                
                <div class="row">
                    <div class="col-md-12">
                     <h2>Listing</h2>   
                    </div>
                </div>              
                 <!-- /. ROW  -->
                  <hr>
                  <?php if(isset($success) && $success==1) { ?>
                    <div class="row">
                    <div class="col-lg-9 col-md-9">
                  <div class="alert alert-success">
                    <strong>Success!</strong> Request has been processed successfuly.
                </div>
                </div></div>
                  <?php } ?>

                  <?php if(isset($sessionExpired) && $sessionExpired==1) { ?>
                    <div class="row">
                    <div class="col-lg-9 col-md-9">
                  <div class="alert alert-danger">
                    <strong>Warning!</strong> Your session expired.
                </div>
                </div></div>
                  <?php } ?>

                  <div class="col-lg-6 col-md-6">
                        <a href="index.php?action=add" class="btn btn-success">Add Poll</a>
                        <br>
                        <br>


                    </div>
                  <div class="row">
                    <div class="col-lg-9 col-md-9">
                    <?php if ($result) { ?> 
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Question</th>
                                    <th>Expiration Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($result as $key => $row) { ?>
                                <tr>
                                    <td><?php echo ++$key; ?></td>
                                    <td><a href="?action=edit&rid=<?php echo urlencode(encrypt($row['id'])); ?>" class="poll_link" ><?php echo $row['question']; ?></a></td>
                                    <td><?php echo date("F jS, Y", strtotime($row['expiration_date'])); ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else {
    echo "No Poll created yet! please add.";
}  ?> 
                    </div>
                     
                 <!-- /. ROW  -->           
    </div>
             <!-- /. PAGE INNER  -->
            </div>