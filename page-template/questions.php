<?php


/**
 * Template Name: Questions
 */

get_header();

?>
<div id="content-vw">
    <div class="container">
        <div class="middle-align">
            <div class="loader-parent">
                <div class="loader"></div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <section id="cb-questions">

                        <?php

                        require get_template_directory() . '/page-template/message-error.php';
                        
                        require get_template_directory() . '/page-template/message-info.php';
                        if(!isset($_SESSION['cb_info_inprogress'])) {
                            if (isset($questionArr['questions'])) {
                                ?>
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <i class="fa fa-info-circle fa-fw fa-lg"></i>
                                        <strong>Note:</strong> Please answer the questions below to finish confirming your identity with TransUnion,Equifax and Experian. You have three attempts to respond correctly or you must wait <b>30 days</b> before trying again. Each attempt is timed. <br><br>In order to pull data from TransUnion,Equifax and Experian, Rapid Credit Reports, Inc requires this information. The details you provide here - aside from your name - are never stored within our system.
                                    </div>
                                </div>
                               
                                <div class="col-md-12">
    
                                    <form action="" method="post" name="frmQuestions">
    
                                        <?php
                                            $i = 0;
                                            $questions = $questionArr['questions'];
    
                                            ?>
                                        <?php foreach ($questions as $k => $v) : ++$i;
    
                                                ?>
                                            <div class="row question-list">
                                                <div class="col-md-6">
                                                    <h5 class="vc_custom_heading section-title left">Question <?php echo $i; ?></h5>
                                                    <p id="item<?php echo $i; ?>" class="q_series"><?php echo $v->FullQuestionText; ?></p>
                                                    <input type="hidden" name="QuestionId[]" value="<?php echo $v->QuestionId; ?>" autocomplete="off">
                                                </div>
                                                <div class="col-md-6" id="options">
    
                                                    <?php foreach ($v->AnswerChoice as $ak => $a) : ?>
                                                        <div class="iRadio">
                                                            <input type="radio" name="AnswerChoiceId<?php echo $i; ?>" value="<?php echo $a->AnswerChoiceId; ?>" required="">
                                                            <label for=""><?php echo $a->AnswerChoiceText; ?></label>
                                                        </div>
                                                    <?php endforeach; ?>
    
                                                </div>
                                            </div>
    
                                        <?php endforeach; ?>
    
                                        <div class="row submit-questions">
                                            <div class="col-md-3">
                                                <input type="submit" id="authenticate-questions" name="authenticate-questions" class="btn btn-secondary btn-lg btn-3b-theme" />
                                            </div>
                                        </div>
    
                                    </form>
                                </div>
    
    
                            <?php
                            }
                        }
                       
                        ?>

                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>