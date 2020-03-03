<?php
namespace Icc\Utils;

use Closure;
use mysqli_result;

class Utils
{
    public static function get_post_parameters($exceptions = array(), $post = null) {
        if (empty($post) && empty($_POST))
            return array();
        if (empty($_POST))
            $_POST = $post;

        $result_arr = array();
        foreach ($_POST as $var) {
            if (!in_array($var, $exceptions) || $var != "submit")
                array_push($result_arr, $var);
        }
        return $result_arr;

    }

    public static function toArray(mysqli_result $mysqli_result) {
        $data = array();
        while ($array = $mysqli_result -> fetch_row()) {
            array_push($data, $array);
        }

        return $data;
    }

    public static function to_html_table(mysqli_result $mysqli_result, $header='', $from_index=0) {
        $data = '<table>';
        $data .= $header;
        while ($array = $mysqli_result -> fetch_row()) {
            $data .= '<tr>';
            for ($i = $from_index; $i < count($array); $i++)
                $data .= "<td>$array[$i]</td>";
            $data .= '</tr>';
        }
        $data .= '</table>';
        return $data;
    }

    public static function to_html_table_array(array $array, $header='', $style='', $from_index=0) {
//        if (!empty($style))
            $data = '<table class="' . $style . '">';
        $data .= $header;
        foreach ($array as $arr) {
            $data .= '<tr>';
            for ($i = $from_index; $i < count($arr); $i++)
                $data .= "<td>$arr[$i]</td>";
            $data .= '</tr>';
        }
        $data .= '</table>';
        return $data;
    }

    public static function sort_by($array, $sort_by) {
        $t = array_column($array, $sort_by);
        array_multisort($t, SORT_ASC, $array); //Yes, it is working somehow and I am not sure if it works right, but I want to sleep and I won't fix it today.
        return $array;
    }


    public static function number_to_words($number) {
//        echo $number;
        $one_digit = array("нуль", "один", "два", "три", "чотири", "п'ять", "шість", "сім", "вісім", "дев'ять");
        $ten_to_twenty_digit = array("десять", "одинадцять", "дванадцять", "тринадцять", "чотирнадцять", "п'ятнадцять", "шістнадцять", "сімнадцять", "дев'ятнадцять");
        $divided_by_ten_until_a_hundred = array("двадцять", "тридцять", "сорок", "п'ятдесят", "шістдесят", "сімдесят", "вісімдесят", "дев'яносто");
        $divided_by_hundred_until_a_thousand = array("сто", "двісті", "триста", "чотириста", "п'ятсот", "шістсот", "сімсот", "вісімсот", "дев'ятсот");
        $final_str = '';
        $value = (int) ($number / 1000);
        if ($value != 0) {
            if ($value % 10 == 1) $last = ' тисяча';
            else if ($value % 10 > 4 || $value % 10 == 0 ) $last = ' тисяч';
            else $last = ' тисячі';
            if ($value == 1) $final_str .= 'одна' . $last;
            else if ($value == 2) $final_str .= 'дві' . $last;
            else {
                if ($value < 1000) {
                    if ($value > 99) $final_str .= $divided_by_hundred_until_a_thousand[(int)($value / 100) - 1] . $last;
                    else if ($value > 19) {
                        $one = (int)($value % 10);
                        $one_str = '';
                        if ($one != 0) {
                            if ($one == 1) $one_str = 'одна';
                            else if ($one == 2) $one_str = 'дві';
                            else $one_str = $one_digit[$one];
                        }
                        $final_str .= $divided_by_ten_until_a_hundred[(int)($value / 10 % 10) - 2] . ' ' . $one_str . $last;
                    }

                    else if ($value >= 10) $final_str .= $ten_to_twenty_digit[(int)($value % 10)];
                    else if ($value <= 9) $final_str .= $one_digit[(int)($value % 10)];
            } else $final_str .= 'Unsupported number';
            }
        }
        $value = (int) ($number % 1000 / 100);
        if ($value != 0) {
            $final_str .= ' ';
            $final_str .= $divided_by_hundred_until_a_thousand[$value - 1];
        }
//        $final_str .= ' ';
        $value = (int)($number / 10 % 10);
        if ($value != 0) {
            $final_str .= ' ';
            $final_str .= $divided_by_ten_until_a_hundred[$value - 2];
        }
//        $final_str .= ' ';
        $value = (int) ($number % 10);
        if ($value != 0) {
            $final_str .= ' ';
            $final_str .= $one_digit[$value];
        }
//        echo $final_str;
        if ($final_str[0] == ' ') ltrim($final_str, $final_str[0]); //Actually ltrim is a really freaky thing, but I didn't find any appropriate function. Maybe I'll write something like that and I'll inform about it.
        if ($final_str[strlen($final_str) - 1] == ' ') $final_str[strlen($final_str) - 1] = '';
        return $final_str;
    }

    public static function reg_match($reg_exp, $str) {

    }

    /**
     * @param string $post_request_name
     * @return bool
     */
    public static function is_post_sent(string $post_request_name) {
        return isset($_POST[$post_request_name]);
    }

    /**
     * @param string $post_request_name
     * @param string $redirect_to
     */
    public static function on_post_request_redirect(string $post_request_name, string $redirect_to) {
        if (self::is_post_sent($post_request_name)) header("Location: " . $redirect_to);
    }

    public static function onPost(string $postRequestName, Closure $callback, ... $params): void {
        if (self::is_post_sent($postRequestName)) {
            $callback -> call(null, $params);
        }
    }

    /**
     * Replace all null value in its string representation.
     *
     * @param array $array
     */
    public static function cleanArrayFromNull(array &$array) {
        foreach ($array as &$value) {
            if ($value === null) {
                $value = "NULL";
            }
        }
    }


}