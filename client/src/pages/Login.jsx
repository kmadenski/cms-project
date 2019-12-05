import React from 'react'
import Auth from '../api/Auth'
import {Form, Input, Button, Row, Col, Alert, Typography } from 'antd'
import { connect } from 'react-redux'
import { login } from './../store/user'
import { useHistory } from "react-router-dom"

const Login = ({loginAction}) => {
  const [errorMessage, setErrorMessage] = React.useState(null)
  const [sending, setSending] = React.useState(false)
  const history = useHistory()

  const handleSubmit = e => {
    e.preventDefault()

    setSending(true)
    setErrorMessage(null)

    const form = e.target
    const formData = new FormData(form)
    const data = Object.fromEntries(formData.entries())

    Auth
      .login(data)
      .then(({body}) => {
        history.push('/')
        loginAction(body)
      })
      .catch(error => setErrorMessage(error.response.body.message))
      .finally(() => setSending(false))
  }

  return (
    <Row type="flex" justify="center" className="my-5">
      <Col span={6}>
        <Typography.Title level={2}>Logowanie</Typography.Title>
        <Form onSubmit={handleSubmit}>
          <Form.Item label="E-mail"><Input size="large" type="email" name="email" /></Form.Item>
          <Form.Item label="Hasło"><Input.Password size="large" type="password" name="password" /></Form.Item>

          {
            errorMessage &&
            <Alert
              className="mb-4"
              message={errorMessage}
              type="error"
              showIcon
            />
          }

          <Button loading={sending} type="primary" htmlType="submit" block size="large">Zaloguj</Button>
        </Form>
      </Col>
    </Row>
  )
}

const mapDispatchToProps = {
  loginAction: login
}

export default connect(null, mapDispatchToProps)(Login)
