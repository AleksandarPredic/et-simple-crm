import Form from "./modules/Form";

(function (global, $) {

  const forms = document.getElementsByClassName('et-simple-crm-form__form');

  if (forms.length < 1) {
    return;
  }

  for (let form of forms) {
    new Form(form);
  }

})(window, jQuery);
