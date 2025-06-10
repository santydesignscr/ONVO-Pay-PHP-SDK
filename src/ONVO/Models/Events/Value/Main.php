<?php
namespace ONVO\Models\Events\Value;

class Customer
{
    public string $id;
    public string $name;
    public string $phone;
    public string $email;

    public static function fromArray(array $data): self
    {
        $c = new self();
        $c->id    = $data['id'];
        $c->name  = $data['name'];
        $c->phone = $data['phone'];
        $c->email = $data['email'];
        return $c;
    }
}

class Metadata
{
    /** @var array<string,string> */
    public array $pairs;

    public static function fromArray(array $data): self
    {
        $m = new self();
        $m->pairs = $data;
        return $m;
    }
}

class ErrorDetail
{
    public string    $message;
    public ?string   $code;
    public ?string   $type;
    public ?string   $paymentMethodType;
    public \DateTime $createdAt;

    public static function fromArray(array $data): self
    {
        $e                       = new self();
        $e->message              = $data['message'];
        $e->code                 = $data['code']               ?? null;
        $e->type                 = $data['type']               ?? null;
        $e->paymentMethodType    = $data['paymentMethodType']  ?? null;
        $e->createdAt            = new \DateTime($data['createdAt']);
        return $e;
    }
}