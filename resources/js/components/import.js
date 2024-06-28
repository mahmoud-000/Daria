import { defineAsyncComponent } from "vue";

export const TheBreadcrumbs = defineAsyncComponent(() => import("../layouts/main/theBreadcrumbs.vue"));

export const TheFullScreen = defineAsyncComponent(() => import("../layouts/main/theFullScreen.vue"));

export const TheSwitcherLang = defineAsyncComponent(() =>
    import("../layouts/main/theSwitcherLang.vue")
);

export const Themes = defineAsyncComponent(() => import("../components/Themes.vue"));

export const TheSpinner = defineAsyncComponent(() => import("../components/theSpinner.vue"));

export const TheHeader = defineAsyncComponent(() => import("../layouts/main/theHeader.vue"));

export const TheLeftSidebar = defineAsyncComponent(() =>
    import("../layouts/main/theLeftSidebar.vue")
);

export const LayoutBlank = defineAsyncComponent(() => import("../layouts/Blank.vue"));

export const LayoutContent = defineAsyncComponent(() =>
    import("../layouts/Content.vue")
);

export const BaseTable = defineAsyncComponent(() =>
    import("../components/Table/BaseTable.vue")
);

export const CardHeader = defineAsyncComponent(() =>
    import("../components/Form/CardHeader.vue")
);

export const CardSectionWithHeader = defineAsyncComponent(() =>
    import("../components/Form/CardSectionWithHeader.vue")
);

export const CardContacts = defineAsyncComponent(() =>
    import("../components/Form/Cards/Contacts.vue")
);

export const CardLocations = defineAsyncComponent(() =>
    import("../components/Form/Cards/Locations.vue")
);

export const CardUpload = defineAsyncComponent(() =>
    import("../components/Form/Cards/Upload.vue")
);

export const CardPriviliges = defineAsyncComponent(() =>
    import("../components/Form/Cards/Priviliges.vue")
);

export const CardRemarks = defineAsyncComponent(() =>
    import("../components/Form/Cards/Remarks.vue")
);

export const CardVariants = defineAsyncComponent(() =>
    import("../components/Form/Cards/Variants.vue")
);

export const BaseInput = defineAsyncComponent(() =>
    import("../components/Form/Inputs/BaseInput.vue")
);

export const DateInput = defineAsyncComponent(() =>
    import("../components/Form/Inputs/DateInput.vue")
);
export const SelectInput = defineAsyncComponent(() =>
    import("../components/Form/Inputs/SelectInput.vue")
);

export const SelectInputWithMedia = defineAsyncComponent(() =>
    import("../components/Form/Inputs/SelectInputWithMedia.vue")
);

export const CountryInput = defineAsyncComponent(() =>
    import("../components/Form/Inputs/CountryInput.vue")
);

export const CurrencyInput = defineAsyncComponent(() =>
    import("../components/Form/Inputs/CurrencyInput.vue")
);

export const CompanyInput = defineAsyncComponent(() =>
    import("../components/Form/Inputs/CompanyInput.vue")
);

export const ItemInput = defineAsyncComponent(() =>
    import("../components/Form/Inputs/ItemInput.vue")
);

export const CategoryInput = defineAsyncComponent(() =>
    import("../components/Form/Inputs/CategoryInput.vue")
);

export const BrandInput = defineAsyncComponent(() =>
    import("../components/Form/Inputs/BrandInput.vue")
);

export const SupplierInput = defineAsyncComponent(() =>
    import("../components/Form/Inputs/SupplierInput.vue")
);

export const CustomerInput = defineAsyncComponent(() =>
    import("../components/Form/Inputs/CustomerInput.vue")
);

export const DelegateInput = defineAsyncComponent(() =>
    import("../components/Form/Inputs/DelegateInput.vue")
);

export const DetailInput = defineAsyncComponent(() =>
    import("../components/Form/Invoice/DetailInput.vue")
);

export const DetailsTable = defineAsyncComponent(() =>
    import("../components/Form/Invoice/DetailsTable.vue")
);

export const CalculateTable = defineAsyncComponent(() =>
    import("../components/Form/Invoice/CalculateTable.vue")
);

export const PaymentsTable = defineAsyncComponent(() =>
    import("../components/Form/Invoice/PaymentsTable.vue")
);

export const AddBtn = defineAsyncComponent(() =>
    import("../components/Buttons/AddBtn.vue")
);

export const RemoveBtn = defineAsyncComponent(() =>
    import("../components/Buttons/RemoveBtn.vue")
);
export const RemarksInput = defineAsyncComponent(() =>
    import("../components/Form/Inputs/RemarksInput.vue")
);

export const BaseBtn = defineAsyncComponent(() => import("../components/Buttons/BaseBtn.vue"));

export const DialogConfirm = defineAsyncComponent(() =>
    import("../components/Dialogs/DialogConfirm.vue")
);

export const DialogForm = defineAsyncComponent(() =>
    import("../components/Dialogs/DialogForm.vue")
);

export const VisibleColumns = defineAsyncComponent(() =>
    import("../components/Table/VisibleColumns.vue")
);
export const FileUpload = defineAsyncComponent(() =>
    import("../modules/upload/views/components/FileUpload.vue")
);
export const CountryFlag = defineAsyncComponent(() => import("vue-country-flag-next"));
