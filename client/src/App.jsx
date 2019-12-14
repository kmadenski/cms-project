import React from 'react'
import { Layout, Spin, Result } from 'antd'
import { Switch, Route } from 'react-router-dom'
import { connect } from 'react-redux'
import Cookie from 'js-cookie'
import Header from './components/Header'
import Register from './pages/Register';
import Login from './pages/Login';
import Auth from './api/Auth'
import { login } from './store/user'
import Grupy from './pages/Grupy/index'

const { Footer, Content } = Layout;

const App = ({loginAction}) => {
  const [loading, setLoading] = React.useState()

  React.useEffect(() => {
    const userId = Cookie.get('userId')

    if (userId) {
      setLoading(true)

      Auth
        .me()
        .then(({body}) => loginAction(body))
        .finally(() => setLoading(false))
    }
  }, [])

  if (loading) return <div className="text-center m-5"><Spin size="large"/></div>

  return (
    <Layout>
      <Header />

      <Content style={{ padding: '0 50px' }}>
        <div className="mt-3" style={{ background: '#fff', padding: 24, minHeight: 280 }}>
          <Switch>
            <Route path="/" exact>Home page</Route>
            <Route path="/rejestracja"><Register /></Route>
            <Route path="/login"><Login /></Route>
            <Route path="/grupy"><Grupy /></Route>
            <Route path="*"><Result status="404"/></Route>
          </Switch>
        </div>
      </Content>

      <Footer theme="dark">
        <a href="https://localhost:8443/" target="_blank">Swagger</a>
      </Footer>
    </Layout>
  )
}

export default connect(
  null,
  {
    loginAction: login
  }
)(App)
