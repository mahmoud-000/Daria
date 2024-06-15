# Manufacture - Production Order Table:
  - ID
  - Code Nmuber
  - Productiion Plan Date
  - Productiion Plan
  - Status [1 = Review, 2 = In Progress, 3 = Approved, 4 = Canceld ]
  - Is Approved
  - Approved By
  - Is Closed -  مغلق وتم الترحيل
  - Closed By
  - Added By
  - Updated By

# Production Line:
* ID
* Name
* Is Active
* Parent Accounting Number
* Other Table Foreign Key (Internal)
* Account Number
* Customer Code
* Start Balance (+500 = Daan, -1000 = Madeen, 0 = Moltazem)
* Start Balance Status (0 = Moltazem, 1 = Creadit, 2 = Deapit)
* Current Balance
* Company Code
* Notes
* City ID
* Address
* Production Line Parent Account Number

# Production Line With Orders - Exchange
> Auto Serial = نفس فكرة id
> Production Line Code = نفس فكرة id هو مستخدمه forien key
> Treasures Transactions ID - حركة الخزنة
> Discount Type
> لو اتغير الى % يجب الا يزيد عن 100
* ID
* Order Type (1 = Exchange Materials To Production Line From Warehouse, 2 = Return Materials From Production Line to Warehouse)
* Auto Serial
* Date
* Production Line Code
* Production Order Code
* Is Approved or Post Flag (الاعتماد بيأثر فى حساب المورد والمخزن)
* Company Code
* Total Cost Before Discount
* Discount Type (1 = Percent, 2 = Fixed)
* Discount
* Discount Value
* Tax Percent
* Tax Value
* Total Cost (اجمالى الفاتورة بعد الخصم واضافة الضريبة)
* Account Number
* Money For Account (-1000) (دائن) - قيمة الفاتورة
<!-- * Bill Type (كاش او اجل) -->
* What Paid (ايه اللى اتدفع)
* What Remain (ايه اللى الباقى)
* Treasures Transactions
* Production Line Balance Before (رصيد المورد قبل الفاتورة)
* Production Line Balance After (رصيد المورد بعد الفاتورة)
* Added By
* Updated By
* Approved By
* Store ID

# Production Line With Orders Details
> Batch ID = فكرته ان المخزن بيتقسم باتشات على حسب الأسعار وتواريخ الصلاحية
> رقم الباتش الموجود فى المخزن
* ID
* Production Line With Orders Auto Serials - نفس فكرة Production Line Order Id as Forein Key
* Production Line With Orders Order Type
* Company Code
* Deliverd Quantity
* Unit ID
* Unit Type = Is Main Or Retail Unit
* Unit Price
* Total Price
* Order Date
* Item Code
* Batch ID = عند اعتماد الفاتورة
* Item Type 
  1. مخزنى - ليس لديه تاريخ صلاحية ولا يفسد
  2. استهلاكى - لديه تاريخ صلاحية ويفسد
  3. عهده
* Itemion Date - لو مخزنى
* Expired Date - لو مخزنى
* Added By
* Updated By

# Production Line With Orders - Recieved
> Auto Serial = نفس فكرة id
> Production Line Code = نفس فكرة id هو مستخدمه forien key
> Treasures Transactions ID - حركة الخزنة
> Discount Type
> لو اتغير الى % يجب الا يزيد عن 100
* ID
* Order Type (1 = Recived Final Product Production Line From Production Line, 2 = Return Final Product To Production Line)
* Auto Serial
* Date
* Production Line Code
* Production Order Code
* Is Approved or Post Flag (الاعتماد بيأثر فى حساب المورد والمخزن)
* Company Code
* Total Cost Before Discount
* Discount Type (1 = Percent, 2 = Fixed)
* Discount
* Discount Value
* Tax Percent
* Tax Value
* Total Cost (اجمالى الفاتورة بعد الخصم واضافة الضريبة)
* Account Number
* Money For Account (-1000) (دائن) - قيمة الفاتورة
<!-- * Bill Type (كاش او اجل) -->
* What Paid (ايه اللى اتدفع)
* What Remain (ايه اللى الباقى)
* Treasures Transactions
* Production Line Balance Before (رصيد المورد قبل الفاتورة)
* Production Line Balance After (رصيد المورد بعد الفاتورة)
* Added By
* Updated By
* Approved By
* Store ID

# Production Recieved With Orders Details
> Batch ID = فكرته ان المخزن بيتقسم باتشات على حسب الأسعار وتواريخ الصلاحية
> رقم الباتش الموجود فى المخزن
* ID
* Production Recieved With Orders Auto Serials - نفس فكرة Production Recieved Order Id as Forein Key
* Production Recieved With Orders Order Type
* Company Code
* Deliverd Quantity
* Unit ID
* Unit Type = Is Main Or Retail Unit
* Unit Price
* Total Price
* Order Date
* Item Code
* Batch ID = عند اعتماد الفاتورة
* Item Type 
  1. مخزنى - ليس لديه تاريخ صلاحية ولا يفسد
  2. استهلاكى - لديه تاريخ صلاحية ويفسد
  3. عهده
* Itemion Date - لو مخزنى
* Expired Date - لو مخزنى
* Added By
* Updated By


