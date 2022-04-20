<?php


/**
 * Template Name: Demo Questions
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

                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle fa-fw fa-lg"></i>
                                <strong>Note:</strong> Please answer the questions below to finish confirming your identity with TransUnion,Equifax and Experian. You have three attempts to respond correctly or you must wait <b>30 days</b> before trying again. Each attempt is timed. <br><br>In order to pull data from TransUnion,Equifax and Experian, Rapid Credit Reports, Inc requires this information. The details you provide here - aside from your name - are never stored within our system.
                            </div>
                        </div>

                        <div class="col-md-12">

                            <form action="" method="post" name="frmQuestions">


                                <div class="row question-list">
                                    <div class="col-md-6">
                                        <h5 class="vc_custom_heading section-title left">Question 1</h5>
                                        <p id="item1" class="q_series">Which of the following is a current or previous employer?</p>
                                    </div>
                                    <div class="col-md-6" id="options">

                                        <div class="iRadio">
                                            <input type="radio" name="AnswerChoiceId1" value="1" required="">
                                            <label for="">A. G. Edwards</label>
                                        </div>

                                        <div class="iRadio">
                                            <input type="radio" name="AnswerChoiceId1" value="2" required="">
                                            <label for="">Bingham Mccutchen</label>
                                        </div>

                                        <div class="iRadio">
                                            <input type="radio" name="AnswerChoiceId1" value="3" required="">
                                            <label for="">Iec</label>
                                        </div>

                                        <div class="iRadio">
                                            <input type="radio" name="AnswerChoiceId1" value="4" required="">
                                            <label for="">Milliken</label>
                                        </div>

                                        <div class="iRadio">
                                            <input type="radio" name="AnswerChoiceId1" value="5" required="">
                                            <label for="">None of the Above</label>
                                        </div>
                                        

                                    </div>
                                </div>

                                <div class="row question-list">
                                    <div class="col-md-6">
                                        <h5 class="vc_custom_heading section-title left">Question 2</h5>
                                        <p id="item2" class="q_series">What is the monthly payment of your student loan?</p>
                                    </div>
                                    <div class="col-md-6" id="options">

                                        <div class="iRadio">
                                            <input type="radio" name="AnswerChoiceId2" value="1" required="">
                                            <label for="">$ 601 - $ 650</label>
                                        </div>

                                        <div class="iRadio">
                                            <input type="radio" name="AnswerChoiceId2" value="2" required="">
                                            <label for="">$ 651 - $ 700</label>
                                        </div>

                                        <div class="iRadio">
                                            <input type="radio" name="AnswerChoiceId2" value="3" required="">
                                            <label for="">$ 701 - $ 750</label>
                                        </div>

                                        <div class="iRadio">
                                            <input type="radio" name="AnswerChoiceId2" value="4" required="">
                                            <label for="">$ 751 - $ 800</label>
                                        </div>

                                        <div class="iRadio">
                                            <input type="radio" name="AnswerChoiceId2" value="5" required="">
                                            <label for="">None of the Above</label>
                                        </div>
                                        
                                    </div>
                                </div>


                                <div class="row question-list">
                                    <div class="col-md-6">
                                        <h5 class="vc_custom_heading section-title left">Question 3</h5>
                                        <p id="item3" class="q_series">Which of these street names are you associated with?</p>
                                    </div>
                                    <div class="col-md-6" id="options">

                                        <div class="iRadio">
                                            <input type="radio" name="AnswerChoiceId3" value="1" required="">
                                            <label for="">123rd</label>
                                        </div>

                                        <div class="iRadio">
                                            <input type="radio" name="AnswerChoiceId3" value="2" required="">
                                            <label for="">Bond</label>
                                        </div>

                                        <div class="iRadio">
                                            <input type="radio" name="AnswerChoiceId3" value="3" required="">
                                            <label for="">Cleveland</label>
                                        </div>

                                        <div class="iRadio">
                                            <input type="radio" name="AnswerChoiceId3" value="4" required="">
                                            <label for="">Pine Lake</label>
                                        </div>

                                        <div class="iRadio">
                                            <input type="radio" name="AnswerChoiceId3" value="5" required="">
                                            <label for="">None of the Above</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row submit-questions">
                                    <div class="col-md-3">
                                        <input type="submit" id="authenticate-questions" name="authenticate-questions" class="btn btn-secondary btn-lg btn-3b-theme" />
                                    </div>
                                </div>

                            </form>
                        </div>

                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>