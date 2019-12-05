import Cookie from 'js-cookie'
import {agent, authAgent} from './index'

export default {
  register: data => agent.post('/people').send(data),

  login: credentials => agent
    .post('/authentication_token')
    .send(credentials)
    .then(({body}) => {
      Cookie.set('token', body.token)
      Cookie.set('userId', body.id)

      return authAgent.get(`/people/${body.id}`)
    }),

  me: () => {
    const userId = Cookie.get('userId')
    return authAgent.get(`/people/${userId}`)
  }
}