> Every Company Has Many Branches
> Only the Owner Can Be Create a Company With Branches
# Company
* ID
* Name
* Address
* Email
* Phone
* Is Active

# Branches
* ID
* Name
* Address
* Email
* Phone
* Company ID
* Is Active


> الضيط العام
# ٍSettings
* System Name
* General Alert رسالة عامة
* Image
* Company Name
* Company Address
* Company phone
* Added By
* Updated By
* Company Code For All Tables To Make Branches Or Diffrent Companies
* Parent Account Number For Suppliers
* Parent Account Number For Customers
* Parent Account Number For Delegates
* Parent Account Number For Employes

# Treasury - الخزنة
* ID
* Name
* Is Master - هل الخزنة أساسية - الجدول مفيهوش الا واحدة رئيسية
* Last Isal Exchange - أخر إيصال صرف نقدية - اللى على ارض الواقع - وبيزيد بشكل ألى - يبدأ من
* Last Isal Collect - أخر إيصال تحصيل نقدية - اللى على ارض الواقع - وبيزيد بشكل ألى - يبدأ من
* Company Code For All Tables To Make Branches Or Diffrent Companies
* Is Active
* Added By
* Updated By

> أعتقد الجدول ده ممكن يتلغى وتعمل عمود جديد فى جدول الخزن ونسميبه treasury_id 
> ولو الخزنة فرعية لازم نحط الحزنة الرئيسية الأب ليها اللى هترمى فيه
> نفس فكرة جدول الوحدات Units

# Treasury Delivery
فكرتها ان ده خزن فرعية بتسلم عهدتها للخزنة الرئيسية
* ID
* Treasury ID - الخزنة التى سوف تستلم Parent (ID)
* Treasury Can Delivey ID - الخزنة التى سوف يتم تسليمها
* Company Code For All Tables To Make Branches Or Diffrent Companies
* Is Active
* Added By
* Updated By

> هذا الجدول هو شبيه بجدول الأقسام Categories
> ولكن خاص بأقسام الفواتير
> وممكن نسميه أفضل Invoice Types

# Sales Material Types - بيانات فئات الفواتير
* ID
* Name
* Company Code For All Tables To Make Branches Or Diffrent Companies
* Is Active
* Invoice Type = Module Name

# Warehouse
* ID
* Name
* Company Code For All Tables To Make Branches Or Diffrent Companies
* Is Active
* Added By
* Updated By

# Units
* ID
* Name
* Is Master
* Company Code For All Tables To Make Branches Or Diffrent Companies
* Is Active
* Added By
* Updated By


> وهو تقريبا نفسه جدول الأقسام Categories

# ItemCard Categoriey
* ID
* Name
* Company Code For All Tables To Make Branches Or Diffrent Companies
* Is Active
* Added By
* Updated By

# ItemCard = Item
* ID
* Name
* Item Type 
  1. مخزنى - ليس لديه تاريخ صلاحية ولا يفسد
  2. استهلاكى - لديه تاريخ صلاحية ويفسد
  3. عهده
* Item Category
* Item Parent ID
* Does Has Retail Unit = هل تمتلك وحدة تجزئة
* Retail Unit = وحدة التجزئة
* Unit ID = وحدة التجزئة الأب
* Retail Unit Quantity To Parent = وحدة التجزئة فيها كم وحدة للأب
* Is Active
* Date
* Company Code For All Tables To Make Branches Or Diffrent Companies
* Item Code = Id For Item
* Barcode
* Has Fixed Cost = يمكن تغييره بفواتير المشتريات
* Has Fixed Price = يمكن تغييره بفواتير المبيعات

* Price = السعر القطاعى للوحدة الأب
* Goomal Price = السعر الجملة للوحدة الأب
* Nos Goomal Price = السعر نص الجملة للوحدة الأب

* Price Retail = السعر القطاعى للوحدة التجزئة
* Goomal Price Retail = السعر الجملة للوحدة التجزئة
* Nos Goomal Price Retail = السعر نص الجملة للوحدة التجزئة

* Cost Price
* Cost Price Retail

* Quantity بالشكاره مثلا
* Quantity Retail 2 طبق مثلا
* Quantity All Retail 12 طبق
* Added By
* Updated By

# Price List
* ID
* Item ID
* Variant ID
* Unit ID
* Quantity
* Unit Cost
* Unit Price


# Accounting Types
> أنواع الحسابات
> Related Accounting Internal Accounts
> هل يضاف من شاشة داخلية ام يضاف من شاشة الحسابات
> بيعنى هل الحساب تم تأسيسه من داخل النظام ام حساب عام
> مثل الموردين مثلا = true
> أما البنكى او المصروفات مثلا = false
- رأس المال = false
- بنكى = false
- مصروفات = false
- قسم داخلى = false
- عام = false
- مورد = true
- عميل = true
- مندوب = true
- موظف = true
- خط إنتاج = true
* ID
* Name
* Active
* Related Accounting Internal Accounts



