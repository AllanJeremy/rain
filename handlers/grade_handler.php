<?php

//Class that deals with interpreting grade digits into meaningful imap_headerinfo
class GradeHandler
{
    /*Integers representing the percentages needed per grade*/
    const A_GRADE = 90;
    const A_MINUS_GRADE = 85;
    const B_PLUS_GRADE = 80;
    const B_GRADE = 75;
    const B_MINUS_GRADE = 70;
    const C_PLUS_GRADE = 65;
    const C_GRADE = 60;
    const C_MINUS_GRADE = 55;
    const D_PLUS_GRADE = 50;
    const D_GRADE = 45;
    const D_MINUS_GRADE = 40;
    const E_GRADE = 39;

    /*Strings representing what text is displayed for each grade*/
    const A_GRADE_TEXT = "A PLAIN";
    const A_MINUS_GRADE_TEXT = "A- [A MINUS]";
    const B_PLUS_GRADE_TEXT = "B+ [B PLUS]";
    const B_GRADE_TEXT = "B PLAIN";
    const B_MINUS_GRADE_TEXT = "B- [B MINUS]";
    const C_PLUS_GRADE_TEXT = "C+ [C PLUS]";
    const C_GRADE_TEXT = "C PLAIN";
    const C_MINUS_GRADE_TEXT = "C- [C MINUS]";
    const D_PLUS_GRADE_TEXT = "D+ [D PLUS]";
    const D_GRADE_TEXT = "D PLAIN";
    const D_MINUS_GRADE_TEXT = "D- [D MINUS]";
    const E_GRADE_TEXT = "E";

    //Returns the grade info ~ Grade text as well as the percentage
    public static function GetGradeInfo($grade_input,$max_grade)
    {
        $percentage = ($grade_input/$max_grade)*100;
        $grade_text = "";


        try
        {
            //Assign grade texts based on grade achieved
            if($percentage > self::A_GRADE) # A Grade achieved
            {
                $grade_text = self::A_GRADE_TEXT;
            }
            else if ($percentage > self::A_MINUS_GRADE) # A- Grade achieved
            {
                $grade_text = self::A_MINUS_GRADE_TEXT;
            }
            else if ($percentage > self::B_PLUS_GRADE) # B+ Grade achieved
            {
                $grade_text = self::B_PLUS_GRADE_TEXT;
            }
            else if ($percentage > self::B_GRADE) # B Grade achieved
            {
                $grade_text = self::B_GRADE_TEXT;
            }
            else if ($percentage > self::B_MINUS_GRADE) # B- Grade achieved
            {
                $grade_text = self::B_MINUS_GRADE_TEXT;
            }
            else if ($percentage > self::C_PLUS_GRADE) # C+ Grade achieved
            {
                $grade_text = self::C_PLUS_GRADE_TEXT;
            }
            else if ($percentage > self::C_GRADE) # C Grade achieved
            {
                $grade_text = self::C_GRADE_TEXT;
            }
            else if ($percentage > self::C_MINUS_GRADE) # C- Grade achieved
            {
                $grade_text = self::C_MINUS_GRADE_TEXT;
            }
            else if ($percentage > self::D_PLUS_GRADE) # D+ Grade achieved
            {
                $grade_text = self::D_PLUS_GRADE_TEXT;
            }
            else if ($percentage > self::D_GRADE) # D Grade achieved
            {
                $grade_text = self::D_GRADE_TEXT;
            }
            else if ($percentage > self::D_MINUS_GRADE) # D- Grade achieved
            {
                $grade_text = self::D_MINUS_GRADE_TEXT;
            }
            else
            {
                $grade_text = self::E_GRADE_TEXT;
            }

            //Once all the details have been set
            $grade_info = array("grade_text"=>$grade_text,"percentage"=>$percentage);

            return $grade_info;#return the grade info
        }#end of try
        catch (Exception $e)
        {
            echo "Error occured ".$e;
            return false;
        }
    }
};
