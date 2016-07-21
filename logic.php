#!/usr/bin/env php
<?php

function usage($argv, $lenMax) {
	echo 
		'Usage: php -f '.$argv[0].' \'<expression 1>\' \'<expression 2>\' [<expression 3> ...]'.PHP_EOL
		.PHP_EOL
		.$argv[0].' accepts two or more expressions with a maximum of '.$lenMax.' variables and compares the truth tables to ensure they are equivalent.'.PHP_EOL
		.PHP_EOL
		.'Example:'.PHP_EOL
		.'    $ php -f '.$argv[0].' \'(A & ~B) = C\' \'(~A | B) = ~C\''.PHP_EOL
	;
	exit(1);
}


$lenMax = 4;

if ($argc < 3) {
	usage($argv, $lenMax);
}

$lenMax = min($lenMax, 26);
$allowedVars = range('A', chr(ord('A') + $lenMax - 1));
$allowedOperators = ['(', ')', '~', '&', '|', '=', ' '];

$expressions = array_slice($argv, 1);
$phpExpressions = [];
$usedVars = [];
echo 'Testing equivalency of expressions...'.PHP_EOL;

for ($i = 0, $l = count($expressions); $i < $l; $i++) {
	$expression = $expressions[$i];
        $expressionChars = str_split($expression);
	$unknownChars = array_diff(
		$expressionChars,
		$allowedVars,
		$allowedOperators
        );
	if (count($unknownChars)) {
		die('Error: Unknown characters in expression '.$i.' \''.$expression.'\': '.implode(', ', $unknownChars).PHP_EOL);
	}
	$expression = str_replace('=', '===', $expression);
	$expression = str_replace('&', '&&', $expression);
	$expression = str_replace('|', '||', $expression);
	$expression = str_replace('~', '!', $expression);
        $phpExpressions[$i] = $expression;
	$usedVars = array_merge(array_unique(array_merge($usedVars, array_intersect($allowedVars, $expressionChars))));
}

$len = count($usedVars);

if ($len > $lenMax) {
	echo 'Error: Unable to compare more than '.$lenMax.' variables'.PHP_EOL;
}

$result = true;
for ($i = 0, $l = $len**2 - 1; $i < $l; $i++) {
        $bools = [];
	$compareExpression = '';
	$compare = '';
	for ($j = 0; $j < $len; $j++) {
		$bools[$j] = ($i >> $j & 1) ? 'TRUE' : 'FALSE';
	}
	$m = count($expressions);
	$bo = $m > 2 ? '(' : '';
	$bc = $m > 2 ? ')' : '';
	for ($j = 0, $m = count($expressions); $j < $m; $j++) {
		$expression = $expressions[$j];
		$resolvedExpression = trim(str_replace($usedVars, $bools, $expression));
		$phpExpression = $phpExpressions[$j];
		$resolvedPhpExpression = '('.str_replace($usedVars, $bools, $phpExpression).')';
		switch ($j) {
			case 0:
				$compareExpression = $bo.$resolvedExpression;
				$compare = $bo.$resolvedPhpExpression;
				break;
			case $m-1:
				$compareExpression .= ' <=> '.$resolvedExpression.$bc;
				$compare .= ' === '.$resolvedPhpExpression.$bc;
				break;
			default:
				$compareExpression .= ' <=> '.$resolvedExpression.') & ('.$resolvedExpression;
				$compare .= ' === '.$resolvedPhpExpression.') && ('.$resolvedPhpExpression;
		}
	}
	$compareResult = eval('return '.$compare.';');
	$result = $result && $compareResult;
	echo '('.$compareExpression.') <=> '.($compareResult ? 'TRUE' : 'FALSE').PHP_EOL;
}
echo '... '.($result ? 'TRUE' : 'FALSE').PHP_EOL;