# Accounting - جدول الحسابات
> جدول الشجرة المحاسبية
> Other Table Foreign Key = يقصد بيه الرقم الخاص بمثلا العميل او المورد = ex. Customer Code
> Account Number: ؤقم الحساب وهو مختلف عن id
* ID
* Name
* Is Archived = deleted_at soft delete
* Account Type ID
* Parent Accounting Number = With Morph Relation
* Account Number
* Start Balance (+500 = Daan, -1000 = Madeen, 0 = Moltazem)
* Start Balance Status (0 = Moltazem, 1 = Creadit, 2 = Deapit)
* Current Balance
* Other Table Foreign Key = Morph Relation with Customers - Suppliers - etc
* Company Code
* Notes
* Is Parent = محتاجينه عشان الشجرة المحاسبية المتكاملة
* Date



# Customers
> Parent Accounting Number: يقصد رقم الحساب من جدول الشجرة المحاسبية
* ID
* Name
* Is Active
* Parent Accounting Number
* Other Table Foreign Key (Internal)
* Account Number
* Customer Code
* Start Balance (+500 = Daan, -1000 = Madeen, 0 = Moltazem)
* Start Balance Status (0 = Moltazem, 1 = Creadit, 2 = Deapit)
* Current Balance
* Company Code
* Notes
* City ID
* Address
* Customer Parent Account Number


# Supplier Categories = فئات الموردين
* ID
* Name
* Company Code For All Tables To Make Branches Or Diffrent Companies
* Is Active

# Suppliers
* ID
* Name
* Active
* Parent Accounting Number
* Account Number
* Supplier Category ID
* Supplier Code
* Start Balance (+500 = Daan, -1000 = Madeen, 0 = Moltazem)
* Start Balance Status (0 = Moltazem, 1 = Creadit, 2 = Deapit)
* Current Balance
* Other Table Foreign Key (Internal)
* Company Category ID
* Notes
* City ID
* Address
* Supplier Parent Account Category ID
* Supplier Parent Account Number

# Supplier With Orders - Purchases
> Auto Serial = نفس فكرة id
> Supplier Code = نفس فكرة id هو مستخدمه forien key
> Treasures Transactions ID - حركة الخزنة
> Discount Type
> لو اتغير الى % يجب الا يزيد عن 100
* ID
* Order Type (1 = Purchase, 2 = Return on Same Bill, 3 = Return on General)
* Auto Serial
* Doc Number (رقم الفاتورة اليدوى اللى مع المورد)
* Date
* Supplier Code
* Is Approved or Post Flag (الاعتماد بيأثر فى حساب المورد والمخزن)
* Company Code
* Total Cost Before Discount
* Discount Type (1 = Percent, 2 = Fixed)
* Discount
* Discount Value
* Tax Percent
* Tax Value
* Total Cost (اجمالى الفاتورة بعد الخصم واضافة الضريبة)
* Account Number
* Money For Account (-1000) (دائن) - قيمة الفاتورة
* Bill Type (كاش او اجل)
* What Paid (ايه اللى اتدفع)
* What Remain (ايه اللى الباقى)
* Treasures Transactions
* Supplier Balance Before (رصيد المورد قبل الفاتورة)
* Supplier Balance After (رصيد المورد بعد الفاتورة)
* Added By
* Updated By
* Approved By
* Store ID


# Supplier With Orders Details
> Batch ID = فكرته ان المخزن بيتقسم باتشات على حسب الأسعار وتواريخ الصلاحية
> رقم الباتش الموجود فى المخزن
* ID
* Supplier With Orders Auto Serials - نفس فكرة Supplier Order Id as Forein Key
* Supplier With Orders Order Type
* Company Code
* Deliverd Quantity
* Unit ID
* Unit Type = Is Main Or Retail Unit
* Unit Price
* Total Price
* Order Date
* Item Code
* Batch ID = عند اعتماد الفاتورة
* Item Type 
  1. مخزنى - ليس لديه تاريخ صلاحية ولا يفسد
  2. استهلاكى - لديه تاريخ صلاحية ويفسد
  3. عهده
* Itemion Date - لو مخزنى
* Expired Date - لو مخزنى
* Added By
* Updated By

# Admin Treasures
> ممكن يتعمل بطريقة أحسن عشان ادى للمستخدم صلاحية للخزنة
* ID
* User ID
* Treasury ID
* Is Active
* Company Code
* Added By
* Updated By



# Admin Shifts - شفتات المستخدمين
* ID
* Shift Code - كود الشيقت بديل id
* User ID
* Treasury ID
* Treasury Balance In Start Shift
* Start Date Shift - تاريخ بداية الشيفت
* End Date Shift - تاريخ نهاية الشيفت
* Is Finished - هل تم إنتهاء الشيفت
* Is Delivered - هل تم التسليم والمراجعة
* Delivery To User ID - الشخص اللى استلم من اللى قبله
* Delivery To Admin Shift ID - الشيفت اللى استلم من اللى قبله
* Delivery To Treasury ID - الخزنة اللى استلمت من اللى قبله
* Money Should Be Delivered - الأموال التى متوقع تسليمها مثلا 5الاف جنيه
* Money Really Delivered - الأموال التى تم تسليمها مثلا 4الاف جنيه
* Money Status - (0 = Balanced, 1 = inability, 2 = Extra) [منزن - عجز - زيادة]
* Money Stat Value - قيمة العجز والزيادة
* Received Type - (1 = استلام على نفس الخزنة) - (2 = استلام على خزنة أخرى)
* Received View Date - تاريخ مراجعة واستلام هذا الشيفت
* treasury Transaction ID - رقم الايصال فى جدول تحصيل النقدية لحركة التقدية 
* Company Code
* Added By
* Updated By

