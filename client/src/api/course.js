import { authAgent } from "./index";

export default {
  getAll: (page = 1) => authAgent.get('/courses'),
  add: (data) => authAgent.post('/courses').send(data)
}
