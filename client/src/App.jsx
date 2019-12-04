import React from 'react'
import { Layout } from 'antd'
import { Switch, Route } from 'react-router-dom'
import Header from './components/Header'

const { Footer, Content } = Layout;

const App = () => {
  return (
    <Layout>
      <Header />

      <Content>
        <Switch>
          <Route path="/">Home page</Route>
        </Switch>
      </Content>

      <Footer>
        <a href="https://localhost:8443/" target="_blank">Swagger</a>
      </Footer>
    </Layout>
  )
}

export default App