# Move Type - أنواع حركات النقدية
> 26 حالة 
> فى ملف sql 


# Treasury Transactions - Collect Transactions - جدول تحصيل النقدية
> Iesal Number
> Auto Serial
> عشان نعرف نرقم ترقيم صحيح لو عملنا كذا شركة
* ID
* Auto Serial = اخر id فى هذا الجدول ومرتبط بالخزنة ونزود عليه 1
* Iesal Number = بديل id
* Treasury ID
* Shift ID
* Admin Shift Code - كود الشيفت للمستخدم
* Move Type [نوع حركة النقدية]
* Move Date
* Invoice Id - وممكن يبقى فارغ
* Account Number - رقم الحساب المالى الخاص بالخزن فى الشجرة المحاسبية
* Is Acount - حركة شيفت خزنة ولا على حساب مالى
* Is Approved
* Money For Account (-1000) (دائن)
* Byan
* Company Code
* Added By
* Updated By

# شاشة تحصيل النقدية - Collect
> يجب التأكد من أن المستخدم مستلم شيفت
> يجب التأكد من أن المستخدم مستلم خزنة
> الحساب المالى اللى هيتحصل عليه لازم يكون إبن وليس أب
> Is Parent = 0
> وفى حالة جلب البيانات الخاصة بأنواع الحركات
> In screen = 2 - internal 2
> money > 0
> ممكن المستخدم يدخل السيستم ويعمل فواتير بدون ما يكون معاه شيفت أو خزنة لو شغال مثلا داتا انترى بس لازم الفاتورة تكون أجل مش كاش

# شاشة صرف النقدية - Exchange
> يجب التأكد من أن المستخدم مستلم شيفت
> يجب التأكد من أن المستخدم مستلم خزنة
> الحساب المالى اللى هيتحصل عليه لازم يكون إبن وليس أب
> Is Parent = 0
> وفى حالة جلب البيانات الخاصة بأنواع الحركات
> In screen = 1 - internal 1 
> money < 0
> هتعمل فحص اذا كانت الخزنة فيها فلوس تكفى للصرف
> ممكن المستخدم يدخل السيستم ويعمل فواتير بدون ما يكون معاه شيفت أو خزنة لو شغال مثلا داتا انترى بس لازم الفاتورة تكون أجل مش كاش

# ملاحظات خاصة بالفواتير
* عند إعتماد الفاتورة تحصل حركتين

> حركة النقدية
1. لو أجل هناك احتمالين
> لو كلها أجل مفيش أى حركة هتحصل على النقدية
> لو بعضها أجل فى حركة هتحصل على النقدية وتأثر على المخزن

2. لو كاش
> فى حركة هتحصل على النقدية وتأثر على المخزن

> حركة المخزن
1. فتح باتش جديد
2. تحديث باتش قديم
> لو نفس السعر وتاريخ الانتاج و الصلاحية

* وكل ده بيأثر فى رصيد حساب المورد

# ItemCard Batches - جدول باتشات الأصناف بالمخازن
* ID
* Store ID - Warehouse ID
* ItemCard ID - كود الصنف
* Unit ID - كود الوح\ة الأب
* Unit Cost
* Unit Price
* Quantity
* Total Cost Price - إحمالى سعر شراء الباتش ككل
* Itemion Date - وممكن يكون فاضى
* Expired Date - وممكن يكون فاضى
* Auto Serial - بديل id
* Is Archived - Deleted at
* Added By
* Updated By

# 3 جداول
> كارت الصنف
> سجل الحركة - فئة الحركة - نوع الحركة
--------------------------------------
> الفئات
1. حركة فى المبيعات
2. حركة فى المشتريات
3. حركة فى المخازن

> الأنواع
1. مشتريات
2. مرتجع مشتريات بأصل الفاتورة
3. مرتجع مشتريات عام
4. مبيعات
5. مرتجع مبيعات عام
6. صرف داخلى لمندوب
7. مرتجع صرف داخلى لمندوب
8. تحويل بين المخازن
9. مبيعات صرف مباشر لعميل
10. مبيعات صرف لمندوب التوصيل
11. صرف خامات لخط التصنيع
12. رد خامات من خط التصنيع
13. استلام انتاج تام من خط التصنيع
14. رد انتاج تام الى خط التصنيع

> السجل - ItemCard Movement
* ID
* ItemCard ID
* Movement Category ID
* Movement Type ID
* Invoiceable ID
* Invoiceable Type
* Detail ID
* Byan
* Quantity Before Movement
* Quantity after Movement
* Added By
* Date
* Company Code

> رصيد الحساب المالى للمورد
* صافى مجموع كل من
. رصيد أول المدة
. حركات الخزن
. مجموع فواتير المشتريات والمرتجعات

> حركات الصنف تؤثر على
1. كارت الصنف
2. مراُة الصنف
- الكمية الإجمالية للصنف داخل كل المخازن والباتشات
- تحويل كل الكميات لوحدة التجزئة
3. اخر سعر شراء


