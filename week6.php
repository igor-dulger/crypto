<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 23.03.2018
 * Time: 14:29
 */

$N = gmp_init('179769313486231590772930519078902473361797697894230657273430081157732675805505620686985379449212982959585501387537164015710139858647833778606925583497541085196591615128057575940752635007475935288710823649949940771895617054361149474865046711015101563940680527540071584560878577663743040086340742855278549092581');

//104173  103997
//$N = gmp_init('10833679481');

function gmp2dec($gmp)
{
//    return bin2hex(gmp_export($gmp));
    return gmp_strval($gmp);
}

function findFractions1($N)
{
    list($A, $rem) = gmp_sqrtrem($N);
    if (gmp_cmp($rem, 0) != 0) {
        $A = gmp_add($A, 1);
    }
    $x = gmp_sqrt(gmp_sub(gmp_mul($A, $A), $N));
    return [gmp_sub($A, $x), gmp_add($A, $x)];
}
list($p, $q) = findFractions1($N);

echo "p = ". gmp2dec($p), "\n";
echo "q = ". gmp2dec($q), "\n";

