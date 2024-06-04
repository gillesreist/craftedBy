<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Sku;
use App\Models\Tax;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;


class InvoicesService
{

    public function createInvoice(Order $order)
    {
        App::setLocale('fr');

        $address = '';

        if ($order->facturation_address) {
            $address = $order->facturation_address;
        } else {
            $address = $order->delivery_address;
        }

        $customer = new Party([
            'name' => $order->user->lastname.' '.$order->user->firstname,
            'phone' => $order->user->phone_number,
            'address' => $address,
            'custom_fields' => [
                'email' => $order->user->email,
            ],
        ]);

        $client = new Party([
            'name'          => 'CraftedBy',
            'phone'         => '(520) 318-9486',
        ]);

        $items = [];

        $skus = $order->skus;

        foreach ($skus as $sku) {

            $tax=Tax::find($sku->pivot->tax_id)->value;

            $item =
                InvoiceItem::make($sku->pivot->sku_name)
                ->description($sku->product->description)
                ->pricePerUnit($sku->pivot->sku_unit_price)
                ->taxByPercent($tax)
                ->quantity($sku->pivot->quantity);
            array_push($items, $item);
        }

        $notes = [
            'Merci pour votre achat.',
            'A très bientôt',
        ];
        $notes = implode("<br>", $notes);


        $invoice = Invoice::make('facture')
            ->status(__('invoices::invoice.paid'))
            ->series($order->id)
            ->serialNumberFormat('{SERIES}')
            ->buyer($customer)
            ->seller($client)
            ->date(now())
            ->dateFormat('d/m/Y')
            ->payUntilDays(14)
            ->currencySymbol('€')
            ->currencyCode('EUR')
            ->currencyFormat('{VALUE}{SYMBOL}')
            ->currencyThousandsSeparator('.')
            ->currencyDecimalPoint(',')
            ->filename($client->name . ' ' . $customer->name)
            ->addItems($items)
            ->notes($notes)
            ->logo(public_path('craftedByText.svg'));

        return $invoice->stream();
    }
}
