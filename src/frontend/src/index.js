import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';
import promiseMiddleware from 'redux-promise';
import axiosMiddleware from 'redux-axios-middleware';
import axios from 'axios';
import { createStore, applyMiddleware } from 'redux';

import App from './components/app';
import reducers from './reducers';
import './style/style.styl';

function fishCaptcha(selector = '#app', {refreshUrl, input, onInputChange = null} = {}) {
  const client = axios.create({});
  const createStoreWithMiddleware = applyMiddleware(axiosMiddleware(client), promiseMiddleware)(createStore);

  let onInputChangeCallback = () => {};

  if (typeof onInputChange === 'string') {
    onInputChangeCallback = function() {
      eval(onInputChange);
    };
  }

  ReactDOM.render(
    <Provider store={createStoreWithMiddleware(reducers)}>
      <App
        refreshUrl={refreshUrl}
        input={document.querySelector(input)}
        onInputChange={onInputChangeCallback}/>
    </Provider>
    , document.querySelector(selector));

}
/* global NODE_ENV */
if (NODE_ENV !== 'production') {
  fishCaptcha();
} else {
  window.fishCaptcha = fishCaptcha;
}


