export default {
  select: {
    gender: {
      male: "Male",
      female: "Female",
    },
    driver: {
      smtp: "SMTP",
    },
    host: {
      localhost: "Localhost",
      mailhog: "Mailhog",
      mailtrap: "Mailtrap",
    },
    status: {
      active: 'Active',
      not_active: 'Not Active',
    },
    app_names: {
      purchase: 'Purchase',
      purchase_return: 'Purchase Return',
      sale: 'Sale',
      sale_return: 'Sale Return',
    },
    adjustment: {
      addition: "Addition",
      subtraction: "Subtraction"
    },
    delegate_type: {
      individual: "Individual",
      company: "Company",
    },
    tax: {
      exclusive: "Exclusive",
      inclusive: "Inclusive",
    },
    discount: {
      fixed: "Fixed",
      percent: "Percent %",
    },
    operators: {
      multiply: "Multiply (*)",
      divide: "Divide (/)",
    },
    item_type: {
      standard: "Standard Item",
      variable: "Variable Item",
      service: "Service",
    },
    product_type: {
      stock: "Stock Product",
      consumer: "Consumer Product",
    },
    payment_status: {
      paid: "Paid",
      unpaid: "Unpaid",
      partial: "Partial",
    },
    branch: {
      main: "Main",
      not_main: "Not Main",
    },

    stage: {
      default: "Default",
      not_default: "Not Default",
    },
  }
}