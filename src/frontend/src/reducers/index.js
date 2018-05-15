import { combineReducers } from 'redux';

import CaptchaReduser from './captcha_reducer';

const rootReducer = combineReducers({
  captcha: CaptchaReduser
});

export default rootReducer;
