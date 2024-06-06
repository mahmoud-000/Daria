export default {
  validations: {
    required: "The field @:{property} is required.",
    requiredIf: "The field @:{property} is required.",
    email: "The field @:{property} must be valid email.",
    minLength: "The @:{property} must be at least {min} characters.",
    maxLength: "The @:{property} must not be greater than {max} characters.",
    minValue: 'The @:{property} must be at least {min}.',
    maxValue: "The @:{property} must not be greater than {max}.",
    sameAs: "The @:{property} does not match @:{other}.",
    contains_uppercase: "The @:{property} must contain at least one uppercase letter.",
    contains_lowercase: "The @:{property} must contain at least one lowercase letter.",
    contains_number: "The @:{property} must contain at least one number.",
    contains_special: "The @:{property} must contain at least one special character.",
    url: 'The @:{property} must be a valid URL.',
    integer: 'The @:{property} must be an integer.',
    slug_rule: 'This @:{property} must only contain letters, numbers, dashes and underscores.',
  }
}