# Customers With Orders - Sales
> Auto Serial = نفس فكرة id
> Customer Code = نفس فكرة id هو مستخدمه forien key
> Treasures Transactions ID - حركة الخزنة
> Discount Type
> لو اتغير الى % يجب الا يزيد عن 100
* ID
* Order Type (1 = Sale, 2 = Return on Same Bill, 3 = Return on General)
* Auto Serial
* Doc Number (رقم الفاتورة اليدوى اللى مع المورد)
* Date
* Customer Code
* Is Approved or Post Flag (الاعتماد بيأثر فى حساب المورد والمخزن)
* Company Code
* Total Price Before Discount
* Discount Type (1 = Percent, 2 = Fixed)
* Discount
* Discount Value
* Tax Percent
* Tax Value
* Total Price (اجمالى الفاتورة بعد الخصم واضافة الضريبة)
* Account Number
* Money For Account (-1000) (دائن) - قيمة الفاتورة
* Bill Type (كاش او اجل)
* What Paid (ايه اللى اتدفع)
* What Remain (ايه اللى الباقى)
* Treasures Transactions
* Customer Balance Before (رصيد العميل قبل الفاتورة)
* Customer Balance After (رصيد العميل بعد الفاتورة)
* Added By
* Updated By
* Approved By
* Store ID
* Delegate ID

# Customer With Orders Details
> Batch ID = فكرته ان المخزن بيتقسم باتشات على حسب الأسعار وتواريخ الصلاحية
> رقم الباتش الموجود فى المخزن
* ID
* Customer With Orders Auto Serials - نفس فكرة Customer Order Id as Forein Key
* Customer With Orders Order Type
* Company Code
* Deliverd Quantity
* Unit ID
* Unit Type = Is Main Or Retail Unit
* Unit Price
* Total Price
* Order Date
* Item Code
* Batch ID = عند اعتماد الفاتورة
* Item Type 
  1. مخزنى - ليس لديه تاريخ صلاحية ولا يفسد
  2. استهلاكى - لديه تاريخ صلاحية ويفسد
  3. عهده
* Itemion Date - لو مخزنى
* Expired Date - لو مخزنى
* Added By
* Updated By
* Store ID - لو هتخلى الفاتورة تطلع من كذا مخزن

> تنزيل الأصناف بالفواتير
1. خصم مباشر
2. خصم عند الحفظ
3. خصم عند الإعتماد


> فى فواتير المبيعات
* اذا عملنا فاتورة غير معتمدة وحد تانى عمل فاتورة معتمدة على نفس الصنف وتم التأثير على المخزن
* الفاتورة غير المعتمدة هل ممكن يخصم الكمية حتى لو مفيش فى المخزن ويخليها بالسالب ام نعمل فحص للمخزن ونمنع تنفيذ الفاتورة 
* ممكن نعمل فى الإعدادات عشان تفعل الوضع ده او توقفه

> لازم كل فاتورة المبيعات يكون ليها مندوب حتى لو المكان مفيهوش مناديب يعمل مندوب افتراضى ويحمل عليه الفاتورة

# Delegates
> Parent Accounting Number: يقصد رقم الحساب من جدول الشجرة المحاسبية
>  نسبة المندوب هتتعمل على كل صنف مش الفاتورة ككل قطاعى وجملة ونص جملة
* ID
* Name
* Is Active
* Parent Accounting Number
* Other Table Foreign Key (Internal)
* Account Number
* Delegate Code
* Start Balance (+500 = Daan, -1000 = Madeen, 0 = Moltazem)
* Start Balance Status (0 = Moltazem, 1 = Creadit, 2 = Deapit)
* Current Balance
* Company Code
* Notes
* City ID
* Address
* Delegate Parent Account Number
* Commision Type
* Coolect Commision - نسبة العمولة للمندوب فى التحصيل
* Sales Commision Retail
* Sales Commision Nos Jommla
* Sales Commision Jommla

> لازم اخد بالى وانا بجيب العملاء او الموردين او اى حاجة خاصة بالسيلكت بوكس انى احدد عدد معين وبناء على البحث اظهر الباقى عشان التطبيق ميبقاش تقيل لو عدد العملاء مثلا بالالاف
> قد تكون فاتورة المبيعات بدون عميل - فاتورة طيارى
> عند اعتماد الفاتورة يجب اختيار العميل اذا كانت الفاتورة is has customer column

> رصيد الحساب المالى للعميل
* صافى مجموع كل من
. رصيد أول المدة
. حركات الخزن
. مجموع فواتير المبيعات والمرتجعات

# ItemCard Movement
# ItemCard Movement Types

> عند تعديل المنتج يجب عدم المساس بالوحدة الأب للمنتج عشان الكميات فى المخزن
> كارت الصنف
* بيانات الصنف
* حركات الصنف

> ويكون هناك فلاتر ايضا داخل كارت الصنف

# Suppliers With Orders Table
# Purchase Return - مرتجع المشتريات
> هناك نوعين
* مرتجع مشتريات بأصل فاتورة الشراء
* مرتجع مشتريات عام


> مهم ان اعمل keyboard shortcuts للسيستم

