import {FETCH_CAPTCHA_IMAGE_SUCCESS} from '../actions';

export default function CaptchaReduser(state = {}, action) {
  switch (action.type) {
  case FETCH_CAPTCHA_IMAGE_SUCCESS :
    return {
      url: action.payload.data.url
    };

  default :
    return state;
  }
}