import React from 'react'
import { Layout } from 'antd'
import { Switch, Route } from 'react-router-dom'
import Header from './components/Header'
import Register from './pages/Register';

const { Footer, Content } = Layout;

const App = () => {
  return (
    <Layout>
      <Header />

      <Content>
        <Switch>
          <Route path="/" exact>Home page</Route>
          <Route path="/rejestracja"><Register /></Route>
        </Switch>
      </Content>

      <Footer theme="dark">
        <a href="https://localhost:8443/" target="_blank">Swagger</a>
      </Footer>
    </Layout>
  )
}

export default App