# الشحرة المحاسبية
> كل الحسابات تؤثر فى ميزان المراجعة

> حسابات المركز المالى - الميزانية
  * الأصول - كود 1 - دائن
  * الخصوم - كود 2 - دائن
  * حقوق الملكية - كود 5 - دائن

> حسابات النتيجة - قائمة الدخل - من خلالها بنعرف نتيجة النشاط أخر العام
  * المصروفات - مدين
  * الأيراداتس - دائن

1. اصول
  11. اصول غير متداولة - ثابتة - طويلة الأجل
    111. أصول طويلة الأجل ثابتة
      1111. أراضى
        11110001. أرض شبرا
        11120002. أرض الهرم
      1112. المبانى
        11120001. مبنى الرئيسى
        11120002. مبنى المخزن
      1113. السيارات
        11130001. سيارة نقل
        11130002. سيارة مرسيدس
      1114. أثاث ومنقولات
      1115. أجهزة كمبيوتر ولاب توب
    112. أصول غير ملموسة
      1121. براءات الإختراع
      1122. الشهرة
    113. أستثمارات
      1131. استثمارات فى شركات شقيقة
      1132. استثمارات فى شركات تابعة
      1133. استثمارات متاحة للبيع
  12. اصول متداولة - قصيرة الأجل
    121. النقدية والصندوق
      1210001. صندوق رئيسى
      1210002. صندوق فرعى
    122. البنوك
      1220001. بنك فيصل
      1220002. بنك مصر
  13. العملاء
    131. عملاء تجزئة
    132. عملاء جملة
    133. عملاء محليين
    134. عملاء دوليين
      1340001. محمود
      1340002. أحمد
  14. المخزون
  15. الأرصدة المدينة

2. خصوم - الالتزامات
  21. خصوم متداولة - قصيرة الأحل
    211. الموردون
      2110001. مورد 1
      2110002. مورد 2
    212. قروض قصيرة الأجل
      2120001. على
      2120002. حسن
    213. مخصصات
      2130001. على
      2130002. حسن
    214. أرصدة دائنة أخرى
      2140001. على
      2140002. حسن  
  22. خصوم متداولة - طويلة الأجل
    221. قروض طويلة الأجل
      2210001. قرض بنك فيضل
    222. مخصصات طويلة الأجل
    223. التزامات ضريبية مؤجلة

3. حقوق الملكية
  31. رأس المال
      310001. رأس مال المشروع من الشريك أ
      310002. رأس مال المشروع من الشريك ب
  32. جارى الشركاء
    320001. جارى الشريك أ
    320002. جارى الشريك ب
  33. الاحتياطيات
    330001. الاحتياطى العام
  34. الارباح المحتجزة
    340001. ارباح مرحلة من عام 2018

4. ايرادات
  41. ايرادات النشاط
  42. ايرادات متنوعة

5. المصروفات
  51. تكلفة المبيعات
    511. مخزون أول المدة
      51100001. مخزون 1
      51100002. مخزون 2
    512. المشتريات
      5120001. المشتريات الأجلة
      5120002. المشتريات النقدية
      5120003. مشتريات خارجية
      5120004. مرتجعات مشتريات
      5120005. خصم مكتسب
    513. مصروفات المشتريات
      5130001. مصروفات نقل المشتريات
      5130002. مصروفات عمولات على المشتريات
  52. المصروفات التسويقية والبيعية
    521. مصروفات الدعاية والإعلان
      5210001. مصروفات الدعاية بالجرائد
      5210002. مصروفات الدعاية بالإعلام
    522. رواتب وعمولات مناديب المبيعات
      5221. رواتب نقدية للمناديب
      5222. مزايا عينية للمناديب
      5223. عمولات مناديب المبيعات
    523. هدايا للعملاء
  53. المصروفات الإدارية والعمومية
    531. مصروفات إدارية
      5311. رواتب أجور الإداريين
        53111. أجور نقدية
        53112. مزايا عينية
      5312. حوافز ومكافأت
      5313. مصاريف وعمولات بنكية
      5314. مصاريف مناقصات وممارسات
      5315. مصاريف نثرية متنوعة
      5316. تأمينات إجتماعية
      5317. مصاريف إنتقالات
      5318. مصاريف إيجارات
        53180001. إيجار مستودع
      5319. مصاريف سفر
      53110. مصاريف هاتف وبريد
        531101. مصاريف تليفونات وفاكسات
        531102. مصاريف بريد
      53111. مصاريف كهرباء ومياه
        531110001. مصاريف الكهرباء
        531110002. مصاريف المياه
      53112. مصاريف التصوير والمطبوعات وأدوات كتابية
        5311120001. مصاربف التصوير
        5311120002. مصاربف المطبوعات
        5311120003. مصاربف ادوات كتابية
      53113. ضرائب
        531130001. ضرائب عقارية
    532. مخصصات الإهلاك


# شجرة الحسابات فى دفترة
الأصول #1
الخصوم #2
حقوق الملكية #3
الإيرادات #4
المصروفات #5

# استلام شفت الخزينة
> تكويد الخزن وضبط الأستلام والتسلم
> توزيع صلاحيات الخزن للمستخدمين
> جدول شغتات الخزن
> استلام شفت خزينة
> 
>
>







