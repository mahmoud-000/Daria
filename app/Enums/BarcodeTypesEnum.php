<?php

namespace App\Enums;


enum BarcodeTypesEnum: int
{
  case CODE128 = 1;
  case CODE39 = 2;
  case EAN8 = 3;
  case EAN13 = 4;
  case UPC = 5;
}
