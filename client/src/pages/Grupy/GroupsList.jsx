import React from 'react'
import {Link} from 'react-router-dom'
import courseAPI from '../../api/course'
import { Card, Progress, Row, Col, Tag, Icon, Tooltip, Empty, Checkbox, Pagination } from 'antd'
import {connect} from 'react-redux'

const SIDEBAR_SIZE = 6

const calcProgress = group => {
  const max = group.maximumAttendeeCapacity
  const val = group.contributors.length

  return val * 100 / max
}

const getActions = (group, user) => {
  const actions = []

  const isAdmin = group.editor.email === user.email
  const isMember = false

  if (!isAdmin && !isMember) {
    actions.push(<Tooltip title="Dołącz do grupy"><Icon type="user-add" key="setting" /></Tooltip>)
  }

  return actions
}

const getExtra = (group, user) => {
  const isAdmin = group.editor.email === user.email

  return (
    <>
      {isAdmin && <Tag>Admin</Tag>}
      {group.private && <Icon type="lock" />}
    </>
  )
}

const options = [
  { label: 'Apple', value: 'Apple' },
  { label: 'Pear', value: 'Pear' },
  { label: 'Orange', value: 'Orange' },
]

const GroupsList = ({user}) => {
  const [groups, setGroups] = React.useState(null)

  React.useEffect(() => {
    courseAPI.getAll().then(({body}) => setGroups(body))
  }, [])

  return (
    <div>
      <h1>Grupy</h1>
      <Link className="ant-btn ant-btn-primary" to="/grupy/dodaj">Dodaj grupę</Link>

      <Row type="flex" gutter={[15, 15]} className="mt-3">
        <Col span={SIDEBAR_SIZE}>
          <div className="filters-block">
            <h2>Filtry</h2>
            <h3>Zainteresowania</h3>
            <Checkbox.Group options={options}/>
          </div>
        </Col>

        <Col span={24 - SIDEBAR_SIZE}>
          <Row type="flex" gutter={[15, 15]}>
            {!groups && new Array(6).fill().map((_, i) => (
              <Col span={6} key={i}><Card loading={true} /></Col>
            ))}

            {
              (groups && groups['hydra:member'].length === 0)
                ? <Empty className="m-auto" description="Brak grup"/>
                : null
            }

            {groups && groups['hydra:member'].map(group => (
              <Col span={6} key={group.id}>
                <Card
                  title={`Grupa ID: ${group.id}`}
                  actions={getActions(group, user)}
                  extra={getExtra(group, user)}
                >
                  Stan zapełniena grupy ({group.contributors.length} / {group.maximumAttendeeCapacity})
                  <Progress percent={calcProgress(group)} size="small" showInfo={false}/>
                  <p>{group.abstract}</p>
                </Card>
              </Col>
            ))}
          </Row>
        </Col>

        {
          groups && (
            <Col span={24 - SIDEBAR_SIZE} offset={SIDEBAR_SIZE} className="text-center">
              <Pagination defaultCurrent={1} total={groups['hydra:totalItems']} />
            </Col>
          )
        }
      </Row>
    </div>
  )
}

export default connect(state => ({
  user: state.user
}))(GroupsList)
