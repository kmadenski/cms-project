import React from 'react'
import Cookie from 'js-cookie'
import {Row, Col, Typography, Form, Button, Input, Switch, Radio, Cascader, Select, InputNumber, notification} from 'antd'
import skillsAPI from './../../api/skills'
import courseAPI from './../../api/course'

const requiredFiled = {rules: [{required: true}]}

class CreateGroup extends React.Component {
  constructor(props) {
    super(props)

    this.state = {
      skills: null,
      sending: false,
      loading: false
    }
  }

  componentDidMount() {
    skillsAPI
      .getAll()
      .then(({body}) => this.setState({skills: body['hydra:member']}))
      .catch(error => notification.error({
        message: 'Błąd przy pobieraniu tematów',
        description: JSON.stringify(error.body || error).substr(0, 255)
      }))
  }

  submit = (e) => {
    e.preventDefault()

    this.props.form.validateFieldsAndScroll((err, values) => {
      if (!err) {
        this.setState({loading: true})
        courseAPI
          .add({
            ...values,
            isPrivate: Boolean(values.isPrivate),
            minimumAttendeeCapacity: 1,
            editor: 'people/' + Cookie.get('userId'),
          })
          .then(console.log)
          .finally(() => this.setState({loading: false}))
      }
    });
  }

  render() {
    const {skills, sending} = this.state
    const {getFieldDecorator} = this.props.form

    return (
      <Row type="flex" justify="center">
        <Col span={24}>
          <Typography.Title level={2}>Utwórz grupę</Typography.Title>
          <Form onSubmit={this.submit}>
            <Form.Item label="Tematy">
              {getFieldDecorator('abouts', requiredFiled)(
                <Select mode="multiple" name="skills" loading={!Boolean(skills)}>
                  {skills && skills.map(({id, name}) => <Select.Option key={id} value={`skills/${id}`}>{name}</Select.Option>)}
                </Select>
              )}
            </Form.Item>

            <Form.Item label="Opis">
              {getFieldDecorator('abstract', requiredFiled)(
                <Input.TextArea rows={4}/>
              )}
            </Form.Item>

            <Form.Item label="Maksymalna liczba osób">
              {getFieldDecorator('maximumAttendeeCapacity', requiredFiled)(
                <InputNumber min={1} max={100} />
              )}
            </Form.Item>

            <Form.Item label="Grupy prywatna">
              {getFieldDecorator('isPrivate')(<Switch />)}
            </Form.Item>

            <Button type="primary" htmlType="submit" size="large" loading={sending}>Wyślij</Button>
          </Form>
        </Col>
      </Row>
    )
  }
}

export default Form.create({name: 'creategroup'})(CreateGroup)
