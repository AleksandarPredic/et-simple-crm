import Request from './Request';

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
    this.getTimeToInput();
  }

  /**
   * Fire on form submit
   * @param {object} event
   */
  submit(event) {
    event.preventDefault();
    const formData = new FormData(this.form);

    this.loaderState(true);

    Request(formData, (response) => {
      this.form.querySelector('.et-simple-crm-form__message').innerHTML = response.data.message;
      this.loaderState(false);
    });
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

  /**
   * Fetch time on load and put the value into a hidden input
   */
  getTimeToInput() {

    const formData = new FormData(this.form);
    formData.set('action', etSimpleCRMData.timeAction);

    Request(formData, (response) => {
      this.form.querySelector('.et-simple-crm-form__time').value = response.data.time;
    });
  }
}

export default Form;
