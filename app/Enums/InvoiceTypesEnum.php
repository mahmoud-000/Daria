<?php

namespace App\Enums;


enum InvoiceTypesEnum:string
{
  case PURCHASE = 'purchase';
  case PURCHASE_RETURN = 'purchase_return';
  
  case SALE = 'sale';
  case SALE_RETURN = 'sale_return';

  case QUOTATION = 'quotation';
  case ADJUSTMENT = 'adjustment';
  case TRANSFER = 'transfer';
}
