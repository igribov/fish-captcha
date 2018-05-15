import React, {Component} from 'react';
import {connect} from 'react-redux';
import PropTypes from 'prop-types';
import {bindActionCreators} from 'redux';
import {debounce} from 'lodash';

import {fetchCaptchaImage} from '../actions';

class Captcha extends Component {

  constructor(props) {
    super(props);
    this.fetchCaptchaImage = this.fetchCaptchaImage.bind(this);
    this.refreshCaptchaImage = this.refreshCaptchaImage.bind(this);
    this.updateLinkenInput = this.updateLinkenInput.bind(this);
  }

  componentDidMount() {
    this.fetchCaptchaImage();
  }

  fetchCaptchaImage() {
    this.props.fetchCaptchaImage(this.props.refreshUrl);
  }

  refreshCaptchaImage(e) {
    e.preventDefault();
    this.fetchCaptchaImage();
  }

  updateLinkenInput(value) {
    this.props.input.value = value;
    this.props.onInputChange();
  }

  render() {
    const disable = false;
    if (!this.props.captchaUrl) {
      return (<div>Loading . . .</div>);
    }
    const initialValue = this.props.input.value || '',
      updateField = debounce(value => this.updateLinkenInput(value), 500);

    return (
      <div className="fish_captcha p-10">
        <div>
          <div className="fish_captcha__image mb-10">
            <img src={this.props.captchaUrl}/>
          </div>
        </div>
        <div className="fish_captcha__form p-10">
          <div className="form-group fish_captcha__form-group-input ib">
            <input onChange={e => updateField(e.target.value)} defaultValue={initialValue} className="form-control" type="text"/>
          </div>
          <div className="form-group fish_captcha__form-group-refresh ib">
            <button onClick={this.refreshCaptchaImage} disabled={disable} className="btn btn-info">↺</button>
          </div>
        </div>
      </div>
    );
  }
}

Captcha.propTypes = {
  refreshUrl: PropTypes.string.isRequired,
  input: PropTypes.node.isRequired,
  onInputChange: PropTypes.func,
  fetchCaptchaImage: PropTypes.func,
  captchaUrl: PropTypes.string,
};

function mapStateToProps({captcha}) {
  return {captchaUrl: captcha.url || ''};
}

function mapDispatchToProps(dispatch) {
  return bindActionCreators({fetchCaptchaImage}, dispatch);
}

export default connect(mapStateToProps, mapDispatchToProps)(Captcha);