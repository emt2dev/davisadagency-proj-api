<?php
declare(strict_types=1);

function sanitizeFn(string $string) {
    return str_replace("*","",$string);
}

class sanitizeFnTest extends PHPUnit\Framework\TestCase {
    public function testStringDoesNotContainAsterisk(): void {
        $stringToTest = "SELECT * FROM USERS";
        $stringDesiredResult = "SELECT  FROM USERS";

        $this->assertSame($stringDesiredResult, sanitizeFn($stringToTest));
    }
}

// test
?>