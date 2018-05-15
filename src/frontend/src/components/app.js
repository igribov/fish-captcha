import React, { Component } from 'react';
import Captcha from './captcha';
import PropTypes from 'prop-types';

class App extends Component {
  render() {
    return (
      <Captcha {...this.props}/>
    );
  }
}

App.propTypes = {
  refreshUrl: PropTypes.string.isRequired,
  input: PropTypes.node.isRequired,
  onInputChange: PropTypes.func,
};

export default App;
