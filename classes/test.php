<?php

#HANDLES TEST RELATED FUNCTIONS
class Test
{
    //Variable initialization

    //Constructor
    function __construct()
    {
        
    }

    //Displays a test, inclusive of all questions
    public static function DisplayTest($test_id)
    {

    }

    //Displays a question, depending on whether the question exists in the database or not
    public static function DisplayEditQuestion($test_id,$question_id,$question_count)
    {
        ?>
            <!--Test SubTitle section-->
            <div class="row grey darken-2 z-depth-1">
                <div class="container">
                    <div class="row no-margin">
                        <div class="col s12 m4 center-align">
                            <p class="white-text">Question <span class="php-data"><?php echo $question_id; ?></span> of <?php echo $question_count; ?></p>
                        </div>
                        <div class="col s12 m4 center-align">
                            <p class="white-text">Time left: <span class="php-data">1:00</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!--Test creation - editing section-->
            <div class="row">
                <div class="container">
                    <p class="grey-text">Question Info | Tip : Select the answer(s) to the question by selecting in your question options </p>
                    <div class="divider"></div><br>
                    <div class="row">
                        <div class="col s12">
                            <label for="test_question">Question</label>
                            <textarea class="materialize-textarea" id="test_question" placeholder="Enter question here"></textarea>
                        </div>
                    </div>
                    
                    <!--Question type-->
                    <p class="grey-text text-darken-2">Question type</p>
                    <div class="row">
                        <div class="col s12 m4">
                            <input name="test_question_type" type="radio" id="test_qtype_single" checked/>
                            <label for="test_qtype_single">Single Choice Question</label>
                        </div>
                        <div class="col s12 m4">
                            <input name="test_question_type" type="radio" id="test_qtype_multiple" />
                            <label for="test_qtype_multiple">Multiple Choice Question</label>
                        </div>

                    </div>
                    <div class="divider"></div>

                    <br><br>

                    <!--Single choice question-->
                    <div class="row single_choice_question">
                        <p class="grey-text text-darken-2">Single choice Question</p>
                        <div class="divider col s12"></div><br>
                        <!--Default settings for the question-->
                        <div class="col s12 m6">
                            <label for="no_of_choices">Number of choices</label>
                            <input type="number" value="1" min="1" max="8" id="no_of_choices" required/>
                        </div>
                        <div class="col s12 m6">
                            <label for="question_marks">Marks attainable</label>
                            <input type="number" value="5" min="1" max="20" id="question_marks" required/>
                        </div>
                        
                        <p class="grey-text text-darken-2">Options</p>
                        <div class="divider col s12"></div><br>

                        <!--Options-->
                        <div class=" col s12">
                            
                                <input type="radio" name="option_group" id="option_1" class="valign">
                                <label for="option_1">Option 1</label>
                                <input placeholder="Option 1">
                            
                                <input type="radio" name="option_group" id="option_2" class="valign">
                                <label for="option_2">Option 2</label>
                                <input placeholder="Option 2">
                            
                                <input type="radio" name="option_group" id="option_3" class="valign">
                                <label for="option_3">Option 3</label>
                                <input placeholder="Option 3">
                            
                        </div>
                    </div>
                    <br><br>
                    <!--Multiple choice question-->
                    <div class="row multiple_choice_question">
                        <p class="grey-text text-darken-2">Multiple choice Question</p>
                        <div class="divider col s12"></div><br>
                        <!--Default settings for the question-->
                        <div class="col s12 m6">
                            <label for="no_of_choices">Number of choices</label>
                            <input type="number" value="1" min="1" max="8" id="no_of_choices" required/>
                        </div>
                        <div class="col s12 m6">
                            <label for="question_marks">Marks attainable</label>
                            <input type="number" value="5" min="1" max="20" id="question_marks" required/>
                        </div>
                        
                        
                        <p class="grey-text text-darken-2">Options</p>
                        <div class="divider col s12"></div><br>

                        <!--Options-->
                        <div class=" col s12">
                            
                                <input type="checkbox" name="option_group" id="m_option_1" class="valign">
                                <label for="m_option_1">Option 1</label>
                                <input placeholder="Option 1">
                            
                                <input type="checkbox" name="option_group" id="m_option_2" class="valign">
                                <label for="m_option_2">Option 2</label>
                                <input placeholder="Option 2">
                            
                                <input type="checkbox" name="option_group" id="m_option_3" class="valign">
                                <label for="m_option_3">Option 3</label>
                                <input placeholder="Option 3">
                            
                        </div>
                    </div>
                    
                    <!--Open ended choice question | to be implemented later on as an update-->
                    <div class="row open_ended_choice_question">
                    </div>
                    
                    <div class="row">
                        <div class="col s4 left">
                            <a class="btn  disabled" href="javascript:void(0)">PREVIOUS QUESTION</a>
                        </div>
                        <div class="col s4 right">
                            <a class="btn right" href="javascript:void(0)">NEXT QUESTION</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php
    }
};?>