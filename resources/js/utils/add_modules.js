// Rigister Modules
import { registerModules } from '../register-modules'
import pageErrorsModule from '../modules/pageErrors'
import localeModule from '../modules/locale'
import roleModule from '../modules/role'
import permissionModule from '../modules/permission'
import authModule from '../modules/auth'
import dashboardModule from '../modules/dashboard'
import peopleModule from '../modules/people'
import userModule from '../modules/user'
import supplierModule from '../modules/supplier'
import customerModule from '../modules/customer'
import delegateModule from '../modules/delegate'
import settingModule from '../modules/setting'
import uploadModule from '../modules/upload'
import categoryModule from '../modules/category'
import brandModule from '../modules/brand'
import warehouseModule from '../modules/warehouse'
import unitModule from '../modules/unit'
import itemModule from '../modules/item'
import invoiceModule from '../modules/invoice'
import stockModule from '../modules/stock'
import purchaseModule from '../modules/purchase'
import purchaseReturnModule from '../modules/purchaseReturn'
import saleReturnModule from '../modules/saleReturn'
import saleModule from '../modules/sale'
import quotationModule from '../modules/quotation'
import adjustmentModule from '../modules/adjustment'
import transferModule from '../modules/transfer'
import pipelineModule from '../modules/pipeline'
import stageModule from '../modules/stage'


const addModules = registerModules({
  pageErrors: pageErrorsModule,
  locale: localeModule,
  dashboard: dashboardModule,
  people: peopleModule,
  user: userModule,
  supplier: supplierModule,
  customer: customerModule,
  delegate: delegateModule,
  category: categoryModule,
  brand: brandModule,
  warehouse: warehouseModule,
  unit: unitModule,
  item: itemModule,
  invoice: invoiceModule,
  stock: stockModule,
  purchase: purchaseModule,
  purchaseReturn: purchaseReturnModule,
  sale: saleModule,
  saleReturn: saleReturnModule,
  quotation: quotationModule,
  adjustment: adjustmentModule,
  transfer: transferModule,
  pipeline: pipelineModule,
  stage: stageModule,
  permission: permissionModule,
  auth: authModule,
  upload: uploadModule,
  setting: settingModule,
  role: roleModule,
})

export default addModules