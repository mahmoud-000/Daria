<?php

namespace App\Traits;

use Modules\Setting\Models\Setting;
use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\InvoiceDate;
use Salla\ZATCA\Tags\InvoiceTaxAmount;
use Salla\ZATCA\Tags\InvoiceTotalAmount;
use Salla\ZATCA\Tags\Seller;
use Salla\ZATCA\Tags\TaxNumber;

trait InvoiceQrCodeTrait
{
  public function getBase64Attribute()
  {
    $setting = Setting::first();
    if ($setting->vat_registration_number && strlen($setting->vat_registration_number) === 15) {
      return  GenerateQrCode::fromArray([
        new Seller($setting->company_name), // seller name        
        new TaxNumber($setting->vat_registration_number), // seller tax number
        new InvoiceDate($this->created_at), // invoice date as Zulu ISO8601 @see https://en.wikipedia.org/wiki/ISO_8601
        new InvoiceTotalAmount(number_format((float)$this->grand_total, 2, '.', '')), // invoice total amount
        new InvoiceTaxAmount(number_format((float)$this->tax_net, 2, '.', '')) // invoice tax amount
        // TODO :: Support others tags
      ])->toBase64();
    }
  }
}
