<?php

namespace App\Payment\Methods;

use App\Payment\Visitors\PaymentVisitor;

class StripePayment implements PaymentMethod
{
    /**
     * Create a new class instance.
     */
    public ?string $sessionId;
    public ?int $amount;
    public ?string $name;
    public ?string $success;
    public ?string $cancel;

    public function __construct(?string $sessionId = null, ?int $amount=null , ?string $name=null,$success=null,$cancel=null)
    {
        $this->sessionId = $sessionId;
        $this->amount = $amount;
        $this->name=$name;
        $this->success=$success;
        $this->cancel=$cancel;
    }

    public function accept(PaymentVisitor $visitor)
    {
        return $visitor->visitStripe($this);
    }
}
