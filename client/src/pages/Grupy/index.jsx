import React from 'react'
import { Switch, Route } from 'react-router-dom'
import Create from './Create'
import GroupsList from './GroupsList'
import PrivateRoute from '../../components/PrivateRoute'

const Grupy = () => {
  return (
    <Switch>
      <PrivateRoute path="/grupy/dodaj"><Create /></PrivateRoute>
      <Route path="/grupy"><GroupsList /></Route>
    </Switch>
  )
}

export default Grupy
