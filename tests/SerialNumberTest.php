<?php

use Carbon\Carbon;
use Finller\Invoice\SerialNumberGenerator;

it('can generate serial number from format', function ($format, $serie, $count, $expected) {
    $generator = new SerialNumberGenerator(
        format: $format,
        prefix: 'IN'
    );

    $serialNumber = $generator->generate($serie, Carbon::parse('2022-01-01'), $count);

    expect($serialNumber)->toBe($expected);
})->with([
    ['PPSSSS-YYCCCC', 1, 2, 'IN0001-220002'],
    ['SSSS-YYCCCC', 1, 2, '0001-220002'],
    ['SSSS-CCCC', 1, 2, '0001-0002'],
    ['SSCC', 1, 2, '0102'],
    ['CCCC', 1, 2, '0002'],
    ['SSSSPP-YYCCCC', 1, 2, '0001IN-220002'],
    ['YYSSSSPPCCCC', 1, 2, '220001IN0002'],
    ['YYCCCCSSSSPP', 1, 2, '2200020001IN'],
    ['PPSSSS-YYYYCCCC', 1, 2, 'IN0001-20220002'],
    ['PPSSSS-YYYCCCC', 1, 2, 'IN0001-0220002'],
    ['PPCCCC', null, 102, 'IN0102'],
]);

it('can parse serial number from format', function ($format, $serialNumber, $prefix, $serie, $year, $count) {
    $generator = new SerialNumberGenerator(
        format: $format,
    );

    $serialNumberParsed = $generator->parse($serialNumber);

    dump($serialNumberParsed);

    expect($serialNumberParsed['prefix'])->toBe($prefix);
    expect($serialNumberParsed['serie'])->toBe($serie);
    expect($serialNumberParsed['year'])->toBe($year);
    expect($serialNumberParsed['count'])->toBe($count);
})->with([
    ['PPSSSS-YYCCCC', 'IN0001-220002', 'IN', '0001', '22', '0002'],
    ['SSSS-YYCCCC', '0001-220002', null, '0001', '22', '0002'],
]);
