<?php

namespace Modules\SaleReturn\Http\Controllers;

use App\Http\Controllers\Controller;
use Brick\Math\RoundingMode;
use Brick\Money\Context\CashContext;
use Brick\Money\Money;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Brand\Models\Brand;
use Modules\Category\Models\Category;
use Modules\SaleReturn\Models\SaleReturn;
use Modules\Unit\Models\Unit;
use Illuminate\Support\Str;

class SaleReturnImportCsv extends Controller
{
    public function __invoke(Request $request)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('import-csv-saleReturn'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $units = Unit::all();
            $category = Category::all();
            $brand = Brand::all();
            $saleReturns = $request->all();
            $removed = array_shift($saleReturns);
            $saleReturnsArray = [];
            foreach ($saleReturns as $key => $value) {
                $currency = isset($value['currency']) ? trim($value['currency']) : (systemsettings('currency') ?? config('setting.currency'));
                $saleReturnsArray[] = [
                    'name' => trim($value['name']),
                    'label' => trim($value['label']),
                    'saleReturn_desc' => isset($value['saleReturn_desc']) ? trim($value['saleReturn_desc']) : null,
                    'category_id' => isset($value['category_id']) ? $this->getMetaId($units, $value['category_id'], 'name') : null,
                    'brand_id' => isset($value['brand_id']) ? $this->getMetaId($units, $value['brand_id'], 'name') : null,
                    'barcode' => isset($value['barcode']) ? trim($value['barcode']) : null,
                    'barcode_type' => isset($value['barcode_type']) ? Arr::first(Controller::BARCODE_TYPES, function ($valueInArray, $key) use ($value) {
                        return $valueInArray['name'] == trim($value['barcode_type']);
                    })['id'] : null,
                    'currency' => Str::upper($currency),
                    'cost' => isset($value['cost']) ? $this->convertMoney($value['cost'], Str::upper($currency)) : 0,
                    'price' => isset($value['price']) ? $this->convertMoney($value['price'], Str::upper($currency)) : 0,
                    'unit_id' => isset($value['unit_id']) ? $this->getMetaId($units, $value['unit_id'], 'short_name') : null,
                    'saleReturn_unit_id' => isset($value['saleReturn_unit_id']) ? $this->getMetaId($units, $value['saleReturn_unit_id'], 'short_name') : null,
                    'saleReturn_unit_id' => isset($value['saleReturn_unit_id']) ? $this->getMetaId($units, $value['saleReturn_unit_id'], 'short_name') : null,
                    'tax' => isset($value['tax']) ? $value['tax'] : 0,
                    'tax_type' => isset($value['tax_type']) ? Arr::first(Controller::TAX_TYPES, function ($valueInArray, $key) use ($value) {
                        return $valueInArray['name'] == trim($value['tax_type']);
                    })['id'] : null,
                    'stock_alert' => isset($value['stock_alert']) ? $value['stock_alert'] : 0,
                    'saleReturn_type' => isset($value['saleReturn_type']) ? Arr::first(SaleReturn::PRODUCT_TYPES, function ($valueInArray, $key) use ($value) {
                        return $valueInArray['name'] == trim($value['saleReturn_type']);
                    })['id'] : 1,
                    'is_active' => isset($value['is_active']) && in_array(Str::lower(trim($value['is_active'])), Controller::ACTIVE_ARRAY) ? true : false,
                ];
            }
            $errors = [];
            foreach ($saleReturnsArray as $saleReturn) {
                $validator = Validator::make($saleReturn, [
                    'name'          => ['required', 'min:3', 'max:100', Rule::unique('saleReturns', 'name')->withoutTrashed()],
                    'label'         => ['required', 'min:3', 'max:100', 'string', Rule::unique('saleReturns', 'label')->withoutTrashed()],
                    'saleReturn_desc'  => ['sometimes', 'max:255', 'nullable', 'string'],
                    'category_id'   => ['sometimes', 'integer', 'nullable'],
                    'brand_id'      => ['sometimes', 'integer', 'nullable'],
                    'barcode'       => ['sometimes', 'max:255', 'nullable', 'string', Rule::requiredIf(!!$saleReturn['barcode_type'])],
                    'barcode_type'  => ['integer', 'sometimes', 'nullable', Rule::requiredIf(!!$saleReturn['barcode'])],
                    'currency'      => ['sometimes', 'string', 'nullable'],
                    'cost'          => ['sometimes', 'numeric', 'min:0'],
                    'price'         => ['sometimes', 'numeric', 'min:0'],
                    'unit_id'       => ['nullable', 'integer'],
                    'saleReturn_unit_id'  => ['nullable', 'integer'],
                    'saleReturn_unit_id' => ['nullable', 'integer'],
                    'tax_type'      => ['integer', 'sometimes', 'nullable', Rule::requiredIf(!!$saleReturn['tax']), 'integer', Rule::in([1, 2])],
                    'tax'           => ['sometimes', Rule::requiredIf(!!$saleReturn['tax_type']), 'numeric', 'min:0'],
                    'stock_alert'   => ['sometimes', 'nullable', 'integer', 'min:0'],
                    'is_active'     => ['required', 'boolean'],
                    'saleReturn_type'    => ['nullable', 'integer', Rule::in([1, 2, 3])],
                    'remarks'       => ['string', 'nullable'],
                ]);

                if ($validator->fails()) {
                    $errors[$saleReturn['name']] = $validator->errors();
                }
            }
            if ($errors) {
                return $this->error(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            SaleReturn::upsert($saleReturnsArray, ['name', 'label']);
            return $this->success(__('status.imported_csv_successfully'));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.import_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.import_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    protected function getMetaId($collection, $value, $column)
    {
        return $collection->where($column, trim($value))->first()->id ?? null;
    }

    protected function convertMoney($value, $currency)
    {
        if (!$value instanceof \Brick\Money\Money) {
            return Money::of($value, Str::upper($currency), new CashContext(5), RoundingMode::UP)->getMinorAmount()->toInt();
        }

        return $value->getMinorAmount()->toInt();
    }
}
