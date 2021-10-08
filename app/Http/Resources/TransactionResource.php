<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
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
            'ammount'         => $this->ammount,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }
}
