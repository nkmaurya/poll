<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Today's Poll
                    </div>
                   
                    <div class="panel-body">
                    <?php if ($result && !$showResult) {  ?>

                        <div class="col-lg-10 col-md-10">
                        <?php if ($voted) { ?>
                        
                                        You have already voted for this poll.
                        <?php } else { ?>
                            <div class="row">
                                <div class="col-lg-10 col-md-10" class="ques">
                                    <div class="form-group">
                                        <p>
                                            <label class="custom-control-label">Question:&nbsp;&nbsp;&nbsp; </label><?php echo isset($result['question'])? $result['question']: ""; ?></p>
                                    </div>
                                </div>
                            </div>
                            <form action="" method="POST">
                                <div class="row">
                                <input class="form-control" type="hidden" name="token" value="<?php echo $_SESSION['csrf_token'] ?>" readonly>
                                <input  value="<?php echo encrypt($result['id']); ?>" type="hidden" name="id">
                                <input class="form-control" type="hidden" name="action" value="<?php echo $action ?>"  readonly >
                                <?php if (isset($result['options']) && is_array($result['options'])) {
    foreach ($result['options'] as $key => $row) { ?> 
                                    <div class="col-lg-10 col-md-10">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" value="<?php echo encrypt($row['option_id']); ?>" class="custom-control-input" id="defaultUnchecked" name="option_id">
                                            <label class="custom-control-label" for="defaultUnchecked"><?php echo isset($row['answer'])? $row['answer']: ""; ?></label>
                                        </div>
                                    </div>
                                <?php }
}  ?>
                                    <div class="col-lg-8 col-md-8">
                                        <button id="save_btn" class="btn btn-primary">&nbsp;&nbsp;&nbsp;&nbsp;Vote&nbsp;&nbsp;&nbsp;&nbsp;</button>&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div>
                                </div>

                            </form>
                            <?php } ?>
                        </div>
                        
                        <?php } elseif ($result && $showResult) {  ?>
                            <div class="row">
                            <div class="col-lg-10 col-md-10" style="text-align:center" class="result"> 
                        <p>
                                        <b>    <?php echo isset($result['question'])? $result['question']: ""; ?></b></p>  
                        </div></div>
                        <?php if (isset($result['options']) && is_array($result['options'])) {
    foreach ($result['options'] as $key => $row) {
        $votePercentage = calculateVotePercentage($result['totalVote'], $row['vote_counter']); ?>     
                                        <div class="row">
                             <div class="col-lg-10 col-md-10" class="result">                              
                            <div class="col-lg-2 col-md-2" >
                                <label class="custom-control-label" for="defaultUnchecked"><?php echo $row['answer']; ?></label>
                            </div>

                            <div class="col-lg-6 col-md-6">
                                <div class="custome-progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $votePercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $votePercentage; ?>%;background-color:<?php echo $row['color_code']; ?>">
                                        <span><?php echo $votePercentage; ?>%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1" style="text-align:left" >
                                <span ><?php echo $row['vote_counter']; ?></span>
                            </div>
                            </div></div>
                            <?php
    }
}  ?>
                            <div class="row">
                            <div class="col-lg-10 col-md-10" class="result">   
                            <div class="col-lg-2 col-md-2" >
                                <label class="custom-control-label" for="defaultUnchecked">&nbsp;</label>
                            </div>
                            <div class="col-lg-6 col-md-6" >
                                <label class="custom-control-label" for="defaultUnchecked">&nbsp;</label>
                            </div>
                            <div class="col-lg-1 col-md-1" style="text-align:left;" >
                                <span ><b><?php echo isset($result['totalVote'])? $result['totalVote']: ""; ?></b></span>
                            </div>
                                    </div>   
                            </div>
                        
                        <?php } else {
    echo "No Poll available for today";
}  ?>
                    </div>

                </div>

            </div>
        </div>
        <!-- /. ROW  -->
    </div>
    <!-- /. PAGE INNER  -->
</div>