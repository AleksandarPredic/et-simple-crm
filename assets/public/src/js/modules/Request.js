/* global etSimpleCRMData */

/**
 * Make POST requests
 * @param {FormData} data
 * @param {function} callback
 */
const request = (data, callback) => {
  const xhr = new XMLHttpRequest();
  xhr.open('POST', etSimpleCRMData.ajaxUrl, true);
  xhr.send(data);

  xhr.onload = () => {
    if (xhr.readyState == 4) {
      let response = JSON.parse(xhr.response);
      if (callback) {
        callback(response);
      }
    }
  };

  xhr.onerror = () => {
    console.log(etSimpleCRMData.errorMessage);
  };
};

export default request;