# القيود المحاسبية - مقال
تنقسم الى نوعان رئيسين
1. القيد البسيط Simple Entry
> أبسط تعريف للقيد البسيط هو القيد الذي يضم كلا طرفيه المدين والدائن حسابًا واحدًا فقط. ومن أمثلة القيد البسيط: سداد شركة لأجور عاملين.

2. القيد المركب Compound Entry
> القيد المركب عكس القيد البسيط، وهو القيد الذي يضم أحد طرفيه أو كلاهما -المدين أو الدائن- أكثر من حساب. وقد جرت العادة عند كتابة هذا النوع من القيود أن يُكتب قبل الطرف الذي يضم أكثر من حساب “من مذكورين” أو “إلى مذكورين” وفقًا لكونه حسابًا مدينًا أم دائنًا. ومن أمثلة القيد المركب ما يأتي:

> بعد أن أوضحنا القيد البسيط والقيد المركب -وهما نوعا القيود المحاسبية من حيث عدد الحسابات الموجودة في كل طرف من طرفي القيد- ننتقل الآن إلى التفرقة بين أنواع القيود المحاسبية من حيث طبيعتها والغرض منها، وقلنا سابقًا إنها تنقسم إلى قيد يومية وقيد تسوية وقيد إغلاق، ويضاف إلى هذه الأنواع الثلاثة الرئيسية نوعان آخران وهما القيد الافتتاحي والقيد العكسي، وسنوضح كلًا منها في السطور الآتية.

1. قيد اليومية Journal Entry
> قيد اليومية هو الأساس الذي تقوم عليه نظرية القيد في المحاسبة، فهو أداة توثيق كل معاملة مالية تحدث المنشأة بهدف تحقيق إدارة مالية مُحكمة وتحديد نتيجة النشاط في نهاية كل فترة بالربح أو بالخسارة. وتطبيقًا لمبادئ إعداد القيود المحاسبية، ينبغي أن يتكون القيد من طرفين أحدهما مدين والآخر دائن، وأن تكون الكفة متوازنة بين الطرفين. ومن أمثلة قيود اليومية:


2. قيد التسوية Adjusting Entry
> في بعض الحالات قد تستحق المنشأة بعض المصروفات التي تخص السنة ولكنها لم تسدد، وقد تنفذ الشركة بعض العقود خلال السنة المالية ولكنها لا تستلم كامل قيمة هذه العقود، في مثل هذه الحالات تبرز أهمية قيود التسوية.

> قيود التسوية هي التي يتم من خلالها تصحيح هذه الأوضاع التي تعتبر في ظاهرها مخالفة لبعض مبادئ المحاسبة مثل مبدأ الاستحقاق ومبدأ المقابلة، حيث يتم إجراء بعض التسويات لتعديل أرصدة الحسابات حيت يمكن الوصول إلى نتيجة النشاط والمركز المالي بشكل سليم ودقيق لكل فترة محاسبية على حدةٍ.

1. أولًا: تسوية الإيرادات المستحقة
> هي الأموال التي تستحقها الشركة خلال السنة مقابل خدمة أو منتج قدمته ولم تستلم مستحقاته بعدُ. ومن أمثلة ذلك:

قدمت إحدى الشركات استشارات بيئية لأحد العملاء بقيمة 35000 ريال ولم تستلم مستحقاتها حتى نهاية السنة المحاسبية. يكون حينئذٍ القيد كما يأتي:

> ثم يتم ترحيل القيد إلى دفتر الأستاذ.

2. ثانيًا: تسوية الإيرادات المقدمة
> الإيراد المقدّم هو المدفوع لك قبل تقديم الخدمة أو المنتج في مقابل هذا الإيراد وقد تم تسجيل الإيراد في الدفاتر. وفي هذه الحالة يتم تحديد ما نسبة الإيراد التي تخص السنة الحالية، وما زاد على ذلك يتم إدراجه كخصم متداول في قائمة المركز المالي. ومن أمثلة ذلك:

> قيام شركة بتأجير مقرها الإداري لشركة أخرى لمدة ثلاث سنوات مقابل 300000 ريال مع استلام كامل المبلغ مقدمًا. يكون حينئذٍ القيد كما يأتي:

> ثم يتم ترحيل القيد إلى دفتر الأستاذ.

3. ثالثًا: تسوية المصروفات المستحقة
> يمكن تعريف المصروفات المستحقة بأنها المصروفات المتعلقة بالفترة المحاسبية ومع ذلك لم يتم قيدها في الدفاتر ولا سداد قيمتها. ومن أمثلة ذلك:

> قيمة أجور العاملين في إحدى الشركات 250000 ريال، وكان رصيد الأجور في ميزان الكراجعة هو 210000 ريال، يكون حينئذٍ القيد كما يأتي:

> ثم يتم ترحيل القيد إلى دفتر الأستاذ.

4. رابعًا: تسوية المصروفات المقدمة
> المصروفات المقدمة هي المدفوعة خلال السنة المحاسبية مع أنها تخص أكثر من سنة لاحقة، وفي هذه الحالة يُحدد ما يخص السنة الحالية فقط، والزائد على ذلك يظهر كأصل متداول في قائمة المركز المالي. ومن أمثلة ذلك:

