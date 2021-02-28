<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    

    public function index()
    {
        $x=1;
        $y=1;
        $test = '2+3*pi-customfunction($x,$y)';

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

            eval('$result = '.$test.';');
        }
        else
        {
            $result = false;
        }

        print_r($result);

        // print_r(eval('1 + 2 * 3 * ( 7 * 8 ) - ( 45 - 10 ) - cmin(4,0)'));exit;
        // $parser = new \Math\Parser();
        // $expression = '1 + 2 * 3 * ( 7 * 8 ) - ( 45 - 10 ) - cmin(4,0)';
        // $result = $parser->evaluate($expression);

        // echo $result; //302
        //return view('home');
    }
}
