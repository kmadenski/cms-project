import React from 'react'
import {connect} from 'react-redux'
import { Form, Row, Col, Typography, Input, Switch, Button, notification } from 'antd'
import userAPI from '../api/Auth'
import { login } from './../store/user'

const UserProfile = ({user, form, loginAction}) => {
  const [sending, setSending] = React.useState(false)
  const {getFieldDecorator} = form

  const handleSubmit = e => {
    e.preventDefault()

    form.validateFieldsAndScroll((err, values) => {
      if (!err) {
        setSending(true)

        userAPI
          .updateUser(values)
          .then((data) => {
            loginAction(data.body)
            notification.success({message: 'Profil zaktualizowany'})
          })
          .catch(error => notification.error({
            message: 'Error',
            description: JSON.stringify(error.body || error).substr(0, 255)
          }))
          .finally(() => setSending(false))
      }
    });
  }

  return (
    <Row type="flex" justify="center">
      <Col span={6}>
        <Typography.Title level={2}>Edycja profilu</Typography.Title>
        <Form onSubmit={handleSubmit}>
          <Form.Item label="E-mail">{getFieldDecorator('email', {initialValue: user.email})(<Input />)}</Form.Item>

          <Form.Item label="Login">{getFieldDecorator('name', {initialValue: user.name})(<Input />)}</Form.Item>

          <Form.Item label="Numer telefonu">{getFieldDecorator('telephone', {initialValue: user.telephone})(<Input />)}</Form.Item>

          <Button type="primary" htmlType="submit" size="large" loading={sending}>Aktualizuj</Button>
        </Form>
      </Col>
    </Row>
  )
}

const UserProfileWithForm = Form.create({name: 'userEdit'})(UserProfile)
export default connect(
  state => ({user: state.user}),
  {loginAction: login}
)(UserProfileWithForm)
