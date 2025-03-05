<?php

namespace Tests\Unit\Support;

use JMac\Additions\Support\HttpStatus;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

final class HttpStatusTest extends TestCase
{
    private HttpStatus $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new HttpStatus;
    }

    #[Test]
    #[DataProvider('methodsAndStatusCodes')]
    public function it_returns_empty_response_with_status_code(string $method, int $code): void
    {
        $response = $this->subject->$method();

        $this->assertSame($response->content(), '');
        $this->assertSame($response->status(), $code);

    }

    #[Test]
    #[DataProvider('redirectionMethods')]
    public function it_throws_an_exception_for_redirection_methods(string $method): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('You can not use the status helper for redirection');

        $this->subject->$method();
    }

    #[Test]
    public function it_throws_an_exception_for_invalid_methods(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid status code method: fooBar');

        $this->subject->fooBar();
    }

    #[Test]
    public function it_throws_an_exception_for_improper_case_methods(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid status code method: iamaTeapot');

        $this->subject->iamaTeapot();
    }

    public static function methodsAndStatusCodes(): array
    {
        return [
            ['continue', 100],
            ['switchingProtocols', 101],
            ['processing', 102],
            ['earlyHints', 103],
            ['ok', 200],
            ['created', 201],
            ['accepted', 202],
            ['nonAuthoritativeInformation', 203],
            ['noContent', 204],
            ['resetContent', 205],
            ['partialContent', 206],
            ['multiStatus', 207],
            ['alreadyReported', 208],
            ['imUsed', 226],
            ['badRequest', 400],
            ['unauthorized', 401],
            ['paymentRequired', 402],
            ['forbidden', 403],
            ['notFound', 404],
            ['methodNotAllowed', 405],
            ['notAcceptable', 406],
            ['proxyAuthenticationRequired', 407],
            ['requestTimeout', 408],
            ['conflict', 409],
            ['gone', 410],
            ['lengthRequired', 411],
            ['preconditionFailed', 412],
            ['requestEntityTooLarge', 413],
            ['requestUriTooLong', 414],
            ['unsupportedMediaType', 415],
            ['requestedRangeNotSatisfiable', 416],
            ['expectationFailed', 417],
            ['iAmATeapot', 418],
            ['misdirectedRequest', 421],
            ['unprocessableEntity', 422],
            ['locked', 423],
            ['failedDependency', 424],
            ['tooEarly', 425],
            ['upgradeRequired', 426],
            ['preconditionRequired', 428],
            ['tooManyRequests', 429],
            ['requestHeaderFieldsTooLarge', 431],
            ['unavailableForLegalReasons', 451],
            ['internalServerError', 500],
            ['notImplemented', 501],
            ['badGateway', 502],
            ['serviceUnavailable', 503],
            ['gatewayTimeout', 504],
            ['versionNotSupported', 505],
            ['variantAlsoNegotiatesExperimental', 506],
            ['insufficientStorage', 507],
            ['loopDetected', 508],
            ['notExtended', 510],
            ['networkAuthenticationRequired', 511],
        ];
    }

    public static function redirectionMethods(): array
    {
        return [
            ['multipleChoices'],
            ['movedPermanently'],
            ['found'],
            ['seeOther'],
            ['notModified'],
            ['useProxy'],
            ['reserved'],
            ['temporaryRedirect'],
            ['permanentlyRedirect'],
        ];
    }
}
