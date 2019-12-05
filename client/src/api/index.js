import superagent from 'superagent'
import Cookie from 'js-cookie'
import {ENTRYPOINT} from '../config/entrypoint'

const urlPrefix = request => {
  request.url = ENTRYPOINT + request.url

  return request
}

const auth = request => {
  request.set({ Authorization: 'Bearer ' + Cookie.get('token') })

  return request
}

/** @type {superagent} */
export const agent = superagent
  .agent()
  .use(urlPrefix)
  .accept('application/json')
  .type('application/json')

/** @type {superagent} */
export const authAgent = agent.use(auth)
