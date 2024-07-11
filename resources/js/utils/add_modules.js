// Rigister Modules
import { registerModules } from '../register-modules'
import pageErrorsModule from '../modules/pageErrors'
import localeModule from '../modules/locale'
import roleModule from '../modules/role'
import permissionModule from '../modules/permission'
import authModule from '../modules/auth'
import attributeModule from '../modules/attribute'
import regionModule from '../modules/region'
import organizationModule from '../modules/organization'
import companyModule from '../modules/company'
import branchModule from '../modules/branch'
import departmentModule from '../modules/department'
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
import variantModule from '../modules/variant'
import invoiceModule from '../modules/invoice'
import stockModule from '../modules/stock'
import purchaseModule from '../modules/purchase'
import purchaseReturnModule from '../modules/purchaseReturn'
import saleReturnModule from '../modules/saleReturn'
import saleModule from '../modules/sale'
import quotationModule from '../modules/quotation'
import pipelineModule from '../modules/pipeline'
import stageModule from '../modules/stage'


const addModules = registerModules({
  pageErrors: pageErrorsModule,
  region: regionModule,
  locale: localeModule,
  organization: organizationModule,
  company: companyModule,
  branch: branchModule,
  department: departmentModule,
  dashboard: dashboardModule,
  people: peopleModule,
  user: userModule,
  supplier: supplierModule,
  customer: customerModule,
  delegate: delegateModule,
  category: categoryModule,
  attribute: attributeModule,
  brand: brandModule,
  warehouse: warehouseModule,
  unit: unitModule,
  item: itemModule,
  variant: variantModule,
  invoice: invoiceModule,
  stock: stockModule,
  purchase: purchaseModule,
  purchaseReturn: purchaseReturnModule,
  sale: saleModule,
  saleReturn: saleReturnModule,
  quotation: quotationModule,
  pipeline: pipelineModule,
  stage: stageModule,
  permission: permissionModule,
  auth: authModule,
  upload: uploadModule,
  setting: settingModule,
  role: roleModule,
})

export default addModules