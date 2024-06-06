<?php

namespace App\Enums;


enum InvoiceTypesEnum:string
{
  case PURCHASE = 'purchase';
  case PURCHASE_RETURN = 'purchase_return';
  
  case SALE = 'sale';
  case SALE_RETURN = 'sale_return';

  case ADJUSTMENT = 'adjustment';
  case QUOTATION = 'quotation';

  case TRANSFER = 'transfer';
}
