<?php

declare(strict_types=1);

namespace Sylius\PayPalPlugin\Controller;

use Sylius\PayPalPlugin\Manager\PaymentStateManagerInterface;
use Sylius\PayPalPlugin\Provider\PaymentProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CancelPayPalCheckoutPaymentAction
{
    /** @var PaymentProviderInterface */
    private $paymentProvider;

    /** @var PaymentStateManagerInterface */
    private $paymentStateManager;

    public function __construct(
        PaymentProviderInterface $paymentProvider,
        PaymentStateManagerInterface $paymentStateManager
    ) {
        $this->paymentProvider = $paymentProvider;
        $this->paymentStateManager = $paymentStateManager;
    }

    public function __invoke(Request $request): Response
    {
        $content = (array) json_decode((string) $request->getContent(false), true);

        $payment = $this->paymentProvider->getByPayPalOrderId((string) $content['payPalOrderId']);

        $this->paymentStateManager->cancel($payment);

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
