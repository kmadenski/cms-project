import React from 'react'
import { Layout, Menu, Row, Col, Button } from 'antd'
import {Link} from 'react-router-dom'
import {connect} from 'react-redux'
import Cookie from 'js-cookie'
import {logout as logoutAction} from '../store/user'

const menuProps = {
  theme: "dark",
  mode: "horizontal",
  style: {lineHeight: '64px'}
}

const Header = ({user}) => {
  const logout = () => {
    logoutAction()
    Cookie.remove('token')
    Cookie.remove('userId')
  }

  return (
    <Layout.Header>
      <Row type="flex" align="middle">
        <Col><div className="logo" /></Col>

        <Col>
          <Menu {...menuProps}>
            <Menu.Item key="1">
              Grupy
              <Link to="/grupy" />
            </Menu.Item>
          </Menu>
        </Col>

        <Col className="ml-auto">
          {
            user.name
            ? (
              <>
                <Link className="text-white mr-3" to="/profil">{user.name}</Link>
                <Button onClick={logout}>wyloguj</Button>
              </>
            )
            : (
              <>
                <Link className="ant-btn mr-2" to="/login">Logowanie</Link>
                <Link className="ant-btn ant-btn-primary" to="/rejestracja">Rejestracja</Link>
              </>
            )
          }
        </Col>
      </Row>
    </Layout.Header>
  )
}

const mapStateToProps = state => ({
  user: state.user
})

const mapDispatchToProps = {
  logoutAction
}

export default connect(mapStateToProps, mapDispatchToProps)(Header)
