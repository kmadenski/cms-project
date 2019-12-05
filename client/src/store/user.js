const actionTypes = {
  login: 'USER_LOGIN',
  logout: 'USER_LOGOUT'
}

export default (state = {}, action) => {
  switch(action.type) {
    case actionTypes.login:
      return action.payload

    case actionTypes.logout:
      return {}

    default: return state
  }
}

export const login = payload => ({type: actionTypes.login, payload})
export const logout = () => ({type: actionTypes.logout})
