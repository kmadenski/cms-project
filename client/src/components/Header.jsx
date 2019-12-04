import React from 'react'
import { Layout, Menu, Row, Col, Button } from 'antd'
import {Link} from 'react-router-dom'

const menuProps = {
  theme: "dark",
  mode: "horizontal",
  style: {lineHeight: '64px'}
}

const Header = () => {
  return (
    <Layout.Header>
      <Row type="flex" align="middle">
        <Col><div className="logo" /></Col>

        <Col>
          <Menu {...menuProps}>
            <Menu.Item key="1">nav 1</Menu.Item>
            <Menu.Item key="2">nav 2</Menu.Item>
            <Menu.Item key="3">nav 3</Menu.Item>
          </Menu>
        </Col>

        <Col className="ml-auto">
          <Link className="ant-btn mr-2" to="/login">Logowanie</Link>
          <Link className="ant-btn ant-btn-primary" to="/rejestracja">Rejestracja</Link>
        </Col>
      </Row>
    </Layout.Header>
  )
}

export default Header
