import { combineReducers } from 'redux';
import GlobalStateReducer from './reducers/GlobalStateReducer';

export const RootReducer = combineReducers({
  globalState: GlobalStateReducer
})

export const initialState = {
  globalState: GlobalStateReducer.initialState
}

export default RootReducer;