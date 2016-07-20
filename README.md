# Logic

Logic accepts two or more logical expressions (with a maximum of 4 variables) and compares them to ensure they are equivalent.

## Usage

```
php -f logic.php '<expression 1>' '<expression 2>' [<expression 3> ...]
```

## Example

``` bash
$ php -f ./logic.php '(A & ~B) = C' '(~A | B) = ~C'
Testing equivalency of expressions...
((FALSE & ~FALSE) = FALSE <=> (~FALSE | FALSE) = ~FALSE) <=> TRUE
((TRUE & ~FALSE) = FALSE <=> (~TRUE | FALSE) = ~FALSE) <=> TRUE
((FALSE & ~TRUE) = FALSE <=> (~FALSE | TRUE) = ~FALSE) <=> TRUE
((TRUE & ~TRUE) = FALSE <=> (~TRUE | TRUE) = ~FALSE) <=> TRUE
((FALSE & ~FALSE) = TRUE <=> (~FALSE | FALSE) = ~TRUE) <=> TRUE
((TRUE & ~FALSE) = TRUE <=> (~TRUE | FALSE) = ~TRUE) <=> TRUE
((FALSE & ~TRUE) = TRUE <=> (~FALSE | TRUE) = ~TRUE) <=> TRUE
((TRUE & ~TRUE) = TRUE <=> (~TRUE | TRUE) = ~TRUE) <=> TRUE
((FALSE & ~FALSE) = FALSE <=> (~FALSE | FALSE) = ~FALSE) <=> TRUE
... TRUE
```

