<?php

namespace Tests\Feature;

use JMac\Additions\Support\HttpStatus;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

final class StatusHelperTest extends TestCase
{
    #[Test]
    public function it_returns_instance_when_called_with_no_arguments(): void
    {
        $this->assertInstanceOf(HttpStatus::class, status());
    }

    #[Test]
    #[DataProvider('statusCodes')]
    public function it_response_when_passed_integer(int $code): void
    {
        $response = status($code);

        $this->assertSame($response->content(), '');
        $this->assertSame($response->status(), $code);
    }

    #[Test]
    #[DataProvider('redirectionStatusCodes')]
    public function it_throws_an_exception_redirection_status_codes(int $code): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('You can not use the status helper for redirection');

        status($code);
    }

    public static function redirectionStatusCodes(): array
    {
        return [
            [300],
            [301],
            [302],
            [303],
            [304],
            [305],
            [306],
            [307],
            [308],
        ];
    }

    public static function statusCodes(): array
    {
        return [
            [100],
            [101],
            [102],
            [103],
            [200],
            [201],
            [202],
            [203],
            [204],
            [205],
            [206],
            [207],
            [208],
            [226],
            [400],
            [401],
            [402],
            [403],
            [404],
            [405],
            [406],
            [407],
            [408],
            [409],
            [410],
            [411],
            [412],
            [413],
            [414],
            [415],
            [416],
            [417],
            [418],
            [421],
            [422],
            [423],
            [424],
            [425],
            [426],
            [428],
            [429],
            [431],
            [451],
            [500],
            [501],
            [502],
            [503],
            [504],
            [505],
            [506],
            [507],
            [508],
            [510],
            [511],
        ];
    }
}
