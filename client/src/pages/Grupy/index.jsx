import React from 'react'
import { Switch, Route } from 'react-router-dom'
import Create from './Create'

const Grupy = () => {
  return (
    <Switch>
      <Route path="/grupy/dodaj" component={Create}/>
      <Route path="/grupy"><h1>grupy</h1></Route>
    </Switch>
  )
}

export default Grupy
