/* global etSimpleCRMData */

/**
 * Represents a single form in the DOM
 */
class Form {
  constructor(element) {
    this.form = element;
    this.form.addEventListener('submit', this.submit.bind(this));
    this.loader = this.form.querySelector('.et-simple-crm-loader');
    this.form.addEventListener('input', this.verifyNumberMaxValue.bind(this));
  }

  /**
   * Fire on form submit
   * @param {object} event
   */
  submit(event) {
    event.preventDefault();
    const formData = new FormData(this.form);
    const xhr = new XMLHttpRequest();

    this.loaderState(true);
    xhr.open('POST', etSimpleCRMData.ajaxUrl, true);
    xhr.send(formData);

    xhr.onload = () => {
      if (xhr.readyState == 4) {
        let response = JSON.parse(xhr.response);
        console.log(xhr.response);
        this.form.querySelector('.et-simple-crm-form__message').innerHTML = response.data.message;
        this.loaderState(false);
      }
    };

    xhr.onerror = () => {
      console.log("An error occurred during the form sumission");
    };
  }

  /**
   * Manage loader state: Hide or show
   * @param {boolean} state
   */
  loaderState(state)
  {
    if (state) {
      this.loader.style.opacity = 1;
    } else {
      this.loader.style.opacity = 0;
    }
  }

  /**
   * Verify an input[type="number"] max attribute and validate
   * @param {object} event
   */
  verifyNumberMaxValue(event) {
    let input = event.target;
    if (!input.hasAttribute('max')) {
      return;
    }

    let max = Number(input.getAttribute('max'));
    if (input.value > max) {
      input.value = max;
    }
  }
}

export default Form;
