<?php

namespace App\Http\Resources;

use App\Hateoas\TransactionHateoas;
use GDebrauwer\Hateoas\Traits\HasLinks;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    use HasLinks;

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray($request) : array
    {
        return [
            'id'              => $this->id,
            'wallet_payer_id' => $this->wallet_payer_id,
            'wallet_payee_id' => $this->wallet_payee_id,
            'from_name'       => $this->whenLoaded('payerWallet', function () {
                return $this->payer()->name;
            }),
            'to_name'         => $this->whenLoaded('payeeWallet', function () {
                return $this->payee()->name;
            }),
            'ammount'         => (float) $this->ammount,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
            '_links'          => $this->links(TransactionHateoas::class),
        ];
    }
}
