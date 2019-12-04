import React from 'react'
import Auth from '../api/Auth'
import {Form, Input, Button, DatePicker, Row, Col, Alert, Typography } from 'antd'

const Register = () => {
  const [errorMessage, setErrorMessage] = React.useState(null)
  const [sending, setSending] = React.useState(false)

  const handleSubmit = e => {
    e.preventDefault()

    setSending(true)

    const form = e.target
    const formData = new FormData(form)
    const data = Object.fromEntries(formData.entries())

    Auth
      .register(data)
      .then()
      .catch(error => setErrorMessage(error.response.body.detail))
      .finally(() => setSending(false))
  }

  return (
    <Row type="flex" justify="center" className="my-5">
      <Col span={6}>
        <Typography.Title level={2}>Rejestracja</Typography.Title>
        <Form onSubmit={handleSubmit} autoComplete="off">
          <Form.Item label="E-mail"><Input size="large" type="email" name="email" /></Form.Item>
          <Form.Item label="Hasło"><Input.Password size="large" type="password" name="password" /></Form.Item>
          <Form.Item label="Data urodzenia"><DatePicker className="d-block" size="large" name="birthDate" /></Form.Item>

          {
            errorMessage &&
            <Alert
              className="mb-4"
              message={errorMessage}
              type="error"
              showIcon
            />
          }

          <Button loading={sending} type="primary" htmlType="submit" block size="large">Wyślij</Button>
        </Form>
      </Col>
    </Row>
  )
}

export default Register
