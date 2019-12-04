import React from 'react';
import ReactDOM from 'react-dom';
import {HashRouter as Router} from 'react-router-dom'
import {Provider} from 'react-redux'
import {createStore, applyMiddleware} from 'redux'
import rootReducer from './store/index'
import App from './App';
import { composeWithDevTools } from 'redux-devtools-extension';
import thunk from 'redux-thunk';

import 'antd/dist/antd.css'
import './style.css'
import './utils.css'

const middleware = [thunk]
const store = createStore(
  rootReducer,
  composeWithDevTools(applyMiddleware(...middleware))
)

const Root = () => (
  <Provider store={store}>
    <Router>
      <App />
    </Router>
  </Provider>
)

ReactDOM.render(
  <Root />,
  document.getElementById('root')
);
