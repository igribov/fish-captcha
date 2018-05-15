export const FETCH_CAPTCHA_IMAGE = 'FETCH_CAPTCHA_IMAGE';
export const FETCH_CAPTCHA_IMAGE_SUCCESS = 'FETCH_CAPTCHA_IMAGE_SUCCESS';

export function fetchCaptchaImage(url) {

  return {
    type: FETCH_CAPTCHA_IMAGE,
    payload: {
      request: {
        url
      }
    }
  };
}