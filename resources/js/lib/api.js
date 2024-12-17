import { writable } from 'svelte/store';

class GradebookAPI {
  #baseUrl = 'https://gradebook.epcst.edu.ph/api/';
  #user = {};
  #headers = {
    'accept': 'application/json',
    'accept-language': 'en-US',
    'content-type': 'application/json'
  }
  #authorizationHeader = {
    'authorization': 'Bearer [[API_KEY]]'
  }

  /**
   * Sets the Auth API token.
   * @param {string} token - api token
   */
  setToken(token) {
    this.#authorizationHeader['authorization'] = this.#authorizationHeader['authorization'].replace('[[API_KEY]]', token);
  }

  /**
   * Sets the header of the fetch
   *
   * @param {string} key - key to header to modify (or add)
   * @param {string} value - value to set
   */
  setHeaders(key, value) {
    this.#headers[key] = value;
  }

  /**
   * Retrieve an API token, if it doesn't exist already.
   *
   * @param {object} credentials - user credentials to get api token
   *
   * @returns {Promise<void>}
   */
  async login(credentials) {
    return fetch(this.#baseUrl + 'login', {
      headers: this.#headers,
      body: JSON.stringify(credentials),
      method: 'POST'
    })
      .then((res) => res.json())
      .then((data) => {
        return data;
      });
  }

  /**
   * Hit a particular endpoint
   *
   * @param {string} endpoint - endpoint to hit.
   * @param {string} method - method to use, defaults to 'GET'
   * @param {object?} body - data to send over if present.
   * @returns {Promise<any>} - result of the fetch
   */
  async fetch(endpoint, method = 'GET', body = {}) {
    this.setToken(localStorage.getItem('apiToken') ?? '[[API_KEY]]');

    let headers = {...this.#headers, ...this.#authorizationHeader};
    const options = {
      headers: headers,
      method: method
    };

    if(method !== 'GET') {
      options['body'] = JSON.stringify(body);
    }

    return fetch(this.#baseUrl + endpoint, options).then((res) => res.json());
  }
}

export const api = writable(new GradebookAPI());
export const academicYears = writable([]);

