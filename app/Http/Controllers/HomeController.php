<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    

    /**
     * Check the syntax of some PHP code.
     * @param string $code PHP code to check.
     * @return boolean|array If false, then check was successful, otherwise an array(message,line) of errors is returned.
     */
    function php_syntax_error($code){
        if(!defined("CR"))
            define("CR","\r");
        if(!defined("LF"))
            define("LF","\n") ;
        if(!defined("CRLF"))
            define("CRLF","\r\n") ;
        $braces=0;
        $inString=0;
        foreach (token_get_all('<?php ' . $code) as $token) {
            if (is_array($token)) {
                switch ($token[0]) {
                    case T_CURLY_OPEN:
                    case T_DOLLAR_OPEN_CURLY_BRACES:
                    case T_START_HEREDOC: ++$inString; break;
                    case T_END_HEREDOC:   --$inString; break;
                }
            } else if ($inString & 1) {
                switch ($token) {
                    case '`': case '\'':
                    case '"': --$inString; break;
                }
            } else {
                switch ($token) {
                    case '`': case '\'':
                    case '"': ++$inString; break;
                    case '{': ++$braces; break;
                    case '}':
                        if ($inString) {
                            --$inString;
                        } else {
                            --$braces;
                            if ($braces < 0) break 2;
                        }
                        break;
                }
            }
        }
        $inString = @ini_set('log_errors', false);
        $token = @ini_set('display_errors', true);
        ob_start();
        $code = substr($code, strlen('<?php '));
        $braces || $code = "if(0){{$code}\n}";
        if (eval($code) === false) {
            if ($braces) {
                $braces = PHP_INT_MAX;
            } else {
                false !== strpos($code,CR) && $code = strtr(str_replace(CRLF,LF,$code),CR,LF);
                $braces = substr_count($code,LF);
            }
            $code = ob_get_clean();
            $code = strip_tags($code);
            if (preg_match("'syntax error, (.+) in .+ on line (\d+)$'s", $code, $code)) {
                $code[2] = (int) $code[2];
                $code = $code[2] <= $braces
                    ? array($code[1], $code[2])
                    : array('unexpected $end' . substr($code[1], 14), $braces);
            } else $code = array('syntax error', 0);
        } else {
            ob_end_clean();
            $code = false;
        }
        @ini_set('display_errors', $token);
        @ini_set('log_errors', $inString);
        return $code;
    }

    public function getFormula(){
        return view('formulas.index');
    }

    public function index(Request $request)
    {
        $x=1;
        $y=2;
        $z=3;
        $a=5;
        $b=6;


        if($request->formula)
        {
            try
            {
                $test = $request->formula;//'2+3*pi-minOfTwo($x,$y)';

                // Remove whitespaces
                $test = preg_replace('/\s+/', '', $test);

                $number = '(?:\d+(?:[,.]\d+)?|pi|π)'; // What is a number
                $functions = '(?:sinh?|cosh?|tanh?|abs|acosh?|asinh?|atanh?|exp|log10|deg2rad|rad2deg|sqrt|ceil|floor|round)'; // Allowed PHP functions
                $operators = '[+\/*\^%-]'; // Allowed math operators
                $regexp = '/^(('.$number.'|'.$functions.'\s*\((?1)+\)|\((?1)+\))(?:'.$operators.'(?2))?)+$/'; // Final regexp, heavily using recursive patterns

                //if (preg_match($regexp, $q))
                if ($regexp)
                {
                    $test = preg_replace('!pi|π!', 'pi()', $test); // Replace pi with pi function
                    // $isvalidexp = $this->php_syntax_error($test);
                    // if($isvalidexp){
                    //     echo "<h4>ERROR : </h4>";
                    // }
                    // else
                        eval('$result = '.$test.';');
                }
                else
                {
                    $result = false;
                }
                echo "<h4>Result : </h4>";
                print_r($result);
            }
            catch(\Exception $e){
                echo "<h4>ERROR : </h4>";
                return $e->getMessage();
            }
        }
        

        // print_r(eval('1 + 2 * 3 * ( 7 * 8 ) - ( 45 - 10 ) - cmin(4,0)'));exit;
        // $parser = new \Math\Parser();
        // $expression = '1 + 2 * 3 * ( 7 * 8 ) - ( 45 - 10 ) - cmin(4,0)';
        // $result = $parser->evaluate($expression);

        // echo $result; //302
        //return view('home');
    }
}
