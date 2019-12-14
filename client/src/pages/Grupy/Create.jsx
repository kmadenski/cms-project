import React from 'react'
import {Row, Col, Typography, Form, Button, Input, Switch, Radio, Cascader} from 'antd'
import {places} from './places'

const locations = [
  {
    value: 'dolnośląskie',
    label: 'dolnośląskie',
    children: []
  },
  {
    value: 'kujawsko-pomorskie',
    label: 'kujawsko-pomorskie',
    children: []
  },
  {
    value: 'lubelskie',
    label: 'lubelskie',
    children: []
  },
  {
    value: 'lubuskie',
    label: 'lubuskie',
    children: []
  },
  {
    value: 'łódzkie',
    label: 'łódzkie',
    children: []
  },
  {
    value: 'małopolskie',
    label: 'małopolskie',
    children: []
  },
  {
    value: 'mazowieckie',
    label: 'mazowieckie',
    children: []
  },
  {
    value: 'opolskie',
    label: 'opolskie',
    children: []
  },
  {
    value: 'podkarpackie',
    label: 'podkarpackie',
    children: []
  },
  {
    value: 'podlaskie',
    label: 'podlaskie',
    children: []
  },
  {
    value: 'pomorskie',
    label: 'pomorskie',
    children: []
  },
  {
    value: 'śląskie',
    label: 'śląskie',
    children: []
  },
  {
    value: 'świętokrzyskie',
    label: 'świętokrzyskie',
    children: []
  },
  {
    value: 'warmińsko-mazurskie',
    label: 'warmińsko-mazurskie',
    children: []
  },
  {
    value: 'wielkopolskie',
    label: 'wielkopolskie',
    children: []
  },
  {
    value: 'zachodniopomorskie',
    label: 'zachodniopomorskie',
    children: []
  }
]

export default () => {
  return (
    <Row type="flex" justify="center">
      <Col span={24}>
        <Typography.Title level={2}>Utwórz grupę</Typography.Title>
        <Form labelCol={{ span: 3 }} wrapperCol={{ span: 9 }} >
          <Form.Item label="Nazwa"><Input name="name" /></Form.Item>
          <Form.Item label="Grupa prywatna"><Switch name="private"/></Form.Item>
          <Form.Item label="Lokalizacja"><Cascader options={locations}/></Form.Item>

          <Button type="primary" htmlType="submit" size="large">Wyślij</Button>
        </Form>
      </Col>
    </Row>
  )
}
