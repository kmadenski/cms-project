import { authAgent } from "./index";

export default {
  getAll: (page = 1) => authAgent.get('/skills')
}
