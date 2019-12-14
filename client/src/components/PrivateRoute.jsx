import React from 'react'
import {Route, Redirect} from 'react-router-dom'
import {connect} from 'react-redux'
import {isAuthorized} from '../store/user'

function PrivateRoute({ isAuth, children, ...rest }) {
  return (
    <Route
      {...rest}
      render={({ location }) =>
        isAuth ? (
          children
        ) : (
          <Redirect
            to={{
              pathname: "/login",
              state: { from: location }
            }}
          />
        )
      }
    />
  );
}

export default connect(state => ({
  isAuth: isAuthorized(state)
}))(PrivateRoute)
