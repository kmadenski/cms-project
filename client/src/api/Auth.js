import request from 'superagent'
import {ENTRYPOINT} from '../config/entrypoint'

export default {
  register: userData => {
    return request
      .post(ENTRYPOINT + '/people')
      .set('accept', 'application/json')
      .send(userData)
      // .then(res => {
      //   // Token.set(res.body.jwt)
      //   console.log(res)

      //   return res
      // })
  }
}

