<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2>Add a Poll</h2>
            </div>
        </div>
        <!-- /. ROW  -->
        <hr>
        <form action="index.php?action=<?php echo $action; ?>" id="add-poll" method="POST">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="form-group required">
                        <label class="control-label">Question</label>
                        <textarea class="form-control" name="poll_ques" required><?php echo isset($result['question'])? $result['question']: ""; ?></textarea>
                        <input class="form-control" type="hidden" value="<?php echo isset($rid)? $rid: ""; ?>" name="rid">
                        <p class="help-block">Help text here.</p>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2">
                    <div class="form-group required">
                        <label class="control-label">Expiration Data</label>
                        <input class="form-control" value="<?php echo isset($result['expiration_date'])? $result['expiration_date']: " "; ?>" type="date" name="poll_expiration_date" required>
                        <p class="help-block">Help text here.</p>
                    </div>
                </div>
            </div>
            <hr>
            <div id="answers">
                <?php if (isset($result['options']) && is_array($result['options'])) {
    foreach ($result['options'] as $key => $row) { ?>
                    <div class="row first_answer" id="first_answer">
                        <div class="col-lg-3 col-md-3">
                            <div class="form-group required">
                                <label class="control-label">Answer</label>
                                <input class="form-control" value="<?php echo isset($row['answer'])? $row['answer']: " "; ?>" name="answer[]" required>
                                <!--<input class="form-control" type="hidden" value="<?php echo isset($row['option_id'])? $row['option_id']: " "; ?>" name="answer[]" required>-->
                                <p class="help-block">Help text here.</p>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <div class="form-group required">
                                <label class="control-label">Color Code</label>
                                <input class="form-control" value="<?php echo isset($row['color_code'])? $row['color_code']: " "; ?>" name="answer_color[]" placeholder="#xxxxxx" type="color" required>
                                <p class="help-block">Help text here.</p>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <div class="form-group required">
                                <label class="control-label">Order</label>
                                <input class="form-control" value="<?php echo isset($row['display_order'])? $row['display_order']: " "; ?>" name="answer_order[]" type="number">
                                <p class="help-block">Help text here.</p>
                            </div>
                        </div>
                        <div class="col-lg-1 col-md-1">
                            <div class="form-group">
                                <label>Counter</label>
                                <input class="form-control" value="<?php echo isset($row['vote_counter'])? $row['vote_counter']: " "; ?>" name="answer_counter[]" >
                            </div>
                        </div>
                        <?php if (count($result['options']) > 1) {?>
                            <div class="removeBtn col-lg-1 col-md-1">
                                <label>&nbsp;</label>
                                <a class="btn btn-danger" href="javascript:;" onclick="removeElement(this)">- Remove</a>
                            </div>
                            <?php } ?>

                    </div>

                    <?php }
} else if($action == "add") { ?> 

<div class="row first_answer" id="first_answer">
                        <div class="col-lg-3 col-md-3">
                            <div class="form-group required">
                                <label class="control-label">Answer</label>
                                <input class="form-control" value="<?php echo isset($row['answer'])? $row['answer']: " "; ?>" name="answer[]" required>
                                <!--<input class="form-control" type="hidden" value="<?php echo isset($row['option_id'])? $row['option_id']: " "; ?>" name="answer[]" required>-->
                                <p class="help-block">Help text here.</p>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <div class="form-group required">
                                <label class="control-label">Color Code</label>
                                <input class="form-control" value="<?php echo isset($row['color_code'])? $row['color_code']: " "; ?>" name="answer_color[]" placeholder="#xxxxxx" type="color" required>
                                <p class="help-block">Help text here.</p>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <div class="form-group required">
                                <label class="control-label">Order</label>
                                <input class="form-control" value="<?php echo isset($row['display_order'])? $row['display_order']: " "; ?>" name="answer_order[]" type="number">
                                <p class="help-block">Help text here.</p>
                            </div>
                        </div>
                        <div class="col-lg-1 col-md-1">
                            <div class="form-group">
                                <label>Counter</label>
                                <input class="form-control" value="<?php echo isset($row['vote_counter'])? $row['vote_counter']: " "; ?>" name="answer_counter[]" >
                            </div>
                        </div>
                        
                        

                    </div>
<?php } ?>
            </div>
            <div class="row">
                <div class="col-lg-10 col-md-10">
                    <a href="javascript:;" id="add_btn" class="btn btn-success">+ Add More</a>
                </div>

            </div>
            <hr>
            <div class="row">
                <div class="col-lg-8 col-md-8" style="text-align:center">
                    <input class="form-control" type="hidden" name="token" value="<?php echo $_SESSION['csrf_token'] ?>" readonly>
                    <input class="form-control" type="hidden" name="action" value="<?php echo $action ?>" readonly>
                    <button id="save_btn" type="submit" class="btn btn-primary">&nbsp;&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php if (isset($result['options']) && is_array($result['options'])) { ?>
                 <a class="btn btn-danger" href="?action=delete&rid=<?php echo urlencode($rid); ?>"  onclick="deleteRecord(event)">&nbsp;&nbsp;&nbsp;Delete&nbsp;&nbsp;&nbsp;</a>
                    <?php } ?>
                </div>

            </div>
        </form>
    </div>
    <!-- /. ROW  -->
</div>
<!-- /. PAGE INNER  -->