> دفعت إحدى الشركات شيكًا بقيمة 200000 ريال إيجار سنتين، يكون حينئذٍ القيد كما يأتي:  
> ثم يتم ترحيل القيد إلى دفتر الأستاذ.


3. قيد الإقفال Closing Entry
> مع نهاية السنة المالية يتخذ المحاسب أحد أهم الإجراءات المحاسبية فيما يتعلق بالقيود، وهي إقفال جميع الحسابات الاسمية -وهي حسابات الإيرادات والمصروفات– حتى يتمكن من إعداد قائمة الدخل ومعرفة نتيجة النشاط الفعلية سواءً بالربح أم بالخسارة.

1. أولًا: قيد إقفال المصروفات
> على العكس من طبيعتها المدينة في القيد المحاسبي، يتم إقفال المصروفات كحساب دائن، وعلى الطرف الآخر من القيد يكون حساب الأرباح والخسائر، وذلك كما يأتي:

2. ثانيًا: قيد إقفال الإيرادات
> كما هو الحال مع قيد إقفال المصروفات، تعامل الإيرادات بعكس طبيعتها الدائنة في القيد المحاسبي بحيث تكون في قيد الإقفال مدينة وعلى الطرف الآخر يكون حساب الأرباح والخسائر كذلك، وذلك كما يأتي:

4. القيد الافتتاحي Opening Entry
> ويطلق عليه كذلك قيد التأسيس، وهو القيد الذي تبدأ به الشركة السنة المالية متضمنًا سواءً أكانت حديثة النشأة أم تنتقل من سنة مالية إلى أخرى. وبالتالي نحن أمام حالتين إحداهما قيد افتتاحي لشركة حديثة النشأة، والأخرى قيد افتتاحي لشركة تنتقل من سنة مالية سابقة إلى السنة المالية اللاحقة.

> فإذا كانت الشركة حديثة التاسيس ولم تمارس نشاط سابقًا، فلن تكون للشركة أي بيانات سابقة يمكن تضمينها في القيد الافتتاحي. وفي هذه الحالة، تتم معالجة القيد الافتتاحي كالآتي:

> وفي حال كان القيد الافتتاحي لشركة تنتقل من سنة مالية سابقة إلى السنة المالية اللاحقة، فتتم المعالجة المحاسبية للقيد الافتتاحي استخراج الأرصدة الظاهرة في الميزانية العمومية والتي تعبر بدورها عن رصيد بداية المدة الجديدة، بعد اتخاذ كافة الخطوات المحاسبية في إقفال هذه الحسابات.

5. القيد العكسي Contra Entry
> القيد العكسي هو أحد أنواع القيود التي ليس شرطًا أن يتم استخدامه، حيث يشترط أن يستخدم في 

القيد العكس هو أحد أنواع القيود ذات الطبيعة الخاصة ولا يتم اللجوء إليه إلى في حالات خاصة، وهي عند حدوث خطأ في التبديل بين حسابي المدين والدائن في القيد المحاسبي، ولكي يتم تصحيح هذا الخطأ وتسكين كل حساب في موضعه الصحيح من حيث كونه مدينًا أم دائنًا يلجأ المحاسب إلى القيد العكسي.

 
 ----

 > طريقة كتابة القيد بدفتر اليومية:
وفي ضوء ذلك، نوضح في نقاط موجزة ومركزة أهم ما ينبغي وضعه في الاعتبار عند كتابة القيد في دفتر اليومية:

المدين: الطرف المدين.
الدائن: الطرف الدائن.
البيان: توضيح للقيد المحاسبي.
مركز التكلفة: وهو مركز التكلفة الذي يصب فيه هذا القيد.
رقم القيد: رقم تسلسلي للعملية المحاسبية بناءً على عدد ما سبقها من عمليات.
رقم صفحة الأستاذ: المقصود رقم صفحة الأستاذ التي سيتم ترحيل هذا القيد إليها.
التاريخ: تاريخ اليوم الذي حدثت فيه المعاملة المالية ويفترض به أن يكون تاريخ تسجيل القيد كذلك.
ولكي يكون القيد صحيحًا ينبغي أن تكون الكفة متوازنة بين طرفي المعاملة المدين والدائن. وفي دفتر اليومية من المفترض أن تكون هناك خانات جاهزة لملء هذه البيانات. 


> أهم القيود المحاسبية
دعنا نتفق أولًا على أن كل قيد محاسبي مهم لأنه لولا ذلك لما ظهرت الحاجة إلى تسجيله. ومع ذلك، من الممكن تسليط الضوء على أكثر القيود المحاسبية شيوعًا واستخدامًا وتأثيرًا على تحديد الوضع المالي للشركة. ومن بين أبرز هذه القيود ما يأتي:

قيود حساب العملاء.
قيود حساب الموردين.
قيود حساب الرواتب والأجور.
قيود حساب المخزون.
قيود حساب رأس المال.
قيود حساب المشتريات.
إلى جانب أهم القيود المحاسبية المندرجة تحت هذه الحسابات، هناك أيضًا قيود حساب الإهلاك، وقيود حساب العهد، وقيود حساب القروض، وقيود حساب الأقساط، وقيود حساب البنك.

 