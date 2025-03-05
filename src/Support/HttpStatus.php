<?php

namespace JMac\Additions\Support;

use Illuminate\Support\Str;

/**
 * @method \Illuminate\Http\Response continue()
 * @method \Illuminate\Http\Response switchingProtocols()
 * @method \Illuminate\Http\Response processing()
 * @method \Illuminate\Http\Response earlyHints()
 * @method \Illuminate\Http\Response ok()
 * @method \Illuminate\Http\Response created()
 * @method \Illuminate\Http\Response accepted()
 * @method \Illuminate\Http\Response nonAuthoritativeInformation()
 * @method \Illuminate\Http\Response noContent()
 * @method \Illuminate\Http\Response resetContent()
 * @method \Illuminate\Http\Response partialContent()
 * @method \Illuminate\Http\Response multiStatus()
 * @method \Illuminate\Http\Response alreadyReported()
 * @method \Illuminate\Http\Response imUsed()
 * @method \Illuminate\Http\Response badRequest()
 * @method \Illuminate\Http\Response unauthorized()
 * @method \Illuminate\Http\Response paymentRequired()
 * @method \Illuminate\Http\Response forbidden()
 * @method \Illuminate\Http\Response notFound()
 * @method \Illuminate\Http\Response methodNotAllowed()
 * @method \Illuminate\Http\Response notAcceptable()
 * @method \Illuminate\Http\Response proxyAuthenticationRequired()
 * @method \Illuminate\Http\Response requestTimeout()
 * @method \Illuminate\Http\Response conflict()
 * @method \Illuminate\Http\Response gone()
 * @method \Illuminate\Http\Response lengthRequired()
 * @method \Illuminate\Http\Response preconditionFailed()
 * @method \Illuminate\Http\Response requestEntityTooLarge()
 * @method \Illuminate\Http\Response requestUriTooLong()
 * @method \Illuminate\Http\Response unsupportedMediaType()
 * @method \Illuminate\Http\Response requestedRangeNotSatisfiable()
 * @method \Illuminate\Http\Response expectationFailed()
 * @method \Illuminate\Http\Response iAmATeapot()
 * @method \Illuminate\Http\Response misdirectedRequest()
 * @method \Illuminate\Http\Response unprocessableEntity()
 * @method \Illuminate\Http\Response locked()
 * @method \Illuminate\Http\Response failedDependency()
 * @method \Illuminate\Http\Response tooEarly()
 * @method \Illuminate\Http\Response upgradeRequired()
 * @method \Illuminate\Http\Response preconditionRequired()
 * @method \Illuminate\Http\Response tooManyRequests()
 * @method \Illuminate\Http\Response requestHeaderFieldsTooLarge()
 * @method \Illuminate\Http\Response unavailableForLegalReasons()
 * @method \Illuminate\Http\Response internalServerError()
 * @method \Illuminate\Http\Response notImplemented()
 * @method \Illuminate\Http\Response badGateway()
 * @method \Illuminate\Http\Response serviceUnavailable()
 * @method \Illuminate\Http\Response gatewayTimeout()
 * @method \Illuminate\Http\Response versionNotSupported()
 * @method \Illuminate\Http\Response variantAlsoNegotiatesExperimental()
 * @method \Illuminate\Http\Response insufficientStorage()
 * @method \Illuminate\Http\Response loopDetected()
 * @method \Illuminate\Http\Response notExtended()
 * @method \Illuminate\Http\Response networkAuthenticationRequired()
 */
class HttpStatus
{
    public function __call(string $name, array $arguments)
    {
        $constant = 'Symfony\Component\HttpFoundation\Response::HTTP_'.Str::upper(Str::snake($name));
        if (! defined($constant)) {
            throw new \RuntimeException('Invalid status code method: '.$name);
        }

        $code = constant($constant);
        if ($code >= 300 && $code < 400) {
            throw new \RuntimeException('You can not use the status helper for redirection');
        }

        return response()->noContent($code);
    }
}
